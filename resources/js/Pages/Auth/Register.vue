<script setup>
import device from 'vue3-device-detector'
import { useWindowSize } from '@vueuse/core'
const { width } = useWindowSize()
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
            <form
                @submit.prevent="submit"
                :class="device().isDesktop && width > 390 ? 'space-y-6' : 'mobile-form'"
            >
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
            <div class="flex justify-end gap-3" v-if="device().isDesktop && width > 390">
                <Link :href="route('login')">
                    <AppButton size="lg" theme="normal"> Отмена </AppButton>
                </Link>

                <AppButton @click="submit" size="lg" :disabled="form.processing || !isFormFill">
                    Зарегистироваться
                </AppButton>
            </div>
            <div v-else class="auth-panel-mobile__footer-btns">
                <Link :href="route('login')">
                    <AppButton size="lg" theme="normal" class="mobileparagraph1">
                        Отмена
                    </AppButton>
                </Link>

                <AppButton
                    @click="submit"
                    size="lg"
                    :disabled="form.processing || !isFormFill"
                    class="mobileparagraph1"
                >
                    Зарегистироваться
                </AppButton>
            </div>
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

    & a {
        width: 100%;
        margin: 0;
    }
}

.mobile-form {
    display: flex;
    flex-direction: column;
    gap: 5.128vw;
}
</style>
