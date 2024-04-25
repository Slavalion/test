<script setup>
import { computed, ref, watch } from 'vue'

import TextInput from './TextInput.vue'
import AppButton from '../AppButton.vue'

const props = defineProps({
    modelValue: {
        type: Array,
        required: true,
    },
    min: {
        type: Number,
        default: 0,
    },
    max: {
        type: Number,
        default: 99,
    },
    size: {
        type: String,
        default: 'sm',
    },
})

const emit = defineEmits(['update:modelValue'])

const val = ref(props.modelValue)

const computedValue = computed({
    get() {
        val.value = props.modelValue
        return props.modelValue.toString()
    },
    set(value) {
        emit('update:modelValue', Number(value))
    },
})

watch(val, (newVal) => {
    if (newVal > props.max) {
        newVal = props.max
    }

    if (newVal < props.min) {
        newVal = props.min
    }

    emit('update:modelValue', newVal)
})

function increase() {
    if (val.value >= props.min && val.value < props.max) {
        val.value++
    }
}

function decrease() {
    if (val.value > props.min && val.value <= props.max) {
        val.value--
    }
}
</script>

<template>
    <div class="flex gap-1 items-center">
        <div class="w-7">
            <AppButton fillwidth theme="normal" :size="size" @click="decrease"> - </AppButton>
        </div>

        <div class="w-9">
            <TextInput class="text-center" type="number" v-model="computedValue" :size="size" />
        </div>

        <div class="w-7">
            <AppButton fillwidth theme="normal" :size="size" @click="increase"> + </AppButton>
        </div>
    </div>
</template>
