<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

defineProps({
    purchases: {
        type: Object,
        required: true,
    },
    reviews: {
        type: Object,
        required: true,
    },
})
</script>
<template>
    <AuthenticatedLayout>
        <div>Purchases: {{ purchases }}</div>
        <div>Reviews: {{ reviews }}</div>
    </AuthenticatedLayout>
</template>
