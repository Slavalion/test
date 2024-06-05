<script setup>
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import device from 'vue3-device-detector'
import { useWindowSize } from '@vueuse/core'
import AppButton from '@/Components/AppButton.vue'
import DigitBlock from '@/Components/Dashboard/DigitBlock.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import AppIcon from '@/Components/AppIcon.vue'
import AccountsTable from '@/Components/Dashboard/AccountsTable.vue'
import StuckTable from '@/Components/Dashboard/StuckTable.vue'
import Chart from '@/Components/Dashboard/Chart.vue'
import Modal from '@/Components/ModalMobileTariffs.vue'
import {
    logisticSourceData,
    logisticSourceLegend,
    balanseSourceLegend,
    incomeSourceLegend,
    incomeSourceData,
} from '@/Data/chart'

defineProps({
    totals: {
        type: Object,
        required: true,
    },
    stuck: {
        type: Object,
        default: {
            purchases: 12,
            reviews: 4,
            reviewReactions: 5,
            reviewComplaints: 4,
            cartActions: 0,
        },
    },
    purchases: {
        type: Object,
        default: {
            returned: 12,
            edited: 34,
            deleted: 50,
        },
    },
    users: {
        type: Object,
        default: {
            total: 45,
            deleted: 5,
        },
    },
    accounts: {
        type: Object,
        default: {
            total: 50162,
            free: 27465,
            otleg: 10064,
            bouthed: 560,
            reviewed: 1590,
            qrgive: 7590,
            banned: 232,
            thief: 198,
            withoutcooced: 2,
            payed: 500,
            stoledCard: 463,
        },
    },
    missedPurchases: {
        type: Array,
        required: true,
    },
})

const isModalShowed = ref(false)
const sections = ['services', 'stuck', 'logistic', 'accounts']
const currentSection = ref('stuck')
const { width } = useWindowSize()

const setSection = (section) => {
    currentSection.value = section
}

const nextSection = (section) => {
    setSection(section)
    isModalShowed.value = false
}
</script>
<template>
    <Head>
        <title>Статистика</title>
    </Head>

    <AuthenticatedLayout>
        <template #header>Статистика</template>

        <div v-if="!(device().isDesktop && width > 390)" class="input-wrapper">
            <div @click="isModalShowed = !isModalShowed" class="mobile-section-input">
                <p>{{ $t(currentSection + 'Title') }}</p>
                <AppIcon icon="chevron-down" />
            </div>
        </div>

        <div class="panel mb-6" v-else>
            <div class="flex gap-1.5">
                <AppButton
                    v-for="section in sections"
                    theme="normal"
                    :class="{ btn_selected: section == currentSection }"
                    @click="setSection(section)"
                >
                    {{ $t(section + 'Title') }}
                </AppButton>
            </div>
        </div>

        <div class="space-y-4">
            <div
                class="grid grid-cols-5 gap-4 digit-block-desk"
                v-if="currentSection === 'services'"
            >
                <DigitBlock icon="blue-purchase" :digit="totals.purchases">выкупов</DigitBlock>
                <DigitBlock icon="blue-review" :digit="totals.reviews">отзывов</DigitBlock>
                <DigitBlock icon="blue-like" :digit="totals.reviewReactions">
                    реакций на отзывы
                </DigitBlock>
                <DigitBlock icon="blue-question" :digit="totals.reviewComplaints">
                    жалоб на отзывы
                </DigitBlock>
                <DigitBlock icon="blue-star" :digit="totals.cartActions">
                    добавлений в корзину
                </DigitBlock>
            </div>

            <div v-if="currentSection === 'services'">
                <Chart
                    title="Вы заработали"
                    :dataSource="incomeSourceData"
                    variant="income"
                    :legend="incomeSourceLegend"
                />
            </div>

            <div
                class="grid grid-cols-3 gap-4 digit-block-desk"
                v-if="currentSection === 'services'"
            >
                <DigitBlock icon="blue-back" :digit="purchases.returned"
                    >вернулось назад выкупов</DigitBlock
                >
                <DigitBlock icon="yellow-edit" :digit="purchases.edited"
                    >отредактировано выкупов</DigitBlock
                >
                <DigitBlock icon="red-deleted" :digit="purchases.deleted">
                    удалено выкупов
                </DigitBlock>
            </div>

            <div v-if="currentSection === 'services'">
                <Chart
                    title="Пополнений баланса"
                    :dataSource="logisticSourceData"
                    variant="balanse"
                    :legend="balanseSourceLegend"
                />
            </div>

            <div
                class="grid grid-cols-2 gap-4 digit-block-desk"
                v-if="currentSection === 'logistic'"
            >
                <DigitBlock icon="blue-qr" :digit="users.total">выдано QR-кодов</DigitBlock>
                <DigitBlock icon="blue-box" :digit="users.deleted">заборов из ПВЗ</DigitBlock>
            </div>

            <div v-if="currentSection === 'logistic'">
                <Chart
                    title="Логистика"
                    :dataSource="logisticSourceData"
                    variant="logistic"
                    :legend="logisticSourceLegend"
                />
            </div>

            <div class="grid grid-cols-5 gap-4 digit-block-desk" v-if="currentSection === 'stuck'">
                <DigitBlock icon="red-purchase" :digit="stuck.purchases">выкупов</DigitBlock>
                <DigitBlock icon="red-review" :digit="stuck.reviews">отзывов</DigitBlock>
                <DigitBlock icon="red-like" :digit="stuck.reviewReactions">
                    реакций на отзывы
                </DigitBlock>
                <DigitBlock icon="red-question" :digit="stuck.reviewComplaints">
                    жалоб на отзывы
                </DigitBlock>
                <DigitBlock icon="red-star" :digit="stuck.cartActions">
                    добавлений в корзину
                </DigitBlock>
            </div>

            <div
                class="grid grid-cols-2 gap-4 digit-block-desk"
                v-if="currentSection === 'services'"
            >
                <DigitBlock icon="blue-users" :digit="users.total"
                    >зарегистрировалось пользователей</DigitBlock
                >
                <DigitBlock icon="red-deleted" :digit="users.deleted"
                    >удалили свой аккаунт</DigitBlock
                >
            </div>

            <div
                class="grid grid-cols-3 gap-4 digit-block-desk"
                v-if="currentSection === 'accounts'"
            >
                <DigitBlock icon="blue-users" :digit="accounts.total">всего</DigitBlock>
                <DigitBlock icon="green-users" :digit="accounts.free">свободен</DigitBlock>
                <DigitBlock icon="red-users" :digit="accounts.otleg"> в отлеге </DigitBlock>
            </div>

            <AccountsTable v-if="currentSection === 'accounts'" :accounts="accounts" />

            <StuckTable v-if="currentSection === 'stuck'" />
        </div>
        <Modal
            :show="isModalShowed"
            :currentSection="currentSection"
            :sections="sections"
            @close="nextSection"
            @open="disableInput = true"
        />
    </AuthenticatedLayout>
</template>

<style lang="scss" scoped>
.digit-block-desk {
    height: 13.72vw;

    & .digitBlock {
        border-radius: 1.11vw;
        padding: 0;
    }
}
</style>
