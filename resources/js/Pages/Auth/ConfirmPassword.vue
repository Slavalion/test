<script setup>
import { Head, useForm } from '@inertiajs/vue3'

import GuestLayout from '@/Layouts/GuestLayout.vue'

import TextInput from '@/Components/TextInput.vue'
import AppButton from '@/Components/AppButton.vue'

const form = useForm({
    password: '',
})

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    })
}
</script>

<template>
    <Head>
        <title>Confirm Password</title>
    </Head>

    <GuestLayout>
        <template #title>Confirm Password</template>

        <template #caption>
            This is a secure area of the application. Please confirm your password before
            continuing.
        </template>

        <template #default>
            <form @submit.prevent="submit">
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
