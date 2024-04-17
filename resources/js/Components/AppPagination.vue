<script setup>
import { Link } from '@inertiajs/vue3'
import AppButton from './AppButton.vue'

defineProps({
    links: {
        type: Array,
        required: true,
    },
})
</script>
<template>
    <div class="pt-6 flex gap-2 justify-center">
        <div v-for="link in links" :key="link">
            <Link v-if="link.url" :href="link.url">
                <AppButton v-html="link.label" :theme="link.active ? '' : 'normal'"></AppButton>
            </Link>

            <AppButton v-else v-html="link.label" :theme="link.active ? '' : 'normal'"></AppButton>
        </div>
    </div>
</template>
