<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import device from 'vue3-device-detector'
import { useWindowSize } from '@vueuse/core'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import AppButton from '@/Components/AppButton.vue'

defineProps({
    status: {
        type: String,
        default: '',
    },
})

const { width } = useWindowSize()

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
            <div class="flex justify-end gap-3" v-if="device().isDesktop && width > 390">
                <Link :href="route('login')">
                    <AppButton size="lg" theme="normal"> Отмена </AppButton>
                </Link>

                <AppButton @click="submit" size="lg" :disabled="form.processing">
                    Сбросить пароль
                </AppButton>
            </div>
            <div class="auth-panel-mobile__footer-btns" v-else>
                <Link :href="route('login')">
                    <AppButton size="lg" theme="normal" class="mobileparagraph1">
                        Отмена
                    </AppButton>
                </Link>

                <AppButton
                    @click="submit"
                    size="lg"
                    :disabled="form.processing"
                    class="mobileparagraph1"
                >
                    Сбросить пароль
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
}
.mobile-form {
    display: flex;
    flex-direction: column;
    gap: 5.128vw;
}
</style>
