<script setup>
import { onMounted, onUnmounted, ref } from 'vue'
import TextInput from './TextInput.vue'

defineProps({
    modelValue: {
        type: Object,
        required: true,
    },
    align: {
        type: String,
        default: 'right',
    },
    items: {
        type: Array,
        default: () => [],
    },
    size: {
        type: String,
        default: 'sm',
    },
    contentClasses: {
        type: String,
        default: 'py-1 bg-white',
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

const emit = defineEmits(['update:modelValue'])

const closeOnEscape = (e) => {
    if (open.value && e.key === 'Escape') {
        open.value = false
    }
}

const setItem = function (item) {
    open.value = false
    emit('update:modelValue', item)
}

onMounted(() => document.addEventListener('keydown', closeOnEscape))
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape))

const open = ref(false)
</script>

<template>
    <div class="relative">
        <div @click="open = !open">
            <TextInput
                :model-value="modelValue.name"
                :size="size"
                icon="chevron-down"
                readonly
                class="text-input_select"
                :has-error="hasError"
                :error-message="errorMessage"
                :disabled="items.length == 0"
            />
        </div>

        <!-- Full Screen Dropdown Overlay -->
        <div v-show="open" class="fixed inset-0 z-40" @click="open = false"></div>

        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div v-show="open" class="list origin-top-left left-0" style="display: none">
                <div
                    v-for="item in items"
                    @click="setItem(item)"
                    :key="item.key"
                    class="list__item"
                >
                    {{ item.name }}
                </div>
            </div>
        </transition>
    </div>
</template>
