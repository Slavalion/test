<script setup>
import { computed } from 'vue'
import AppIcon from './AppIcon.vue'

const props = defineProps({
    theme: {
        type: String,
        default: 'action',
    },
    size: {
        type: String,
        default: 'md',
    },
    fillwidth: {
        type: Boolean,
        default: false,
    },
    icon: {
        type: String,
        default: '',
    },
})

const sizes = {
    sm: 'btn_size-sm',
    lg: 'btn_size-lg',
}

const themes = {
    normal: 'btn_theme-normal',
    outline: 'btn_theme-outline',
    danger: 'btn_theme-danger',
    oldanger: 'btn_theme-oldanger',
}

const fillwidthClass = computed(() => {
    return props.fillwidth ? 'btn_fillwidth' : ''
})

const themeClass = computed(() => {
    return themes[props.theme]
})

const sizeClass = computed(() => {
    return sizes[props.size]
})
</script>
<template>
    <button class="btn" :class="[fillwidthClass, sizeClass, themeClass]">
        <AppIcon v-if="icon" :icon="icon" />
        <span v-if="$slots.default">
            <slot></slot>
        </span>
    </button>
</template>
