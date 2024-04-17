<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

import { useAxios } from '@/Composables/useAxios'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import AppButton from '@/Components/AppButton.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import TextareaInput from '@/Components/Inputs/TextareaInput.vue'
import AppIcon from '@/Components/AppIcon.vue'

const api = useAxios()

const faq = ref({
    title: '',
    video: '',
    content: '',
})

const createFaq = () => {
    api.post(route('admin.faqs.store'), faq.value).then((resp) => {
        router.get(route('admin.faqs.edit', { faq: resp.data.id }))
    })
}
</script>

<template>
    <Head title="FAQ" />

    <AuthenticatedLayout>
        <template #header>
            <div class="font-normal text-sm">
                <Link :href="route('admin.faqs.index')" class="flex gap-2 items-center">
                    <AppIcon icon="arrow-up" class="-rotate-90 h-4 w-4" />
                    <span>Назад</span>
                </Link>
            </div>
        </template>

        <div class="panel panel_p-lg">
            <div class="space-y-4">
                <TextInput v-model="faq.title" label="Название" />
                <TextInput v-model="faq.video" label="Код для вставки видео" />
                <TextareaInput v-model="faq.content" label="Текстовое описание" rows="10" />
                <div class="flex justify-end">
                    <AppButton icon="edit" @click="createFaq"> Сохранить </AppButton>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
