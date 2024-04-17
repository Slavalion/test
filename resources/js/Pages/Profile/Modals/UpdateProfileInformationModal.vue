<script setup>
import { ref } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'

import { profileInformation } from '@/modals'

import Modal from '@/Components/Modal.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import AppButton from '@/Components/AppButton.vue'

defineProps({
    status: {
        type: String,
        default: '',
    },
})

const user = usePage().props.auth.user

const form = useForm({
    name: user.name,
    email: user.email,
})

const modalLoading = ref(false)

const submit = () => {
    form.patch(route('profile.update'), { onSuccess: () => profileInformation.close() })
}
</script>

<template>
    <Modal
        :show="profileInformation.show"
        @close="profileInformation.close()"
        :loading="modalLoading"
        wrapper-class="modal__wrapper_w-480"
    >
        <template #header> Изменить информацию </template>

        <form @submit.prevent="submit" class="space-y-6">
            <TextInput
                v-model="form.name"
                label="Как вас зовут?"
                size="lg"
                :has-error="form.errors.name != undefined"
                :error-message="form.errors.name"
            />

            <TextInput
                v-model="form.email"
                label="Email"
                size="lg"
                :has-error="form.errors.email != undefined"
                :error-message="form.errors.email"
            />
        </form>

        <template #actions>
            <AppButton size="lg" @click="submit">Сохранить</AppButton>
        </template>
    </Modal>
</template>
