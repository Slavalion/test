<script setup>
import { computed, onMounted, ref } from 'vue'
import AppIcon from '../AppIcon.vue'
import device from 'vue3-device-detector'
import { useWindowSize } from '@vueuse/core'
const { width } = useWindowSize()

defineOptions({
    inheritAttrs: false,
})

const props = defineProps({
    modelValue: {
        type: [String, Number, null],
        required: true,
    },
    size: {
        type: String,
        default: 'md',
    },
    icon: {
        type: String,
        default: '',
    },
    wrapperClass: {
        type: String,
        default: '',
    },
    label: {
        type: String,
        default: '',
    },
    hasError: {
        type: Boolean,
        default: false,
    },
    errorMessage: {
        type: String,
        default: '',
    },
})

defineEmits(['update:modelValue'])

const input = ref(null)

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus()
    }
})

defineExpose({
    focus: () => input.value.focus(),
})

const validationClass = computed(() => (props.hasError ? 'text-input_error' : ''))

// const maxWidthClass = computed(() => {
//     return {
//         sm: "sm:max-w-sm",
//         md: "sm:max-w-md",
//         lg: "sm:max-w-lg",
//         xl: "sm:max-w-xl",
//         "2xl": "sm:max-w-2xl",
//     }[props.maxWidth]
// })
</script>

<template>
    <div v-if="device().isDesktop && width > 390">
        <label v-if="label" class="text-input__label">{{ label }}</label>
        <div class="input-wrapper" :class="wrapperClass">
            <input
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                ref="input"
                type="text"
                class="text-input"
                :class="['text-input_size-' + size, validationClass]"
                v-bind="$attrs"
            />
            <AppIcon v-if="icon" :icon="icon" />
            <div v-if="hasError" class="text-input__caption">{{ errorMessage }}</div>
        </div>
    </div>
    <div v-else>
        <label v-if="label" class="text-input__label mobileparagraph1">{{ label }}</label>
        <div class="input-wrapper" :class="wrapperClass">
            <input
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                ref="input"
                type="text"
                class="text-input"
                :class="['text-input_size-' + size + '-mobile', validationClass]"
                v-bind="$attrs"
            />
            <AppIcon v-if="icon" :icon="icon" />
            <div v-if="hasError" class="text-input__caption">{{ errorMessage }}</div>
        </div>
    </div>
</template>
