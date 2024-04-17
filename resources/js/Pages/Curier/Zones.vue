<script setup>
defineProps({
    orders: {
        type: Array,
        required: true,
    },
    date: {
        type: String,
        required: true,
    },
})
</script>
<template>
    <div class="w-screen mx-auto">
        <h1 class="text-4xl font-bold text-center py-8">Список на {{ date }}</h1>
        <div class="w-80 mx-auto flex flex-col space-y-2">
            <a
                v-for="order in orders"
                :key="order"
                :href="'/generated/deliveries/' + order.pickpoint_zone.id + '-' + date"
                class="bg-blue-600 rounded-xl py-3 px-4 text-white leading-none"
            >
                {{ order.pickpoint_zone.name }}

                ({{ order.livecargo_deliveries_count }} шт.)
            </a>
        </div>
    </div>
</template>
