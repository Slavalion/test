<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import { useWindowSize } from '@vueuse/core'
import { useTelegramAuth } from '@/Composables/telegramAuth'

import GuestLayout from '@/Layouts/GuestLayout.vue'
import AppButton from '@/Components/AppButton.vue'
import TelegramButton from '@/Components/TelegramButton.vue'
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

const { width } = useWindowSize()

let params = new URLSearchParams(document.location.search)
let refCode = params.get('ref')

if (refCode) {
    localStorage.setItem('refCode', refCode)
}

refCode = localStorage.getItem('refCode')

const telegram = ref()
const isPasswordHide = ref(true)

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

const widgetButtonClick = () => {
    window.Telegram.Login.auth({ bot_id: '5428078645', request_access: true }, (data) => {
        console.log(data)
    })
}
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

            <form @submit.prevent="submit" :class="width > 390 ? 'space-y-6' : 'mobile-form'">
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
                    :icon="isPasswordHide ? 'eye' : 'eyeoff'"
                    size="lg"
                    :type="isPasswordHide ? 'password' : 'text'"
                    required
                    autocomplete="current-password"
                    @clickIcon="isPasswordHide = !isPasswordHide"
                    class="passInput"
                />

                <CheckboxInput name="remember" v-model:checked="form.remember">
                    Запомнить меня
                </CheckboxInput>
            </form>
        </template>

        <template #footer>
            <div :class="width > 390 ? 'flex gap-3' : 'auth-panel-mobile__footer-btns'">
                <Link :href="route('register')" @click="regClick">
                    <AppButton
                        size="lg"
                        theme="outline"
                        :class="width > 390 ? '' : 'mobileparagraph1'"
                    >
                        Регистрация
                    </AppButton>
                </Link>

                <Link v-if="canResetPassword" :href="route('password.request')" class="ml-auto">
                    <AppButton
                        size="lg"
                        theme="normal"
                        :class="width > 390 ? '' : 'mobileparagraph1'"
                    >
                        Забыли пароль
                    </AppButton>
                </Link>

                <AppButton @click="submit" size="lg" :disabled="form.processing"> Войти </AppButton>
            </div>

            <TelegramButton @clickBtn="widgetButtonClick" />
        </template>
    </GuestLayout>
</template>

<style lang="scss" scoped>
.auth-panel-mobile__footer-btns {
    width: 87vw;
    display: flex;
    flex-direction: column-reverse;
    justify-content: space-between;
    gap: 2.051vw;

    & a button,
    & button {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 11.282vw;
        border-radius: 2.564vw;
    }
}
.mobile-form {
    display: flex;
    flex-direction: column;
    gap: 5.128vw;
}
</style>
