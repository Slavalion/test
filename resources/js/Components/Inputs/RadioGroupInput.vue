<script setup>
import { watch, ref } from 'vue'

const props = defineProps({
    modelValue: {
        type: [String, null],
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

watch(
    () => props.modelValue,
    (newVal) => (gender.value = newVal)
)

const gender = ref(props.modelValue)

defineEmits(['update:modelValue'])
</script>

<template>
    <div>
        <label v-if="label" class="text-input__label">{{ label }}</label>
        <div class="input-wrapper">
            <div class="flex gap-2">
                <div class="radio-group">
                    <input
                        id="genderMale"
                        type="radio"
                        name="gender"
                        value="1"
                        v-model="gender"
                        @change="$emit('update:modelValue', $event.target.value)"
                        class=""
                    />
                    <label for="genderMale">Мужской</label>
                </div>

                <div class="radio-group">
                    <input
                        id="genderFemale"
                        type="radio"
                        name="gender"
                        value="2"
                        v-model="gender"
                        @change="$emit('update:modelValue', $event.target.value)"
                        class=""
                    />
                    <label for="genderFemale">Женский</label>
                </div>
            </div>

            <div v-if="hasError" class="text-input__caption" style="bottom: -4px">
                {{ errorMessage }}
            </div>
        </div>
    </div>
</template>
