<script setup>
import { computed } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

import GuestLayout from '@/Layouts/GuestLayout.vue'
import AppButton from '@/Components/AppButton.vue'

const props = defineProps({
    status: {
        type: String,
        default: '',
    },
})

const form = useForm({})

const submit = () => {
    form.post(route('verification.send'))
}

const verificationLinkSent = computed(() => props.status === 'verification-link-sent')
</script>

<template>
    <Head>
        <title>Email Verification</title>
    </Head>

    <GuestLayout>
        <div class="mb-4 text-sm text-gray-600">
            Thanks for signing up! Before getting started, could you verify your email address by
            clicking on the link we just emailed to you? If you didn't receive the email, we will
            gladly send you another.
        </div>

        <div class="mb-4 font-medium text-sm text-green-600" v-if="verificationLinkSent">
            A new verification link has been sent to the email address you provided during
            registration.
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 flex items-center justify-between">
                <AppButton :disabled="form.processing"> Resend Verification Email </AppButton>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Log Out
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
