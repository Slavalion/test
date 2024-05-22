<script setup>
import { Head } from '@inertiajs/vue3'

import LabelText from '@/Components/LabelText.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

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
                        <div class="">
                            {{ address.address }}
                        </div>
                    </div>
                    <div class="ml-auto">
                        <LabelText v-if="address.wb_pickpoint_exist" theme="success">
                            WB адрес найден
                        </LabelText>
                        <LabelText v-else theme="danger">WB адрес не найден</LabelText>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
