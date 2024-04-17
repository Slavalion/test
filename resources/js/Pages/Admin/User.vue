<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { useToast } from 'vue-toastification'

import { useAxios } from '@/Composables/useAxios'
import { currencyFormater } from '@/Helpers/formater'

import AppButton from '@/Components/AppButton.vue'
import AppIcon from '@/Components/AppIcon.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PersonalPrices from '@/Pages/Admin/User/PersonalPrices.vue'

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    prices: {
        type: Array,
        required: true,
    },
    stat: {
        type: Object,
        required: true,
    },
})

const toast = useToast()
const api = useAxios()

const { loading } = api

const amount = ref('0')

const topUpBalance = () => {
    api.post(route('admin.users.top-up-balance', { user: props.user.id }), {
        amount: amount.value,
    }).then(() => {
        amount.value = 0
        router.reload()
        toast.success('Баланс успешно пополнен')
    })
}
</script>
<template>
    <Head title="" />

    <AuthenticatedLayout>
        <template #header>
            <div class="font-normal text-sm">
                <Link :href="route('admin.users')" class="flex gap-2 items-center">
                    <AppIcon icon="arrow-up" class="-rotate-90 h-4 w-4" />
                    <span>Назад</span>
                </Link>
            </div>
        </template>

        <div class="space-y-6">
            <div class="grid grid-cols-2 gap-x-6 gap-y-6">
                <div class="panel panel_p-lg">
                    <div>
                        <div class="flex gap-1 text-gray-700">
                            <div>Имя:</div>
                            <div>{{ user.name }}</div>
                        </div>
                        <div class="flex gap-1 text-gray-700">
                            <div>Email|Telegram:</div>
                            <div>{{ user.email }}</div>
                        </div>
                        <div class="flex gap-1 text-gray-700">
                            <div>Баланс:</div>
                            <div>{{ currencyFormater.format(user.balance / 100) }}</div>
                        </div>
                    </div>
                </div>

                <div class="panel panel_p-lg">
                    <form @submit.prevent="topUpBalance" class="flex items-end gap-2">
                        <div class="grow">
                            <TextInput v-model="amount" size="md" label="Пополнение баланса" />
                        </div>
                        <div>
                            <AppButton :disabled="loading" size="md">Пополнить</AppButton>
                        </div>
                    </form>
                </div>

                <div class="panel panel_p-lg">
                    <div class="text-lg font-bold mb-2">Стата общая (бета)</div>

                    <div class="divide-y">
                        <div class="flex justify-between py-2">
                            <div>Выкупов:</div>
                            <div>{{ stat.purchases.items }} шт</div>
                        </div>
                        <div class="flex justify-between pt-2">
                            <div>Отзывов:</div>
                            <div>{{ stat.reviews.items }} шт</div>
                        </div>
                    </div>
                </div>

                <div class="panel panel_p-lg">
                    <div class="text-lg font-bold mb-2">Стата транзакции (бета)</div>

                    <div class="divide-y">
                        <div class="flex justify-between py-2">
                            <div>Возвратов:</div>
                            <div>
                                {{ stat.return_purchases.items }} шт /
                                {{ currencyFormater.format(stat.return_purchases.total) }}
                            </div>
                        </div>
                        <div class="flex justify-between py-2">
                            <div>Групп Выкупов:</div>
                            <div>
                                {{ stat.purchase_groups.items }} шт /
                                {{ currencyFormater.format(stat.purchase_groups.total) }}
                            </div>
                        </div>
                        <div class="flex justify-between pt-2">
                            <div>Заборы:</div>
                            <div>
                                {{ stat.pick_ups.items }} шт /
                                {{ currencyFormater.format(stat.pick_ups.total) }}
                            </div>
                        </div>
                    </div>
                </div>

                <PersonalPrices :prices="prices" :user="user" />
            </div>

            <!-- <div class="panel mb-6">
                <div class="flex gap-1.5">
                    <AppButton theme="normal"> Транзакции </AppButton>
                    <AppButton theme="normal"> Выкупы </AppButton>
                    <AppButton theme="normal"> Опубликованные </AppButton>
                </div>
            </div> -->
        </div>
    </AuthenticatedLayout>
</template>
