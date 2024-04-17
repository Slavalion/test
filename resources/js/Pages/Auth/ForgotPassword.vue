<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

import GuestLayout from '@/Layouts/GuestLayout.vue'

import TextInput from '@/Components/Inputs/TextInput.vue'
import AppButton from '@/Components/AppButton.vue'

defineProps({
    status: {
        type: String,
        default: '',
    },
})

const form = useForm({
    email: '',
})

const submit = () => {
    form.post(route('password.email'))
}
</script>

<template>
    <Head>
        <title>Забыли пароль</title>
    </Head>

    <GuestLayout>
        <template #title>Забыли пароль</template>

        <template #caption>
            Введите электронной почты, и мы вышлем вам ссылку для сброса пароля
        </template>

        <template #default>
            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <TextInput
                    v-model="form.email"
                    :has-error="form.errors.email != undefined"
                    :error-message="form.errors.email"
                    label="E-mail"
                    type="email"
                    size="lg"
                    required
                    autofocus
                    autocomplete="username"
                />
            </form>
        </template>

        <template #footer>
            <div class="flex justify-end gap-3">
                <Link :href="route('login')">
                    <AppButton size="lg" theme="normal"> Отмена </AppButton>
                </Link>

                <AppButton @click="submit" size="lg" :disabled="form.processing">
                    Сбросить пароль
                </AppButton>
            </div>
        </template>
    </GuestLayout>
</template>
