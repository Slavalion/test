<script setup>
const props = defineProps({
    padding: {
        type: String,
        default: '',
    },
})

const paddingClass = props.padding != '' ? `panel_p-${props.padding}` : ''
</script>
<template>
    <div class="panel" :class="[paddingClass]">
        <slot></slot>
    </div>
</template>
