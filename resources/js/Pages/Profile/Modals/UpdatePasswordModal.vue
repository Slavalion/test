<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

import { profilePassword } from '@/modals'

import Modal from '@/Components/Modal.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import AppButton from '@/Components/AppButton.vue'

const passwordInput = ref(null)
const modalLoading = ref(false)

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
})

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
            profilePassword.close()
        },
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation')
                passwordInput.value.focus()
            }
        },
    })
}
</script>

<template>
    <Modal
        :show="profilePassword.show"
        @close="profilePassword.close()"
        :loading="modalLoading"
        wrapper-class="modal__wrapper_w-480"
    >
        <template #header> Поменять пароль </template>

        <form @submit.prevent="updatePassword" class="space-y-6">
            <TextInput
                v-model="form.password"
                type="password"
                ref="passwordInput"
                label="Пароль"
                size="lg"
                autocomplete="new-password"
                icon="eye"
                :has-error="form.errors.password != undefined"
                :error-message="form.errors.password"
            />

            <TextInput
                v-model="form.password_confirmation"
                type="password"
                label="Пароль еще раз"
                size="lg"
                autocomplete="new-password"
                icon="eye"
                :has-error="form.errors.password_confirmation != undefined"
                :error-message="form.errors.password_confirmation"
            />
        </form>

        <template #actions>
            <AppButton size="lg" @click="updatePassword">Сохранить</AppButton>
        </template>
    </Modal>
</template>
