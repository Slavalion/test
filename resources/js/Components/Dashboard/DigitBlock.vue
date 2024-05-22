<script setup>
import AppPanel from '../AppPanel.vue'

defineProps({
    digit: {
        type: Number,
        required: true,
    },
    icon: {
        type: String,
        required: true,
    },
})
</script>
<template>
    <AppPanel>
        <div class="text-center">
            <div>Icon</div>
            <div>{{ digit }}</div>
            <div>
                <slot></slot>
            </div>
        </div>
    </AppPanel>
</template>
