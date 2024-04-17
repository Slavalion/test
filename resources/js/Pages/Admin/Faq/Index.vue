<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import AppButton from '@/Components/AppButton.vue'
import CheckboxInput from '@/Components/Inputs/CheckboxInput.vue'

const props = defineProps({
    faqs: {
        type: Array,
        required: true,
    },
})
</script>

<template>
    <Head title="FAQ" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between w-full">
                <div>FAQ</div>
                <div>
                    <Link :href="route('admin.faqs.create')">
                        <AppButton icon="edit"> создать </AppButton>
                    </Link>
                </div>
            </div>
        </template>

        <div class="panel panel_p-lg">
            <div class="divide-y">
                <div
                    v-for="faq in faqs"
                    :key="faq.id"
                    class="flex items-center py-4 first:pt-0 last:pb-0 gap-6"
                >
                    <div>
                        <div class="leading-none pb-1">#{{ faq.id }} {{ faq.title }}</div>
                    </div>

                    <div class="ml-auto flex gap-2">
                        <Link :href="route('admin.faqs.edit', { faq: faq.id })">
                            <AppButton icon="edit" theme="outline"> Редактировать </AppButton>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
