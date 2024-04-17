<script setup>
import { computed } from 'vue'
import AppIcon from './AppIcon.vue'

const props = defineProps({
    theme: {
        type: String,
        default: '',
    },
})

const themeClass = computed(() => {
    return props.theme == '' ? '' : 'label_theme-' + props.theme
})

const icons = {
    info: 'info',
    danger: 'alert-triangle',
    success: 'check-circle',
    warning: 'alert-triangle',
}
</script>
<template>
    <div class="label" :class="[themeClass]">
        <AppIcon v-if="icons[theme]" :icon="icons[theme]" />
        <span>
            <slot></slot>
        </span>
    </div>
</template>
