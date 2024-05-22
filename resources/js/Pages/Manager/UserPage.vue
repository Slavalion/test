<script setup>
import { Head, Link } from '@inertiajs/vue3'

import { currencyFormater } from '@/Helpers/formater'

import AppIcon from '@/Components/AppIcon.vue'
import AppPanel from '@/Components/AppPanel.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { WbHelperImage } from '@/wbHelper'

defineProps({
    user: {
        type: Object,
        required: true,
    },
    stat: {
        type: Object,
        required: true,
    },
    products: {
        type: Array,
        required: true,
    },
})
</script>
<template>
    <Head title="" />

    <AuthenticatedLayout>
        <template #header>
            <div class="font-normal text-sm">
                <Link :href="route('manager.users')" class="flex gap-2 items-center">
                    <AppIcon icon="arrow-up" class="-rotate-90 h-4 w-4" />
                    <span>Назад</span>
                </Link>
            </div>
        </template>

        <div class="space-y-6">
            <div class="grid grid-cols-2 gap-x-6 gap-y-6">
                <div class="panel panel_p-lg col-span-2">
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

                <AppPanel
                    padding="lg"
                    class="col-span-2"
                    v-for="(product, idx) in products"
                    :key="idx"
                >
                    <div class="flex border-b pb-5">
                        <div class="product__image">
                            <a
                                :href="
                                    'https://www.wildberries.ru/catalog/' +
                                    product.product_id +
                                    '/detail.aspx'
                                "
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                <img
                                    :src="
                                        WbHelperImage.constructHostV2(product.product_id) +
                                        '/images/tm/1.webp'
                                    "
                                    alt=""
                                    width="30"
                                    height="40"
                                />
                            </a>
                        </div>
                        <div class="product__info">
                            <div class="product__info-title">
                                <a
                                    :href="
                                        'https://www.wildberries.ru/catalog/' +
                                        product.product_id +
                                        '/detail.aspx'
                                    "
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    {{ product.product?.name }}
                                </a>
                            </div>
                            <div class="product__info-price">
                                {{ product.product_id }}
                            </div>
                        </div>
                    </div>
                    <div class="divide-y">
                        <div class="flex justify-between items-center py-2">
                            <div>Всего выкупов:</div>
                            <div>{{ product.total_purchases }}шт.</div>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <div>Всего Забрано (Согласно отметкам курьеров):</div>
                            <div>{{ product.total_picked_up }}шт.</div>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <div>Суммарно выкуплено на (Отчет в разработке):</div>
                            <div>{{ currencyFormater.format(product.total_price) }}</div>
                        </div>
                    </div>
                </AppPanel>

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
            </div>
        </div>
    </AuthenticatedLayout>
</template>
