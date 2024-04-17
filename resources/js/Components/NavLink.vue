<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
    counter: {
        type: [String, Number],
        default: 0,
    },
})

// const classes = computed(() => (props.active ? 'menu__link_active' : ''))
</script>

<template>
    <Link :href="href" :class="{ menu__link_active: props.active }">
        <slot />

        <div v-if="counter > 0" class="menu__link-counter">
            {{ counter }}
        </div>
    </Link>
</template>
