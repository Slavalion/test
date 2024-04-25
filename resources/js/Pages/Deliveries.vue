<script setup>
import { Head, router, usePage } from '@inertiajs/vue3'
import { onMounted, onUnmounted, reactive, ref, watch } from 'vue'

import { genders } from '@/Data/purchase'
import { currencyFormater } from '@/Helpers/formater.js'
import { updateDeliveryData } from '@/modals'
import { WbHelperImage } from '@/wbHelper.js'
import TextInput from '@/Components/Inputs/TextInput.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import AppButton from '@/Components/AppButton.vue'
import AppPagination from '@/Components/AppPagination.vue'
import EmptyState from '@/Components/EmptyState.vue'
import LabelText from '@/Components/LabelText.vue'
import { useAxios } from '@/Composables/useAxios'
import UpdateDeliveryModal from '@/Modals/UpdateDeliveryModal.vue'
import { useToast } from 'vue-toastification'

const props = defineProps({
    deliveries: {
        type: Array,
        default: () => [],
    },
    deliveryStatus: {
        type: String,
        default: '',
    },
    paginator: {
        type: Array,
        required: true,
    },
})

const page = usePage()
const searchPickUpPoint = ref('')
const state = reactive({ deliveries: props.deliveries })

watch(
    () => props.deliveries,
    (newVal) => {
        state.deliveries = newVal
    }
)

const deliveryStatuses = {
    processing: 'Оплачен',
    sorted: 'Отсортирован',
    pending: 'В работе',
    on_the_way: 'В пути',
    available_for_pick_up: 'Готов к выдаче',
    picked_up: 'Завершен',
    canceled: 'Отменен',
    // not_paid: 'Доставлен, но не оплачен',
}

const currentStatus = ref(props.deliveryStatus)

const setStatus = (status) => {
    currentStatus.value = status

    router.reload({
        only: ['deliveries', 'paginator'],
        data: {
            status: currentStatus.value,
            page: 1,
        },
    })
}

const selectedDelivery = ref(0)

const updateDelivery = (deliveryIndex) => {
    selectedDelivery.value = deliveryIndex
    updateDeliveryData.open()
}

const api = useAxios()
const toast = useToast()

const updateReceipt = (purchase) => {
    api.post(route('deliveries.update-data'), { purchase_id: purchase.id }).then(() => {
        purchase.is_updating_delivery = true
        toast.success('Счет обновляется')
    })
}

onMounted(() => {
    window.Echo.private('user.' + page.props.auth.user.id).listen('.dilivery.update', (e) => {
        const delvrIndx = state.deliveries.findIndex((el) => {
            return el.id == e.id
        })

        if (e.delivery_status != currentStatus.value) {
            router.reload()
            return
        }

        if (!state.deliveries[delvrIndx]) {
            router.reload()
            return
        }

        state.deliveries[delvrIndx].is_updating_delivery = false
        state.deliveries[delvrIndx].delivery_status = e.delivery_status
        state.deliveries[delvrIndx].delivery_qrcode = e.delivery_qrcode
        state.deliveries[delvrIndx].delivery_pin = e.delivery_pin
    })
})

onUnmounted(() => {
    window.Echo.private('user.' + page.props.auth.user.id).stopListening('.dilivery.update')
})
</script>
<template>
    <Head>
        <title>Доставки</title>
    </Head>

    <AuthenticatedLayout>
        <template #header>Доставки</template>

        <div class="panel mb-6">
            <div class="flex gap-1.5">
                <AppButton
                    v-for="(name, statusCode) in deliveryStatuses"
                    :key="statusCode"
                    theme="normal"
                    @click="setStatus(statusCode)"
                    :class="{ btn_selected: statusCode == currentStatus }"
                >
                    {{ name }}
                </AppButton>

                <AppButton
                    theme="normal"
                    @click="setStatus('all')"
                    :class="{ btn_selected: 'all' == currentStatus }"
                >
                    Все
                </AppButton>

                <div class="ml-auto flex gap-3">
                    <TextInput v-model="searchPickUpPoint" size="md" placeholder="Адрес ПВЗ" />
                </div>
            </div>
        </div>

        <template v-if="deliveries.length > 0">
            <div class="panel panel_product">
                <div class="products-header products-header_deliveries">
                    <div class="products-header__product">Товар</div>
                    <div class="products-header__code">Артикул</div>
                    <div class="products-header__quantity">Кол-во</div>
                    <div class="products-header__gender">Пол</div>
                    <div class="products-header__size">Размер</div>
                    <div class="products-header__keywords">Поисковой запрос</div>
                    <div class="products-header__address">
                        <span class="mr-1">Адрес</span>
                    </div>
                </div>
                <div class="product-list product-list_deliveries">
                    <div
                        v-for="(delivery, index) in state.deliveries"
                        :key="delivery.id"
                        class="product"
                    >
                        <div class="product__product">
                            <div class="product__image">
                                <a
                                    :href="
                                        'https://www.wildberries.ru/catalog/' +
                                        delivery.product.remote_id +
                                        '/detail.aspx'
                                    "
                                    target="_blank"
                                >
                                    <img
                                        :src="
                                            WbHelperImage.constructHostV2(
                                                delivery.product.remote_id
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
                                            delivery.product.remote_id +
                                            '/detail.aspx'
                                        "
                                        target="_blank"
                                    >
                                        {{ delivery.task_id }}
                                        {{ delivery.product.name }}
                                    </a>
                                </div>
                                <div class="product__info-price">
                                    {{ currencyFormater.format(delivery.product.price / 100) }}
                                    <template v-if="delivery.delivery_status_updated_ts">
                                        {{ delivery.delivery_status_updated_ts }}
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="product__code">
                            {{ delivery.product.remote_id }}
                        </div>
                        <div class="product__quantity">
                            {{ delivery.quantity }}
                        </div>
                        <div class="product__gender">
                            {{ genders[delivery.gender].name }}
                        </div>
                        <div class="product__size">
                            {{ delivery.size?.name ? delivery.size?.name : '---' }}
                        </div>
                        <div class="product__keywords">
                            {{ delivery.keywords }}
                        </div>
                        <div class="product__address">
                            {{ delivery.address }}
                        </div>
                        <div class="product__actions">
                            <AppButton
                                v-if="currentStatus == 'available_for_pick_up'"
                                size="sm"
                                icon="qr"
                                theme="outline"
                                @click="updateDelivery(index)"
                            />

                            <template v-if="currentStatus == 'picked_up'">
                                <AppButton
                                    v-if="!delivery.receipt"
                                    size="sm"
                                    icon="excel-file"
                                    theme="outline"
                                    :disabled="delivery.is_updating_delivery"
                                    @click="updateReceipt(delivery)"
                                />
                                <a
                                    v-else
                                    :href="delivery.receipt"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    <AppButton size="sm" icon="share" theme="outline" />
                                </a>
                            </template>

                            <LabelText
                                v-if="delivery.delivery_status != 'not_paid'"
                                theme="info"
                                class="whitespace-nowrap"
                            >
                                {{ deliveryStatuses[delivery.delivery_status] }}
                            </LabelText>
                            <LabelText v-else theme="danger" class="whitespace-nowrap">
                                Доставлен, но не оплачен
                            </LabelText>
                        </div>
                    </div>
                </div>
            </div>

            <AppPagination :links="paginator" />
        </template>

        <div v-else class="panel flex flex-col grow">
            <EmptyState class="grow">
                <template #title>Нет активных доставок</template>
            </EmptyState>
        </div>

        <UpdateDeliveryModal
            v-if="state.deliveries.length > 0 && currentStatus == 'available_for_pick_up'"
            v-model="state.deliveries[selectedDelivery]"
        />
    </AuthenticatedLayout>
</template>
