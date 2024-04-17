<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

import { profileDelete } from '@/modals'

import TextInput from '@/Components/Inputs/TextInput.vue'

import Modal from '@/Components/Modal.vue'
import AppButton from '@/Components/AppButton.vue'

const passwordInput = ref(null)

const form = useForm({
    password: '',
})

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => profileDelete.close(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    })
}
</script>

<template>
    <Modal
        :show="profileDelete.show"
        @close="profileDelete.close()"
        wrapper-class="modal__wrapper_w-480"
    >
        <template #header> Удалить аккаунт? </template>

        <template #caption>
            Как только ваша учетная запись будет удалена, все данные будут удалены безвозвратно, в
            том числе и баланс. Пожалуйста, введите свой пароль, чтобы подтвердить, что вы хотите
            навсегда удалить свою учетную запись.
        </template>

        <TextInput
            ref="passwordInput"
            v-model="form.password"
            type="password"
            label="Пароль"
            icon="eye"
            size="lg"
            :has-error="form.errors.password != undefined"
            :error-message="form.errors.password"
            @keyup.enter="deleteUser"
        />

        <template #actions>
            <AppButton theme="danger" size="lg" class="btn_selected" @click="deleteUser">
                Удалить
            </AppButton>
        </template>
    </Modal>
</template>
