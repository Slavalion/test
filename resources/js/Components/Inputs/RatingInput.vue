<script setup>
import { computed, watch } from 'vue'

const props = defineProps({
    modelValue: {
        type: [Number, null],
        required: true,
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

const values = [1, 2, 3, 4, 5]

const rating = computed(() => props.modelValue)

const emits = defineEmits(['update:modelValue'])

watch(rating, (newVal) => emits('update:modelValue', newVal))
</script>

<template>
    <div>
        <label v-if="label" class="text-input__label">{{ label }}</label>

        <div class="input-wrapper">
            <div class="rating-input">
                <svg
                    v-for="value in values"
                    :key="value"
                    :class="{ 'rating-input__star_active': rating >= value }"
                    @click="emits('update:modelValue', value)"
                    class="rating-input__star"
                    width="40"
                    height="39"
                    viewBox="0 0 40 39"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        id="Star 5"
                        d="M18.1534 1.43986C18.8365 -0.20273 21.1635 -0.202727 21.8466 1.43986L25.879 11.1347C26.167 11.8272 26.8182 12.3003 27.5658 12.3602L38.0322 13.1993C39.8055 13.3415 40.5245 15.5545 39.1734 16.7118L31.1992 23.5427C30.6296 24.0306 30.3808 24.7961 30.5549 25.5256L32.9911 35.7391C33.4039 37.4695 31.5214 38.8372 30.0032 37.9099L21.0425 32.4368C20.4025 32.0458 19.5975 32.0458 18.9575 32.4368L9.9968 37.9099C8.4786 38.8372 6.5961 37.4695 7.00887 35.7391L9.44515 25.5256C9.61916 24.7961 9.37042 24.0306 8.80084 23.5427L0.826553 16.7118C-0.52452 15.5545 0.194535 13.3415 1.96784 13.1993L12.4342 12.3602C13.1818 12.3003 13.833 11.8272 14.121 11.1347L18.1534 1.43986Z"
                        fill="#E7EAF0"
                    />
                </svg>
            </div>

            <div v-if="hasError" class="text-input__caption" style="bottom: -4px">
                {{ errorMessage }}
            </div>
        </div>
    </div>
</template>
