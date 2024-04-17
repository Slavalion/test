<script setup>
import { computed } from 'vue'

const props = defineProps({
    size: {
        type: String,
        default: '',
    },
    progress: {
        type: Number,
        default: 0,
    },
})

const sizeClasses = computed(() => {
    return 'progress_size-' + props.size
})
</script>
<template>
    <div class="progress" :class="sizeClasses">
        <div class="progress__fill" :style="{ width: progress + '%' }"></div>
    </div>
</template>
