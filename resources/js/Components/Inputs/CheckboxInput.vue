<script setup>
import { computed } from 'vue'
import device from 'vue3-device-detector'
import { useWindowSize } from '@vueuse/core'
const { width } = useWindowSize()
const emit = defineEmits(['update:checked'])

const props = defineProps({
    checked: {
        type: [Array, Boolean, null],
        required: true,
    },
    value: {
        type: Boolean,
        default: null,
    },
})

const proxyChecked = computed({
    get() {
        return props.checked
    },

    set(val) {
        emit('update:checked', val)
    },
})
</script>

<template>
    <label class="checkbox-input" v-if="device().isDesktop && width > 390">
        <input
            v-model="proxyChecked"
            :value="value"
            type="checkbox"
            class="checkbox-input__input"
        />
        <span class="checkbox-input__label">
            <slot></slot>
        </span>
    </label>
    <label class="checkbox-input checkbox-input-mobile" v-else>
        <input
            v-model="proxyChecked"
            :value="value"
            type="checkbox"
            class="checkbox-input__input"
        />
        <span class="checkbox-input__label mobileparagraph1">
            <slot></slot>
        </span>
    </label>
</template>
