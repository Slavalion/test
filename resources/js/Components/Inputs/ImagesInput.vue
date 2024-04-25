<script setup>
import { computed, onMounted, ref } from 'vue'
import AppIcon from '../AppIcon.vue'

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
    maxQuantity: {
        type: Number,
        default: 8,
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

const files = ref([])
const filesError = ref(false)

const selectFile = (e) => {
    if (e.target.files.length > 8) {
        filesError.value = true
        e.target.value = null
        files.value = []
        return
    }

    filesError.value = false
    files.value = e.target.files
    console.log(files)
}

const createImageUrl = (file) => {
    return URL.createObjectURL(file)
}

const removeFile = (index) => {
    console.log(index)
}

const validationClass = computed(() => (props.hasError ? 'text-input_error' : ''))
</script>

<template>
    <div>
        <label v-if="label" class="text-input__label">{{ label }}</label>
        <div class="input-wrapper" :class="wrapperClass">
            <div class="image-input">
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

                <div
                    v-for="n in maxQuantity - files.length"
                    :key="'block' + n"
                    class="image-input__empty"
                >
                    <label :for="'ava' + n" class="image-input__empty">
                        <AppIcon icon="img" />
                    </label>
                    <input
                        class="avaInput"
                        :id="'ava' + n"
                        :name="'ava' + n"
                        type="file"
                        multiple
                        @change="selectFile"
                        accept="image/png, image/jpg, image/jpeg"
                    />
                </div>
            </div>
            <div v-if="hasError" class="text-input__caption">{{ errorMessage }}</div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.image-input {
    display: flex;
    flex-direction: row;
    gap: 10px;
    overflow: hidden;

    &__empty {
        width: 72px;
        min-width: 72px;
        height: 72px;
        border: 1px solid #bbc1cd;
        border-radius: 15px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;

        .avaInput {
            display: none;
        }
    }
}

.relative {
    min-width: 72px;
}

.relative span {
    display: none;
}

.relative:hover span {
    display: flex;
}
</style>
