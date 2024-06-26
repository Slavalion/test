<script setup>
import { Head, router, usePage } from '@inertiajs/vue3'
import dayjs from 'dayjs'
import { computed, ref, reactive, watch } from 'vue'
import { currencyFormater } from '@/Helpers/formater'
import AppIcon from '@/Components/AppIcon.vue'
import AppButton from '@/Components/AppButton.vue'
import AppPagination from '@/Components/AppPagination.vue'
import AppTable from '@/Components/AppTable.vue'
import TableTh from '@/Components/Table/TableTh.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import device from 'vue3-device-detector'
import { useWindowSize } from '@vueuse/core'
import Modal from '@/Components/ModalMobileTariffs.vue'

const props = defineProps({
    transactions: {
        type: Array,
        required: true,
    },
    paginator: {
        type: Array,
        default: () => [],
    },
    activeSection: {
        type: String,
        required: true,
    },
})

const { width } = useWindowSize()
const isModalShowed = ref(false)
const mobileCurrentSection = ref('Баланс')

const actualSections = ['balance', 'refill', 'debit']

const actualSection = reactive(
    props.transactions.reduce((acc, curVal) => {
        if (acc.includes(curVal.task_type)) {
            return acc
        }
        return [...acc, curVal.task_type]
    }, [])
)

const nextSection = (section) => {
    setSection(section)
    isModalShowed.value = false
}

const targetToText = {
    'App\\Models\\PurchaseGroup': 'Группа выкупов',
    review: 'Отзыв',
    return_purchase: 'Возврат за выкуп',
    livecargo: 'Забор товара',
    review_reaction: 'Реакция на отзыв',
    return_review: 'Возврат за отзыв',
    return_pickup: 'Возврат за забор',
    'App\\Models\\ReviewComplaintGroup': 'Жалобы на отзыв',
    'App\\Models\\ReviewReactionGroup': 'Реакции на отзыв',
    'App\\Models\\ActionCartGroup': 'Действия: добавление в корзину',
    'App\\Models\\ActionSearchGroup': 'Действия: поиск по запросам',
    balance: 'Пополнение баланса',
    balance_manually: 'Пополение баланса (поддержка)',
}

const targetText = (target, target_id) => {
    if (target == 'purchase') {
        return 'Выкуп #' + target_id
    }

    if (target == 'review') {
        return 'Отзыв #' + target_id
    }

    if (target == 'livecargo') {
        return 'Забор товара #' + target_id
    }

    if (target == undefined) {
        return 'Выкуп #' + target_id
    }

    return target
}

const userHasTgPayment = computed(
    () => usePage().props.auth.user.preferences?.paymentChatId != null
)

const targetOnlyText = (target) => {
    return targetToText[target] ? targetToText[target] : 'Не определено'
}

const currentSection = ref(props.activeSection)

const setSection = (section) => {
    currentSection.value = section

    router.reload({
        only: ['transactions', 'paginator', 'activeSection'],
        data: {
            section: currentSection.value,
            page: 1,
        },
    })
}

watch(
    () => currentSection.value,
    () => {
        if (currentSection.value === 'balance') {
            mobileCurrentSection.value = 'Баланс'
        } else if (currentSection.value === 'refill') {
            mobileCurrentSection.value = 'Пополнение'
        } else {
            mobileCurrentSection.value = 'Списание'
        }
    }
)
</script>
<template class="transactions">
    <Head>
        <title>Транзакции</title>
    </Head>

    <AuthenticatedLayout>
        <template #header>Транзакции</template>

        <div v-if="!(device().isDesktop && width > 390)" class="input-wrapper">
            <div @click="isModalShowed = !isModalShowed" class="mobile-section-input">
                <p>{{ mobileCurrentSection }}</p>
                <AppIcon icon="chevron-down" />
            </div>
        </div>

        <div class="panel mb-6" v-if="device().isDesktop && width > 390">
            <div class="flex gap-1.5">
                <!-- <AppButton
                    theme="normal"
                    :class="{ btn_selected: activeSection == 'wallets' }"
                    @click="setSection('wallets')"
                >
                    Кошельки
                </AppButton>
                <AppButton
                    theme="normal"
                    :class="{ btn_selected: activeSection == 'telegram' }"
                    v-if="userHasTgPayment"
                    @click="setSection('telegram')"
                >
                    Телеграм
                </AppButton> -->
                <AppButton
                    theme="normal"
                    :class="{ btn_selected: activeSection == 'balance' }"
                    @click="setSection('balance')"
                >
                    Все
                </AppButton>
                <AppButton
                    theme="normal"
                    :class="{ btn_selected: activeSection == 'refill' }"
                    @click="setSection('refill')"
                >
                    Пополнение
                </AppButton>
                <AppButton
                    theme="normal"
                    :class="{ btn_selected: activeSection == 'debit' }"
                    @click="setSection('debit')"
                >
                    Списание
                </AppButton>

                <div class="ml-auto">
                    <a href="/transactions/download" target="_blank" download>
                        <AppButton icon="file-download">Скачать XLS</AppButton>
                    </a>
                </div>
            </div>
        </div>

        <div class="panel panel_p-lg">
            <AppTable v-if="activeSection == 'wallets'">
                <template #head>
                    <tr>
                        <TableTh class="text-left">ID</TableTh>
                        <TableTh>Назначение</TableTh>
                        <TableTh>Сумма</TableTh>
                        <TableTh>Кошелек</TableTh>
                        <TableTh>Дата</TableTh>
                    </tr>
                </template>

                <tr v-for="transaction in transactions" :key="transaction" class="main-table__tr">
                    <template v-if="transaction.purchase">
                        <td class="text-left">{{ transaction.id }}</td>

                        <td class="text-center">
                            <div class="flex flex-col">
                                <span>
                                    {{ transaction.purchase.product.brand }}
                                    {{ transaction.purchase.product.name }}
                                </span>
                                <span>Артикул: {{ transaction.purchase.product_id }}</span>
                                <span>
                                    {{ targetText(transaction.target, transaction.purchase_id) }}
                                </span>
                            </div>
                        </td>
                        <td class="text-center">
                            {{ currencyFormater.format(-transaction.amount) }}
                        </td>
                        <td class="text-center">{{ transaction.wallet_name }}</td>
                        <td class="text-center">{{ transaction.created_ts }}</td>
                    </template>
                </tr>
            </AppTable>
            <AppTable v-else-if="activeSection == 'telegram'">
                <template #head>
                    <tr>
                        <TableTh>ID</TableTh>
                        <TableTh>Сумма</TableTh>
                        <TableTh>Назначение</TableTh>
                        <TableTh>Дата</TableTh>
                    </tr>
                </template>

                <tr v-for="transaction in transactions" :key="transaction" class="main-table__tr">
                    <td class="text-center">{{ transaction.id }}</td>
                    <td class="text-center">
                        {{ currencyFormater.format(transaction.data.bot_data.amount) }}
                    </td>
                    <td class="text-center">
                        <div class="flex flex-col">
                            <span>
                                {{ transaction.purchase.product.brand }}
                                {{ transaction.purchase.product.name }}
                            </span>
                            <span>Артикул: {{ transaction.purchase.product_id }}</span>
                            <span>
                                {{ targetText(transaction.target, transaction.purchase_id) }}
                            </span>
                        </div>
                    </td>

                    <td class="text-center">
                        {{ dayjs(transaction.created_at).format('D/M/YYYY h:m') }}
                    </td>
                </tr>
            </AppTable>
            <AppTable v-else-if="device().isDesktop && width > 390" class="transactions-table">
                <template #head>
                    <tr>
                        <TableTh>ID</TableTh>
                        <TableTh>Сумма</TableTh>
                        <TableTh>Описание</TableTh>
                        <TableTh>Тип</TableTh>
                        <TableTh>Дата</TableTh>
                        <TableTh>Статус</TableTh>
                    </tr>
                </template>

                <tr v-for="transaction in transactions" :key="transaction" class="main-table__tr">
                    <td class="text-left">{{ transaction.id }}</td>
                    <td class="text-left p-6">
                        <span v-if="transaction.status == 1" class="functionalBlue">
                            {{ transaction.type == -1 ? '-' : '+' }}
                            {{ currencyFormater.format(transaction.amount / 100) }}
                        </span>
                        <span v-else-if="transaction.status == -1" class="accentRedtext">
                            {{ transaction.type == -1 ? '-' : '+ ' }}
                            {{ currencyFormater.format(transaction.amount / 100) }}
                        </span>
                        <span v-else>
                            {{ transaction.type == -1 ? '-' : '+' }}
                            {{ currencyFormater.format(transaction.amount / 100) }}
                        </span>
                    </td>
                    <td class="text-left">
                        {{ targetOnlyText(transaction.target) }}
                    </td>
                    <td class="text-left">
                        <span v-if="transaction.type == -1">Списание</span>
                        <span v-else>Пополнение</span>
                    </td>
                    <td class="text-left">
                        {{ dayjs(transaction.created_ts).format('DD.MM.YYYY') }} в
                        {{ dayjs(transaction.created_ts).format('hh:mm') }}
                    </td>
                    <td class="text-left p-6">
                        <div v-if="transaction.status == 1" class="accentGreen flex badge">
                            <AppIcon
                                icon="check-circle"
                                width="16"
                                height="16"
                                class="checkCircle mr-1"
                            />
                            Выполнено
                        </div>
                        <div v-else-if="transaction.status == -1" class="accentRed flex badge">
                            <AppIcon
                                icon="alert-octagon"
                                width="16"
                                height="16"
                                class="alertOctagon mr-1"
                            />
                            Ошибка выполнения
                        </div>
                        <div v-else class="accentYellow flex badge">
                            <AppIcon icon="timer" class="mr-1" />Выполняется
                        </div>
                    </td>
                </tr>
            </AppTable>
            <div v-else>
                <div
                    v-for="transaction in transactions"
                    :key="'mobile-' + transaction.id"
                    class="mobile-product-card"
                >
                    <div class="mobile-product-card__image">
                        <AppIcon
                            v-if="transaction.type === -1"
                            icon="minus"
                            class="mobile-product-card__symbol"
                            :fill="
                                transaction.status < 0
                                    ? 'red'
                                    : transaction.status > 0
                                      ? '#1665FF'
                                      : 'black'
                            "
                        />
                        <AppIcon
                            v-if="transaction.type === 1"
                            icon="plus"
                            class="mobile-product-card__symbol"
                            :fill="
                                transaction.status < 0
                                    ? 'red'
                                    : transaction.status > 0
                                      ? '#1665FF'
                                      : 'black'
                            "
                        />
                    </div>
                    <div class="mobile-product-card__info">
                        <div class="mobile-product-card__info__top">
                            <div class="product__code product__code__text">
                                {{ dayjs(transaction.created_ts).format('DD.MM.YYYY') }}
                            </div>
                            <div class="product__quantity">
                                {{ targetOnlyText(transaction.target) }}
                            </div>
                        </div>
                        <div class="mobile-product-card__info__bottom">
                            <div class="product__actions">
                                <span v-if="transaction.status == 1" class="functionalBlue">
                                    {{ currencyFormater.format(transaction.amount / 100) }}
                                </span>
                                <span v-else-if="transaction.status == -1" class="accentRedtext">
                                    {{ currencyFormater.format(transaction.amount / 100) }}
                                </span>
                                <span v-else>
                                    {{ currencyFormater.format(transaction.amount / 100) }}
                                </span>
                            </div>
                            <div class="product__status">
                                <div v-if="transaction.status == 1" class="accentGreen flex badge">
                                    <AppIcon
                                        icon="check-circle"
                                        width="16"
                                        height="16"
                                        class="checkCircle mr-1"
                                    />
                                    Выполнено
                                </div>
                                <div
                                    v-else-if="transaction.status == -1"
                                    class="accentRed flex badge"
                                >
                                    <AppIcon
                                        icon="alert-octagon"
                                        width="16"
                                        height="16"
                                        class="alertOctagon mr-1"
                                    />
                                    Ошибка выполнения
                                </div>
                                <div v-else class="accentYellow flex badge">
                                    <AppIcon icon="timer" class="mr-1" />Выполняется
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <AppPagination :links="paginator" v-if="device().isDesktop && width > 390" />
        <Modal
            :show="isModalShowed"
            :currentSection="currentSection"
            :sections="actualSections"
            @close="nextSection"
            @open="disableInput = true"
        />
    </AuthenticatedLayout>
</template>

<style lang="scss" scoped>
.accentGreen {
    background-color: rgba(22, 192, 80, 0.1);
}
.accentYellow {
    background-color: rgba(216, 155, 0, 0.1);
}
.accentRed {
    background-color: rgba(224, 40, 27, 0.1);
}
.transactions-table thead th:nth-child(1) {
    width: 5.81%;
}
.transactions-table thead th:nth-child(2) {
    width: 14.86%;
}
.transactions-table thead th:nth-child(3) {
    width: 25.1%;
}
.transactions-table thead th:nth-child(4) {
    width: 11.62%;
}
.transactions-table thead th:nth-child(5) {
    width: 19.37%;
}
.transactions-table thead th:nth-child(6) {
    width: 21.49%;
}
</style>
