<script setup>
import { computed, onMounted, ref } from 'vue'

defineOptions({
    inheritAttrs: false,
})

const props = defineProps({
    modelValue: {
        type: [String, null],
        required: true,
    },
    size: {
        type: String,
        default: 'md',
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
</script>

<template>
    <div>
        <label v-if="label" class="text-input__label">{{ label }}</label>
        <div class="input-wrapper" :class="wrapperClass">
            <textarea
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                ref="input"
                type="text"
                class="text-input"
                :class="['text-input_size-' + size, validationClass]"
                v-bind="$attrs"
            ></textarea>
            <div v-if="hasError" class="text-input__caption">{{ errorMessage }}</div>
        </div>
    </div>
</template>
