<script setup>
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

import { currencyFormater } from '@/Helpers/formater'

import AppButton from '@/Components/AppButton.vue'
import AppTable from '@/Components/AppTable.vue'
import DigitBlock from '@/Components/Dashboard/DigitBlock.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import AppIcon from '@/Components/AppIcon.vue'
import AccountsTable from '@/Components/AccountsTable.vue'
import Modal from '@/Components/ModalMobileTariffs.vue'

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
    missedReviews: {
        type: Array,
        required: true,
    },
    recentTopUps: {
        type: Array,
        required: true,
    },
    tinkoffTotal: {
        type: Number,
        required: true,
    },
    manualTotal: {
        type: Number,
        required: true,
    },
})

const currentSection = ref('recentTopUpBalance')

const setSection = (section) => {
    currentSection.value = section
}
</script>
<template>
    <Head>
        <title>Статистика</title>
    </Head>

    <AuthenticatedLayout>
        <template #header>Статистика</template>

        <div class="space-y-4">
            <div class="grid grid-cols-5 gap-4">
                <DigitBlock icon="setting" :digit="totals.purchases">выкупов</DigitBlock>
                <DigitBlock icon="setting" :digit="totals.reviews">отзывов</DigitBlock>
                <DigitBlock icon="setting" :digit="totals.reviewReactions">
                    реакций на отзывы
                </DigitBlock>
                <DigitBlock icon="setting" :digit="totals.reviewComplaints">
                    жалоб на отзывы
                </DigitBlock>
                <DigitBlock icon="setting" :digit="totals.cartActions">
                    добавлений в корзину
                </DigitBlock>
            </div>

            <div class="panel mb-6">
                <div class="flex gap-1.5">
                    <AppButton
                        theme="normal"
                        :class="{ btn_selected: 'recentTopUpBalance' == currentSection }"
                        @click="setSection('recentTopUpBalance')"
                    >
                        Пополнения за 7 дней ({{ recentTopUps.length }})
                    </AppButton>
                    <AppButton
                        theme="normal"
                        :class="{ btn_selected: 'missedPurchases' == currentSection }"
                        @click="setSection('missedPurchases')"
                    >
                        Пропущенные выкупы ({{ missedPurchases.length }})
                    </AppButton>
                    <AppButton
                        theme="normal"
                        :class="{ btn_selected: 'missedReviews' == currentSection }"
                        @click="setSection('missedReviews')"
                    >
                        Пропущенные отзывы ({{ missedReviews.length }})
                    </AppButton>
                </div>
            </div>

            <div v-show="currentSection == 'recentTopUpBalance'" class="panel panel_p-lg">
                <template v-if="recentTopUps.length > 0">
                    <AppTable>
                        <tr
                            v-for="transaction in recentTopUps"
                            :key="transaction.id"
                            class="border-y"
                        >
                            <td class="py-2">#{{ transaction.id }}</td>
                            <td class="py-2">User ID {{ transaction.user_id }}</td>
                            <td class="py-2">
                                {{ currencyFormater.format(transaction.amount / 100) }}
                            </td>
                            <td class="py-2">
                                <template v-if="transaction.target == 'balance_manually'">
                                    Ручное пополнение
                                </template>
                                <template v-else-if="transaction.target == 'tinkoff'">
                                    Tinkoff
                                </template>
                            </td>
                            <td class="py-2">{{ transaction.created_ts }}</td>
                        </tr>
                    </AppTable>
                    <div class="text-right mt-4">
                        Всего через кассу: {{ currencyFormater.format(tinkoffTotal / 100) }}
                    </div>
                    <div class="text-right">
                        Всего Ручных: {{ currencyFormater.format(manualTotal / 100) }}
                    </div>
                </template>
                <div v-else class="text-center">За последние 7 дней нет пополнений</div>
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

            <div class="panel panel_p-lg" v-if="currentSection === 'stuck'">
                <div class="mb-4">Пропущенные выкупы</div>
                <div>
                    <pre v-for="purchase in missedPurchases" :key="purchase.id">{{ purchase }}</pre>
                </div>
            </div>
            <div v-show="currentSection == 'missedReviews'" class="panel panel_p-lg">
                <div>
                    <pre v-for="review in missedReviews" :key="review.id">{{ review }}</pre>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
