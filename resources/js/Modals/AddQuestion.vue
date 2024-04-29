<script setup>
import { reactive } from 'vue'
import { addQuestion } from '../modals.js'
import TextareaInput from '@/Components/Inputs/TextareaInput.vue'
import RadioGroupInput from '@/Components/Inputs/RadioGroupInput.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import Modal from '@/Components/Modal.vue'
import DatePicker from '@/Components/Inputs/AddQuestionDatePicker.vue'

const question = reactive({
    gender: null,
    content: '',
    errors: {},
    date: null,
})

const closeModal = () => {
    addQuestion.close()
}
</script>
<template>
    <Modal
        :show="addQuestion.show"
        @close="closeModal"
        submitable
        clearable
        bodyClass="addQuestion"
    >
        <template #header>Вопросы</template>

        <RadioGroupInput
            v-model="question.gender"
            label="Пол покупателя"
            :has-error="question.errors?.gender != undefined"
            :error-message="question.errors?.gender"
            class="mb-6"
        />

        <DatePicker
            v-model="question.date"
            label="Дата и время публикации"
            size="lg"
            type="date"
            class="basis-1/4 mb-6"
        />

        <TextareaInput
            v-model="question.content"
            label="Вопрос"
            rows="6"
            placeholder="Опубликовать сейчас"
        />
    </Modal>
</template>
