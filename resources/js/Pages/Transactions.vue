<script setup>
import { Head, router, usePage } from '@inertiajs/vue3'
import dayjs from 'dayjs'
import { computed, ref } from 'vue'

import { currencyFormater } from '@/Helpers/formater'

import AppButton from '@/Components/AppButton.vue'
import AppPagination from '@/Components/AppPagination.vue'
import AppTable from '@/Components/AppTable.vue'
import TableTh from '@/Components/Table/TableTh.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

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
</script>
<template>
    <Head>
        <title>Транзакции</title>
    </Head>

    <AuthenticatedLayout>
        <template #header>Транзакции</template>

        <div class="panel mb-6">
            <div class="flex gap-1.5">
                <AppButton
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
                </AppButton>
                <AppButton
                    theme="normal"
                    :class="{ btn_selected: activeSection == 'balance' }"
                    @click="setSection('balance')"
                >
                    Баланс
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
            <AppTable v-else>
                <template #head>
                    <tr>
                        <TableTh>ID</TableTh>
                        <TableTh>Сумма</TableTh>
                        <TableTh>Назначение</TableTh>
                        <TableTh>Дата</TableTh>
                        <TableTh>Кошелек</TableTh>
                    </tr>
                </template>

                <tr v-for="transaction in transactions" :key="transaction" class="main-table__tr">
                    <td class="text-center">{{ transaction.id }}</td>
                    <td class="text-center">
                        {{ currencyFormater.format(transaction.amount / 100) }}
                    </td>
                    <td class="text-center">
                        {{ targetOnlyText(transaction.target) }}
                    </td>

                    <td class="text-center">{{ transaction.created_ts }}</td>
                    <td class="text-center">
                        <span v-if="transaction.type == -1">Списание</span>
                        <span v-else>Пополнение</span>
                    </td>
                </tr>
            </AppTable>
        </div>

        <AppPagination :links="paginator" />
    </AuthenticatedLayout>
</template>