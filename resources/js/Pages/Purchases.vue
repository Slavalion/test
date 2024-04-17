<script setup>
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'

import { useAxios } from '@/Composables/useAxios'

import { genders, statuses } from '@/Data/purchase'
import { purchaseSlide } from '@/modals'
import { WbHelperImage } from '@/wbHelper.js'

import AppButton from '@/Components/AppButton.vue'
import AppPagination from '@/Components/AppPagination.vue'
import EmptyState from '@/Components/EmptyState.vue'
import LabelText from '@/Components/LabelText.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

defineProps({
    section: {
        type: String,
        default: 'processing',
    },
    purchaseGroups: {
        type: Array,
        default: () => [],
    },
    paginator: {
        type: Array,
        required: true,
    },
})

const api = useAxios()

const currentSection = ref('processing')

const setSection = (section) => {
    currentSection.value = section

    router.reload({
        only: ['purchaseGroups', 'paginator'],
        data: {
            section: currentSection.value,
            page: 1,
        },
    })
}

const deletePurchase = (purchaseId) => {
    api.post(route('purchases.destroy'), { purchase_id: purchaseId })
        .then((resp) => {
            router.reload({
                only: ['purchaseGroups'],
                data: {
                    section: currentSection.value,
                },
            })
        })
        .catch((error) => {
            alert(error.response.data.message)
        })
}
</script>

<template>
    <Head title="Liker" />

    <AuthenticatedLayout>
        <template #header> Выкупы </template>

        <div class="panel mb-6">
            <div class="flex gap-1.5">
                <AppButton
                    theme="normal"
                    :class="{ btn_selected: 'processing' == currentSection }"
                    @click="setSection('processing')"
                >
                    Активные
                </AppButton>
                <AppButton
                    theme="normal"
                    :class="{ btn_selected: 'done' == currentSection }"
                    @click="setSection('done')"
                >
                    Завершенные
                </AppButton>
                <AppButton
                    theme="normal"
                    :class="{ btn_selected: 'unavailable' == currentSection }"
                    @click="setSection('unavailable')"
                >
                    Недоступные
                </AppButton>
                <AppButton
                    theme="normal"
                    :class="{ btn_selected: 'not_enough_w_balance' == currentSection }"
                    @click="setSection('not_enough_w_balance')"
                >
                    Кошелек исчерпан
                </AppButton>
                <AppButton
                    theme="normal"
                    :class="{ btn_selected: 'pickpoint_overloaded' == currentSection }"
                    @click="setSection('pickpoint_overloaded')"
                >
                    ПВЗ перегружен
                </AppButton>
                <AppButton
                    theme="normal"
                    :class="{ btn_selected: 'all' == currentSection }"
                    @click="setSection('all')"
                >
                    Все
                </AppButton>

                <a
                    class="ml-auto"
                    :href="'/purchase/download?status=' + currentSection"
                    target="_blank"
                    download
                >
                    <AppButton>Скачать XLS</AppButton>
                </a>
            </div>
        </div>

        <template v-if="purchaseGroups.length > 0">
            <div v-for="group in purchaseGroups" :key="group.id" class="panel panel_product mb-6">
                <div class="purchase-group">
                    <div class="purhcase-group__title">
                        #{{ group.id }} Выкуп от {{ group.created_ts }}
                    </div>
                    <div></div>
                    <LabelText>
                        Товаров:
                        {{ group.purchases.length }} шт.
                    </LabelText>
                    <LabelText>
                        Сумма выкупов:
                        {{ group.total_sum }} ₽
                    </LabelText>
                </div>

                <div class="products-header products-header_deliveries">
                    <div class="products-header__product">Товар</div>
                    <div class="products-header__code">Артикул</div>
                    <div class="products-header__quantity">Кол-во</div>
                    <div class="products-header__gender">Пол</div>
                    <div class="products-header__size">Размер</div>
                    <div class="products-header__keywords">Поисковой запрос</div>
                    <div class="products-header__address">
                        <span class="mr-1">Адрес ПВЗ</span>
                    </div>
                </div>
                <div class="product-list product-list_deliveries">
                    <div v-for="purchase in group.purchases" :key="purchase.id" class="product">
                        <div class="product__product">
                            <div class="product__image">
                                <a
                                    :href="
                                        'https://www.wildberries.ru/catalog/' +
                                        purchase.product?.remote_id +
                                        '/detail.aspx'
                                    "
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    <img
                                        :src="
                                            WbHelperImage.constructHostV2(
                                                purchase.product?.remote_id
                                            ) + '/images/tm/1.webp'
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
                                            purchase.product?.remote_id +
                                            '/detail.aspx'
                                        "
                                        target="_blank"
                                        rel="noopener noreferrer"
                                    >
                                        {{ purchase.task_id }} {{ purchase.product?.name }}
                                    </a>
                                </div>
                                <div class="product__info-price">
                                    <!-- {{ currencyFormater.format(purchase.product.price / 100) }} -->
                                    {{ purchase.purchase_ts }}
                                </div>
                            </div>
                        </div>
                        <div class="product__code">
                            {{ purchase.product?.remote_id }}
                        </div>
                        <div class="product__quantity">
                            {{ purchase.quantity }}
                        </div>
                        <div class="product__gender">
                            {{ genders[purchase.gender].name }}
                        </div>
                        <div class="product__size">
                            {{ purchase.size?.name ? purchase.size?.name : '---' }}
                        </div>
                        <div class="product__keywords">
                            <span class="font-bold" v-if="purchase.search_position > 0">
                                ({{ purchase.search_position }})
                            </span>
                            {{ purchase.keywords }}
                        </div>
                        <div class="product__address" :title="purchase.address">
                            {{ purchase.address }}
                        </div>
                        <div class="product__actions">
                            <LabelText :theme="statuses[purchase.status].theme">
                                {{ statuses[purchase.status].title }}
                            </LabelText>
                            <AppButton
                                v-if="purchase.status == 'processing'"
                                size="sm"
                                icon="delete"
                                theme="danger"
                                @click="deletePurchase(purchase.id)"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <AppPagination :links="paginator" />
        </template>

        <div v-if="purchaseGroups.length == 0" class="panel flex flex-col grow">
            <EmptyState class="grow">
                <template #title>Нет активных выкупов</template>
                <template #image>
                    <img
                        src="/images/empty-purchases.svg"
                        alt="Нет активных выкупов"
                        width="250"
                        height="200"
                    />
                </template>

                <p>Создайте выкуп, чтобы начать продвигать товары в рейтинге</p>

                <AppButton icon="plus-circle" @click="purchaseSlide.open()"
                    >Создать выкуп</AppButton
                >
            </EmptyState>
        </div>
    </AuthenticatedLayout>
</template>