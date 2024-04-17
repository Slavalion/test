<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

import GuestLayout from '@/Layouts/GuestLayout.vue'

import AppButton from '@/Components/AppButton.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import { computed } from 'vue'

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    ref_code: localStorage.getItem('refCode'),
})

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}

const isFormFill = computed(() => {
    return form.name && form.email && form.password && form.password_confirmation
})
</script>

<template>
    <Head>
        <title>Авторизация</title>
    </Head>

    <GuestLayout>
        <template #title> Регистрация </template>

        <template #caption>
            Регистрируясь, вы принимаете условия предоставления
            <a href="#" class="inline-link">сервиса</a>
        </template>

        <template #default>
            <form @submit.prevent="submit" class="space-y-6">
                <TextInput
                    v-model="form.name"
                    :has-error="form.errors.name != undefined"
                    :error-message="form.errors.name"
                    label="Как вас зовут?"
                    required
                    autofocus
                    autocomplete="name"
                />

                <TextInput
                    v-model="form.email"
                    :has-error="form.errors.email != undefined"
                    :error-message="form.errors.email"
                    label="E-mail"
                    type="email"
                    required
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
                <Link :href="route('login')">
                    <AppButton size="lg" theme="normal"> Отмена </AppButton>
                </Link>

                <AppButton @click="submit" size="lg" :disabled="form.processing || !isFormFill">
                    Зарегистироваться
                </AppButton>
            </div>
        </template>
    </GuestLayout>
</template>
