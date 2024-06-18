<script setup>
import VueDatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'
import TextInput from './TextInput.vue'
import { computed } from 'vue'
import AppButton from '../AppButton.vue'

const props = defineProps({
    modelValue: {
        type: [Date, null, Object],
        required: true,
    },
    label: {
        type: String,
        default: '',
    },
    size: {
        type: String,
        default: 'sm',
    },
    hasError: {
        type: Boolean,
        default: false,
    },
    errorMessage: {
        type: String,
        default: '',
    },
    type: {
        type: String,
        default: 'datetime',
    },
})

defineEmits(['update:modelValue'])

const timeToString = computed(() => {
    return format(props.modelValue)
})

const leadingZero = (num) => `0${num}`.slice(-2)

const format = (date) => {
    if (!(date instanceof Date)) {
        if (props.type == 'time') {
            return `${date.hours}:${date.minutes}`
        }

        return ''
    }

    const formatDate = (date) =>
        [date.getDate(), date.getMonth() + 1, date.getFullYear()].map(leadingZero).join('.')

    const formatTime = (date) => [date.getHours(), date.getMinutes()].map(leadingZero).join(':')

    if (props.type == 'date') {
        return `${formatDate(date)}`
    }

    return `${formatDate(date)}, ${formatTime(date)}`
}
</script>
<template>
    <VueDatePicker
        :value="modelValue"
        :time-picker-inline="props.type == 'datetime'"
        :time-picker="props.type == 'time'"
        input-class-name="text-input"
        @update:model-value="$emit('update:modelValue', $event)"
        locale="ru"
        :format="format"
        required
        placeholder="Select Date"
        class="datepicker"
        :min-date="new Date()"
    >
        <template #dp-input>
            <TextInput
                :model-value="timeToString"
                :size="size"
                :label="label"
                :has-error="hasError"
                :error-message="errorMessage"
                icon="calendar"
                readonly
                class="cursor-pointer"
                placeholder="Опубликовать сейчас"
            />
        </template>
        <template #action-row="{ selectDate }">
            <div class="ml-auto flex items-center gap-2">
                <AppButton @click="selectDate">Выбрать</AppButton>
            </div>
        </template>
    </VueDatePicker>
</template>
