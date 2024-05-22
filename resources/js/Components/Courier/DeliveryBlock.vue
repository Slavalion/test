<script setup>
import { useAxios } from '@/Composables/useAxios'
import { useToast } from 'vue-toastification'

import dayjs from 'dayjs'
import AppButton from '../AppButton.vue'
import LabelText from '../LabelText.vue'

const props = defineProps({
    deliveryData: {
        type: Object,
        required: true,
    },
    deliveryPhone: {
        type: [Number, String],
        required: true,
    },
})

const emit = defineEmits(['status-updated'])

const toast = useToast()
const api = useAxios()

const setStatus = (delivery, status) => {
    api.post(route('courier.deliveries.set-status'), {
        delivery_id: delivery.id,
        picked_up_status: status,
    })
        .then(() => {
            delivery.picked_up_status = status
            toast.success('Статус изменен')
            emit('status-updated', {
                phone: props.deliveryPhone,
                deliveryId: delivery.id,
                status: status,
            })
        })
        .catch(() => {
            toast.error('Не удалось установить статус, попробуйте еще раз')
        })
}

const updateQr = (delivery) => {
    delivery.version = dayjs().format('YYYYMDhms')
}
</script>
<template>
    <div class="p-2 mb-4 min-h-screen flex flex-col items-center justify-center">
        <!-- <div class="font-bold">
                        По телефону {{ phone }} доступно товаров: {{ deliveryData.length }}шт.
                    </div> -->

        <div v-if="deliveryData[0].deliveryable.delivery_phone != undefined" class="text-center">
            TEL {{ deliveryData[0].deliveryable.delivery_phone }}
        </div>
        <div v-else class="text-center">TEL {{ deliveryData[0].deliveryable.phone }}</div>

        <div v-if="deliveryData[0].deliveryable.delivery_pin != undefined" class="text-center">
            CODE {{ deliveryData[0].deliveryable.delivery_pin }}
        </div>
        <div v-else class="text-center">CODE {{ deliveryData[0].deliveryable.code }}</div>

        <div v-if="deliveryData[0].deliveryable.delivery_qrcode != undefined" class="text-center">
            <img
                :src="
                    '/storage/' +
                    deliveryData[0].deliveryable.delivery_qrcode +
                    '?v=e' +
                    deliveryData[0].version
                "
                width="240"
                height="240"
                class="inline-block"
            />
        </div>

        <div v-else-if="deliveryData[0].deliveryable.qr_code_link != undefined" class="text-center">
            <img
                :src="deliveryData[0].deliveryable.qr_code_link"
                width="240"
                height="240"
                class="inline-block"
            />
            <div class="mt-2 mb-6">
                <a :href="deliveryData[0].deliveryable.qr_code_link" target="_blank">
                    Открыть в новой вкладке
                </a>
            </div>
        </div>

        <div v-else class="text-center font-bold pt-3">QR Отсутствует</div>

        <div
            v-if="deliveryData[0].deliveryable.delivery_qrcode != undefined"
            class="flex gap-4 mt-6"
        >
            <AppButton @click="updateQr(deliveryData[0])">Перезагрузить QR</AppButton>
        </div>

        <div class="border-2 divide-y-2 rounded-lg">
            <div
                v-for="delivery in deliveryData"
                :key="delivery.id"
                class="p-2 flex flex-col items-center justify-center"
            >
                <div
                    class="ml-auto text-white rounded-xl px-2 text-xs"
                    :class="{
                        'bg-blue-500': delivery.type == 'mpbtop',
                        'bg-orange-300': delivery.type == 'outside',
                    }"
                >
                    {{ delivery.type }}
                </div>
                <div class="text-lg">
                    {{ delivery.deliveryable?.product?.name }}
                    <!-- <b v-if="delivery.type == 'mpbtop'">
                                    ({{ delivery.deliveryable.quantity }})
                                </b> -->
                </div>
                <!-- <div class="text-xs">
                                #ID{{ delivery.deliveryable.id }} #TSKID{{ delivery.deliveryable.task_id }}
                                </div> -->

                <div v-if="delivery.deliveryable.to_decline">
                    <LabelText theme="warning"> От этого товара нужно отказаться! </LabelText>
                </div>
                <div class="flex gap-4 mt-6">
                    <template v-if="delivery.picked_up_status == 0">
                        <AppButton
                            v-if="!delivery.deliveryable.to_decline"
                            @click="setStatus(delivery, 1)"
                        >
                            Забрал
                        </AppButton>
                        <AppButton v-else @click="setStatus(delivery, 1)"> Отказался </AppButton>
                        <AppButton @click="setStatus(delivery, -1)" theme="oldanger">
                            Отсутствует
                        </AppButton>
                    </template>

                    <LabelText v-else-if="delivery.picked_up_status == 1" theme="success">
                        Забран
                    </LabelText>
                    <LabelText v-else theme="danger">Отсутствует</LabelText>
                </div>
            </div>
        </div>
    </div>
</template>
