<script setup>
import AppIcon from './AppIcon.vue'

defineProps({
    icon: {
        type: String,
        default: '',
    },
})
</script>
<template>
    <div class="caption">
        <AppIcon v-if="icon != ''" :icon="icon" />
        <span>
            <slot></slot>
        </span>
    </div>
</template>
