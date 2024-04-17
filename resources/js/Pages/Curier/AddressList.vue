<script setup>
defineProps({
    addresses: {
        type: Array,
        required: true,
    },
    zone: {
        type: Object,
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
        <h1 class="text-xl font-bold text-center py-8">{{ zone.name }}</h1>

        <div class="w-80 mx-auto flex flex-col space-y-2">
            <a
                v-for="address in addresses"
                :key="address"
                :href="'/generated/deliveries/' + zone.id + '-' + date + '/' + address.id"
                class="bg-blue-600 rounded-xl py-3 px-4 text-white"
            >
                {{ address.address }}
                <br />

                <span class="font-bold"> ({{ address.livecargo_deliveries_count }} шт.) </span>
            </a>
        </div>
    </div>
</template>
