<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import { useWindowSize } from '@vueuse/core'
import { useTelegramAuth } from '@/Composables/telegramAuth'
import AppIcon from '@/Components/AppIcon.vue'
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

const { width } = useWindowSize()

const baseWidth = ref(480)

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

            <div class="flex justify-center telegram-auth" v-if="width <= 390">
                <div class="telegram-btn" @click="widgetButtonClick">
                    <AppIcon icon="telegramm" />
                    <span>Войти с помощью Telegram</span>
                </div>
                <div ref="telegram" id="tg-auth-widget" style="display: none"></div>
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
            <div
                :class="
                    width > 390
                        ? 'flex gap-3 auth-panel__footer-btns'
                        : 'auth-panel-mobile__footer-btns'
                "
            >
                <Link :href="route('register')" @click="regClick">
                    <AppButton
                        size="lg"
                        theme="outline"
                        :class="width > 390 ? '' : 'mobileparagraph1'"
                    >
                        Регистрация
                    </AppButton>
                </Link>

                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    :class="width > 390 ? 'ml-auto' : ''"
                >
                    <AppButton
                        size="lg"
                        theme="normal"
                        :class="width > 390 ? '' : 'mobileparagraph1 '"
                    >
                        Забыли пароль
                    </AppButton>
                </Link>

                <AppButton @click="submit" size="lg" :disabled="form.processing"> Войти </AppButton>
            </div>

            <div class="pt-9 flex justify-center telegram-auth" v-if="width > 390">
                <div class="telegram-btn" @click="widgetButtonClick">
                    <AppIcon icon="telegramm" />
                    <span>Войти с помощью Telegram</span>
                </div>
                <div ref="telegram" id="tg-auth-widget" style="display: none"></div>
            </div>
        </template>
    </GuestLayout>
</template>

<style lang="scss" scoped>
@import './../../../scss/vars';

.telegram-auth {
    width: 100%;
}

.telegram-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: $functionalBlue10;
    color: $neuralNeural10;
    transition: 0.3s;
    cursor: pointer;

    height: 80px;
    width: 408px;
    gap: 24px;
    border-radius: 16px;
    padding: 24px 19px;

    &:hover {
        background-color: $functionalBlue20;
    }

    & span {
        font-weight: 400;
        letter-spacing: 0.18px;
        font-size: 17px;
        line-height: 28px;
    }

    & svg {
        height: 32px;
        width: 40px;
    }
}

.auth-panel__footer-btns {
    & a button,
    & button {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        padding: 7px 16px;
        gap: 6px;
        font-size: 15px;
        line-height: 22px;
    }
}

@media (min-width: 390px) and (max-width: 500px) {
    .auth-panel__footer-btns {
        & a button,
        & button {
            border-radius: 2vw;
            padding: 1.4vw 3.2vw;
            gap: 1.2vw;
            font-size: 3vw;
            line-height: 4.4vw;
        }
    }

    .telegram-btn {
        height: 16vw;
        width: 81.6vw;
        gap: 4.8vw;
        border-radius: 3.2vw;
        padding: 4.8vw 3.8vw;

        & span {
            letter-spacing: 0px;
            font-size: 3.4vw;
            line-height: 5.6vw;
        }

        & svg {
            height: 6.4vw;
            width: 8vw;
        }
    }
}

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

@media (max-width: 390px) {
    .telegram-btn {
        height: 20.51vw;
        width: 89.74vw;
        gap: 3vw;
        border-radius: 4.1vw;
        padding: 6.15vw 4.87vw;
        margin-bottom: 5.13vw;

        & span {
            letter-spacing: 0px;
            font-size: 4.3vw;
            line-height: 7.2vw;
        }

        & svg {
            height: 8.21vw;
            width: 10.25vw;
        }
    }
}
</style>
