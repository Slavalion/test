<script setup>
import { Head, Link } from '@inertiajs/vue3'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import AppButton from '@/Components/AppButton.vue'

defineProps({
    zone: {
        type: Object,
        required: true,
    },
    addresses: {
        type: Array,
        required: true,
    },
})
</script>
<template>
    <Head>
        <title>Адреса забора</title>
    </Head>

    <AuthenticatedLayout>
        <template #header>{{ zone.name }}</template>

        <div class="panel panel_p-lg">
            <div class="divide-y">
                <div
                    v-for="address in addresses"
                    :key="address.id"
                    class="flex items-center py-4 first:pt-0 last:pb-0 gap-6"
                >
                    <div class="leading-none flex pb-1">
                        <div class="w-12">#{{ address.id }}</div>
                        <div class="w-8">
                            {{ address.sort }}
                        </div>
                        <div>
                            {{ address.address }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
