<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue"
import { Head } from "@inertiajs/vue3"
</script>
<template>
    <Head title="Страница не найдена" />

    <AuthenticatedLayout>
        <template #header>Страница не найдена</template>
    </AuthenticatedLayout>
</template>
