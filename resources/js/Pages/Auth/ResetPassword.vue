<script setup>
import { Head, useForm } from '@inertiajs/vue3'

import GuestLayout from '@/Layouts/GuestLayout.vue'

import TextInput from '@/Components/Inputs/TextInput.vue'
import AppButton from '@/Components/AppButton.vue'

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
})

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}
</script>

<template>
    <Head>
        <title>Новый пароль</title>
    </Head>

    <GuestLayout>
        <template #title>Новый пароль</template>

        <template #caption>Придумайте новый пароль</template>

        <template #default>
            <form @submit.prevent="submit" class="space-y-6">
                <TextInput
                    v-model="form.email"
                    :has-error="form.errors.email != undefined"
                    :error-message="form.errors.email"
                    label="E-mail"
                    type="email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <TextInput
                    v-model="form.password"
                    :has-error="form.errors.password != undefined"
                    :error-message="form.errors.password"
                    label="Пароль"
                    icon="eye"
                    type="password"
                    required
                    autocomplete="new-password"
                />

                <TextInput
                    v-model="form.password_confirmation"
                    :has-error="form.errors.password_confirmation != undefined"
                    :error-message="form.errors.password_confirmation"
                    label="Пароль еще раз"
                    icon="eye"
                    type="password"
                    required
                    autocomplete="new-password"
                />
            </form>
        </template>

        <template #footer>
            <div class="flex justify-end gap-3">
                <AppButton @click="submit" size="lg" :disabled="form.processing">
                    Сохранить
                </AppButton>
            </div>
        </template>
    </GuestLayout>
</template>
