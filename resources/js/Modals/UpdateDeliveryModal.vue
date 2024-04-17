<script setup>
import { ref, watch } from 'vue'

import { useAxios } from '@/Composables/useAxios'

import { updateDeliveryData } from '../modals.js'

import AppButton from '@/Components/AppButton.vue'
import LabelText from '@/Components/LabelText.vue'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
    modelValue: {
        type: Object,
        default: () => {},
    },
})

const emits = defineEmits(['update:modelValue'])

const api = useAxios()

const deliveryStatuses = {
    processing: 'Оформлен',
    sorted: 'Отсортирован',
    pending: 'В работе',
    on_the_way: 'В пути',
    available_for_pick_up: 'Готов к выдаче',
}

const isLoading = ref(Boolean(props.modelValue.is_updating_delivery))

watch(
    () => props.modelValue,
    (newVal) => {
        isLoading.value = Boolean(newVal.is_updating_delivery)
    }
)

watch(
    () => props.modelValue.is_updating_delivery,
    (newVal) => {
        isLoading.value = Boolean(newVal)
    }
)

const updateData = function () {
    isLoading.value = true

    api.post(route('deliveries.update-data'), { purchase_id: props.modelValue.id })
        .then((resp) => {
            emits('update:modelValue', resp.data.delivery)
        })
        .catch(() => {
            isLoading.value = false
        })
}
</script>
<template>
    <Modal
        :show="updateDeliveryData.show"
        :loading="isLoading"
        @close="updateDeliveryData.close()"
        wrapper-class="modal__wrapper_w-480"
    >
        <template #header>QR-код для выдачи</template>

        <template v-if="modelValue" #caption>
            <div class="qrcode-modal__header">
                <div class="truncate">
                    {{ modelValue.product.name }}
                </div>
                <div>
                    <a
                        :href="
                            'https://www.wildberries.ru/catalog/' +
                            modelValue.product_id +
                            '/detail.aspx'
                        "
                        target="_blank"
                        class="inline-link"
                        >{{ modelValue.product_id }}
                    </a>
                </div>
                <div>{{ modelValue.address }}</div>
                <div v-if="modelValue.delivery_phone">Телефон: {{ modelValue.delivery_phone }}</div>
            </div>
            <div class="flex">
                <LabelText>{{ deliveryStatuses[modelValue.delivery_status] }}</LabelText>
            </div>
        </template>

        <div v-if="modelValue" class="qrcode-modal__content">
            <img :src="'/storage/' + modelValue.delivery_qrcode" alt="" />
            <span>{{ modelValue.delivery_pin.split('').join(' ') }}</span>
        </div>

        <template #actions>
            <AppButton size="lg" icon="refresh" @click="updateData">Обновить код</AppButton>
        </template>
    </Modal>
</template>
