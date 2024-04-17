<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

import { useTelegramAuth } from '@/Composables/telegramAuth'

import GuestLayout from '@/Layouts/GuestLayout.vue'

import AppButton from '@/Components/AppButton.vue'
import CheckboxInput from '@/Components/Inputs/CheckboxInput.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
        default: '',
    },
})

let params = new URLSearchParams(document.location.search)
let refCode = params.get('ref')

if (refCode) {
    localStorage.setItem('refCode', refCode)
}

refCode = localStorage.getItem('refCode')

const telegram = ref()

useTelegramAuth(telegram, route('telegram.auth', { refCode: refCode ?? 0 }))

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    })
}

const regClick = () => {
    ym(96168318, 'reachGoal', 'registraciya')
}

// const widgetButtonClick = () => {
//     window.Telegram.Login.auth(
//         { bot_id: "5428078645", request_access: true },
//         (data) => {
//             console.log(data)
//         }
//     )
// }
</script>

<template>
    <Head>
        <title>Авторизация</title>
    </Head>

    <GuestLayout>
        <template #title> Авторизация </template>

        <template #caption> Введите E-mail и пароль </template>

        <template #default>
            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="space-y-6">
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

                <TextInput
                    v-model="form.password"
                    :has-error="form.errors.password != undefined"
                    :error-message="form.errors.password"
                    label="Пароль"
                    icon="eye"
                    size="lg"
                    type="password"
                    required
                    autocomplete="current-password"
                />

                <CheckboxInput name="remember" v-model:checked="form.remember">
                    Запомнить меня
                </CheckboxInput>
            </form>
        </template>

        <template #footer>
            <div class="flex gap-3">
                <Link :href="route('register')" @click="regClick">
                    <AppButton size="lg" theme="outline"> Регистрация </AppButton>
                </Link>

                <Link v-if="canResetPassword" :href="route('password.request')" class="ml-auto">
                    <AppButton size="lg" theme="normal"> Забыли пароль </AppButton>
                </Link>

                <AppButton @click="submit" size="lg" :disabled="form.processing"> Войти </AppButton>
            </div>

            <div class="pt-9 flex justify-center">
                <!-- <div class="telegram-login" @click="widgetButtonClick">
                    <span>Войти с помощью Telegram</span>
                </div> -->
                <div ref="telegram" id="tg-auth-widget" style=""></div>
            </div>
        </template>
    </GuestLayout>
</template>