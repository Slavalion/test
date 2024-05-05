<script setup>
import { onMounted, onUnmounted, watch } from 'vue'
import AppButton from './AppButton.vue'
import { ref } from 'vue'

import device from 'vue3-device-detector'
import { useWindowSize } from '@vueuse/core'

const { width } = useWindowSize()
const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    bodyClass: {
        type: String,
        default: '',
    },
    headerClass: {
        type: String,
        default: '',
    },
    wrapperClass: {
        type: String,
        default: '',
    },
})

const emit = defineEmits(['close', 'open'])

watch(
    () => props.show,
    () => {
        if (props.show) {
            open()
            // document.body.style.overflow = "hidden"
        } else {
            // document.body.style.overflow = null
        }
    }
)

const close = () => {
    emit('close')
}

const open = () => {
    emit('open')
}

const closeOnEscape = (e) => {
    if (e.key === 'Escape' && props.show) {
        close()
    }
}

onMounted(() => document.addEventListener('keydown', closeOnEscape))

onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape)
    // document.body.style.overflow = null
})
</script>

<template>
    <teleport to="body">
        <transition leave-active-class="duration-200">
            <div
                v-show="show"
                :class="
                    device().isDesktop && width > 390
                        ? 'fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-40'
                        : 'mobile-modalSlide'
                "
                scroll-region
            >
                <transition
                    enter-active-class="ease-out duration-200"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div
                        v-show="show"
                        class="fixed inset-0 transform transition-all"
                        @click="close"
                    >
                        <div class="absolute inset-0 bg-gray-500 opacity-75" />
                    </div>
                </transition>

                <transition
                    enter-active-class="ease-out duration-200"
                    enter-from-class="opacity-50 translate-x-full"
                    enter-to-class="opacity-100 translate-x-0"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100 translate-x-0"
                    leave-to-class="opacity-50 translate-x-full"
                >
                    <div
                        v-show="show"
                        class="modal__wrapper modal__wrapper_slide h-screen transform transition-all"
                        :class="wrapperClass"
                    >
                        <div class="modal__header" :class="headerClass">
                            <div class="modal__header-wrapper">
                                <div class="modal__header-title">
                                    <slot name="header" />
                                </div>

                                <div class="modal__header-close">
                                    <AppButton @click="close" icon="close" theme="normal" />
                                </div>
                            </div>

                            <div v-if="$slots.caption" class="modal__header-caption mb-3">
                                <slot name="caption"></slot>
                            </div>

                            <div
                                :class="
                                    device().isDesktop && width > 390
                                        ? 'flex gap-1.5'
                                        : 'mobile-modalSlide__input-block'
                                "
                            >
                                <slot name="search"></slot>
                            </div>
                        </div>

                        <div class="modal__body" :class="bodyClass">
                            <slot></slot>
                        </div>

                        <div class="modal__footer">
                            <div class="modal__footer-actions">
                                <slot name="left-actions"></slot>

                                <AppButton size="lg" theme="normal" @click="close">
                                    Отменить
                                </AppButton>
                                <slot name="actions"></slot>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
        </transition>
    </teleport>
</template>
