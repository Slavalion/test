<script setup>
import { computed } from 'vue'

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
    <label class="checkbox-input">
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
</template>
