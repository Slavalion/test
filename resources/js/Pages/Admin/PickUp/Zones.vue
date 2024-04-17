<script setup>
import { Head, Link } from '@inertiajs/vue3'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import AppButton from '@/Components/AppButton.vue'

defineProps({
    zones: {
        type: Array,
        required: true,
    },
})
</script>
<template>
    <Head>
        <title>Зоны забора</title>
    </Head>

    <AuthenticatedLayout>
        <template #header>Зоны забора</template>

        <div class="panel panel_p-lg">
            <div class="divide-y">
                <div
                    v-for="zone in zones"
                    :key="zone.id"
                    class="flex items-center py-4 first:pt-0 last:pb-0 gap-6"
                >
                    <div>
                        <div class="leading-none pb-1">#{{ zone.id }} {{ zone.name }}</div>
                    </div>

                    <div class="ml-auto flex gap-2">
                        <Link :href="route('admin.pickup-zones.addresses', { zone: zone.id })">
                            <AppButton theme="outline"> Открыть </AppButton>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
