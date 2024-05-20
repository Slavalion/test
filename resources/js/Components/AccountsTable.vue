<script setup>
defineProps({
    accounts: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <div class="panel w-full">
        <div v-for="(value, name, index) in accounts" :key="index">
            <p>{{ name }} ___{{ value }}</p>
        </div>
    </div>
</template>
