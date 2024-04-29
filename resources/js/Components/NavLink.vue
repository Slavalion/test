<script setup>
import { Link } from '@inertiajs/vue3'
import AppIcon from '@/Components/AppIcon.vue'

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
    itemroute: {
        type: String,
        required: true,
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
        <div v-if="itemroute === 'wallets'" class="menu__link__alertOctagon">
            <AppIcon icon="alert-octagon" />
        </div>
        <div v-if="itemroute === 'purchase.list'" class="menu__link__alertTriangle">
            <AppIcon icon="alert-triangle" />
        </div>
    </Link>
</template>
