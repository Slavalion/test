<script setup>
import { useToast } from 'vue-toastification'

import { useAxios } from '@/Composables/useAxios'

import AppButton from '@/Components/AppButton.vue'
import LabelText from '@/Components/LabelText.vue'
import dayjs from 'dayjs'

const props = defineProps({
    groups: {
        type: Object,
        required: true,
    },
    zone: {
        type: Object,
        required: true,
    },
})

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
        })
        .catch(() => {
            toast.error('Не удалось установить статус, попробуйте еще раз')
        })
}

console.log(props.groups)
const updateQr = (delivery) => {
    delivery.version = dayjs().format('YYYYMDhms')
}
</script>
<template>
    <div class="w-full mx-auto">
        <h1 class="text-4xl font-bold text-center py-8">{{ zone.name }}</h1>
        <div class="">
            <div v-for="(group, address) in groups" :key="group">
                <div class="text-xl p-2 bg-teal-900 rounded-lg text-white">
                    {{ address }} ({{ group.length }} шт.)
                </div>
                <div
                    v-for="delivery in groups[address]"
                    :key="delivery"
                    class="p-2 mb-4 min-h-screen flex flex-col items-center justify-center"
                >
                    <div class="text-lg">
                        {{ delivery.deliveryable?.product.name }}
                    </div>
                    <!-- <div class="text-xs">
                        #ID{{ delivery.deliveryable.id }} #TSKID{{ delivery.deliveryable.task_id }}
                    </div> -->

                    <div
                        v-if="delivery.deliveryable.delivery_phone != undefined"
                        class="text-center"
                    >
                        TEL {{ delivery.deliveryable.delivery_phone }}
                    </div>
                    <div v-else class="text-center">TEL {{ delivery.deliveryable.phone }}</div>

                    <div v-if="delivery.deliveryable.delivery_pin != undefined" class="text-center">
                        CODE {{ delivery.deliveryable.delivery_pin }}
                    </div>
                    <div v-else class="text-center">CODE {{ delivery.deliveryable.code }}</div>

                    <div
                        v-if="delivery.deliveryable.delivery_qrcode != undefined"
                        class="text-center"
                    >
                        <img
                            :src="
                                '/storage/' +
                                delivery.deliveryable.delivery_qrcode +
                                '?v=e' +
                                delivery.version
                            "
                            width="240"
                            height="240"
                            class="inline-block"
                        />
                    </div>
                    <div
                        v-else-if="delivery.deliveryable.qr_code_link != undefined"
                        class="text-center"
                    >
                        <img
                            :src="delivery.deliveryable.qr_code_link"
                            width="240"
                            height="240"
                            class="inline-block"
                        />
                        <div class="my-2">
                            <a :href="delivery.deliveryable.qr_code_link" target="_blank">
                                Открыть в новой вкладке
                            </a>
                        </div>
                    </div>
                    <div v-else class="text-center font-bold pt-3">QR Отсутствует</div>

                    <div
                        v-if="delivery.deliveryable.delivery_qrcode != undefined"
                        class="flex gap-4 mt-6"
                    >
                        <AppButton @click="updateQr(delivery)">Перезагрузить QR</AppButton>
                    </div>

                    <div v-if="delivery.deliveryable.to_decline">
                        <LabelText theme="warning">От этого товара нужно отказаться!</LabelText>
                    </div>
                    <div class="flex gap-4 mt-6">
                        <template v-if="delivery.picked_up_status == 0">
                            <AppButton
                                v-if="!delivery.deliveryable.to_decline"
                                @click="setStatus(delivery, 1)"
                            >
                                Забрал
                            </AppButton>
                            <AppButton v-else @click="setStatus(delivery, 1)">Отказался</AppButton>
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
    </div>
</template>
