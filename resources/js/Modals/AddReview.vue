<script setup>
import { router } from '@inertiajs/vue3'
import { nextTick, reactive, ref } from 'vue'
import { useToast } from 'vue-toastification'

import { useAxios } from '@/Composables/useAxios'

import { addReview } from '@/modals.js'
import ImagesInput from '@/Components/Inputs/ImagesInput.vue'
import AppButton from '@/Components/AppButton.vue'
import DatePicker from '@/Components/Inputs/DatePicker.vue'
import RadioGroupInput from '@/Components/Inputs/RadioGroupInput.vue'
import RatingInput from '@/Components/Inputs/RatingInput.vue'
import TextareaInput from '@/Components/Inputs/TextareaInput.vue'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
})

const files2 = ref([])

const toast = useToast()
const api = useAxios()
const { loading } = api

const reviewInput = ref(null)

const open = () => {
    nextTick(() => reviewInput.value.focus())
}

const review = reactive({
    gender: null,
    publish_at: null,
    review: '',
    rating: 0,
    errors: {},
})

const validate = () => {
    let valid = true
    review.errors = {}

    if (!review.gender) {
        review.errors.gender = 'Выберите пол'
        valid = false
    }

    if (!review.publish_at) {
        review.errors.publish_at = 'Укажите дату публикации'
        valid = false
    }

    if (!review.rating) {
        review.errors.rating = 'Укажите рейтинг'
        valid = false
    }

    return valid
}

const files = ref([])
const filesError = ref(false)

const selectFile = (e) => {
    if (e.target.files.length > 3) {
        filesError.value = true
        e.target.value = null
        files.value = []
        return
    }

    filesError.value = false
    files.value = e.target.files
}

const removeFile = (index) => {
    // files.value = e.target.files
}

const createImageUrl = (file) => {
    return URL.createObjectURL(file)
}

const createReview = function () {
    if (!validate()) {
        return
    }

    let data = new FormData()

    for (var key in review) {
        if (key == 'publish_at') {
            data.append(key, review[key].toISOString())
            continue
        }

        data.append(key, review[key])
    }

    for (let i = 0; i < files.value.length; i++) {
        data.append('photos[' + i + ']', files.value[i])
    }

    data.append('product_id', props.modelValue)

    api.post(route('reviews.create'), data)
        .then(() => {
            router.reload()
            review.gender = null
            review.publish_at = null
            review.review = ''
            review.rating = 0
            review.errors = {}
            addReview.close()
            toast.success('Отзыв успешно добавлен!')
        })
        .catch((error) => alert(JSON.stringify(error.response.data.errors)))
}
</script>
<template>
    <Modal :show="addReview.show" @close="addReview.close()" @open="open">
        <template #header> Оставить отзыв </template>

        <div class="space-y-6">
            <RadioGroupInput
                v-model="review.gender"
                label="Пол покупателя"
                :has-error="review.errors?.gender != undefined"
                :error-message="review.errors?.gender"
            />

            <DatePicker
                v-model="review.publish_at"
                size="md"
                label="Дата и время публикации"
                :has-error="review.errors?.publish_at != undefined"
                :error-message="review.errors?.publish_at"
            />

            <TextareaInput
                v-model="review.review"
                ref="reviewInput"
                rows="2"
                label="Отзыв о товаре"
                :has-error="review.errors?.review != undefined"
                :error-message="review.errors?.review"
            />

            <RatingInput
                v-model="review.rating"
                label="Рейтинг"
                :has-error="review.errors?.rating != undefined"
                :error-message="review.errors?.rating"
            />

            <!-- <label class="text-input__label">Фото для отзыва</label>
            <div class="input-wrapper">
                <input
                    type="file"
                    multiple
                    @change="selectFile"
                    accept="image/png, image/jpg, image/jpeg"
                />
                <div v-if="filesError" class="text-input__caption" style="bottom: -4px">
                    Можно выбрать максимум 3 файла
                </div>
            </div>
            <div class="pt-4 flex space-x-2">
                <div v-for="(file, idx) in files" :key="file" class="relative">
                    <img
                        :src="createImageUrl(file)"
                        alt=""
                        class="w-16 h-16 rounded-md object-cover"
                    />
                    <span
                            class="block cursor-pointer -right-1 -top-1 absolute bg-white rounded-full"
                            @click="removeFile(idx)"
                        >
                            <AppIcon icon="delete" />
                        </span>
                </div>
            </div>--->
            <ImagesInput label="Фото для отзыва" v-model="files2" />
        </div>

        <template #actions>
            <AppButton size="lg" @click="createReview" :disabled="loading">Отправить</AppButton>
        </template>
    </Modal>
</template>
