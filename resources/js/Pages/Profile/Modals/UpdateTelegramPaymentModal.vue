<script setup>
import { router, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

import { useAxios } from '@/Composables/useAxios'
import { profileTelegramPayment } from '@/modals'

import AppButton from '@/Components/AppButton.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import LabelText from '@/Components/LabelText.vue'
import Modal from '@/Components/Modal.vue'
import { useToast } from 'vue-toastification'

const modalLoading = ref(false)

const api = useAxios()
const toast = useToast()
const user = usePage().props.auth.user

const paymentChatId = ref(user.preferences?.paymentChatId ? user.preferences.paymentChatId : '')

const updateChatId = () => {
    api.post(route('profile.tg-payment.update'), {
        paymentChatId: paymentChatId.value,
    }).then(() => {
        router.reload()
        profileTelegramPayment.close()
        toast.success('Платежные данные успешно сохранены')
    })
}
</script>

<template>
    <Modal
        :show="profileTelegramPayment.show"
        @close="profileTelegramPayment.close()"
        :loading="modalLoading"
        wrapper-class="modal__wrapper_w-480"
    >
        <template #header> Изменить данные </template>

        <form @submit.prevent="updateChatId" class="space-y-6">
            <div class="space-y-2">
                <LabelText theme="danger" class="mb-2">Важно</LabelText>
                <span class="text-sm">
                    Перед добавлением Chat ID необходимо активировать платежного бота. Перейти в
                    бота и нажать СТАРТ.
                    <br />
                    <a href="https://t.me/getmyid_bot" class="text-[#1665FF]" target="_blank">
                        Получить Chat ID
                    </a>
                    <br />
                    <a href="https://t.me/MPB_ORDER_bot" class="text-[#1665FF]" target="_blank">
                        Платежный бот
                    </a>
                </span>
            </div>

            <TextInput v-model="paymentChatId" label="Chat ID для оплаты" size="lg" />
        </form>

        <template #actions>
            <AppButton size="lg" @click="updateChatId">Сохранить</AppButton>
        </template>
    </Modal>
</template>
