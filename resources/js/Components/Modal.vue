<script setup>
import { onMounted, onUnmounted, watch } from 'vue'
import AppButton from './AppButton.vue'

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    wrapperClass: {
        type: String,
        default: '',
    },
    bodyClass: {
        type: String,
        default: '',
    },
    hideFooter: {
        type: Boolean,
        default: false,
    },
    loading: {
        type: Boolean,
        default: false,
    },
    loadingText: {
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
            document.body.style.overflow = 'hidden'
        } else {
            document.body.style.overflow = null
        }
    }
)

const close = () => {
    if (props.closeable) {
        emit('close')
    }
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
    document.body.style.overflow = null
})
</script>

<template>
    <teleport to="body">
        <transition leave-active-class="duration-200">
            <div
                v-show="show"
                class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
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
                    enter-from-class="opacity-0 scale-50"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-50"
                >
                    <div
                        v-show="show"
                        class="modal__wrapper transform -translate-x-2/4 -translate-y-2/4 transition-all"
                        :class="[wrapperClass]"
                    >
                        <div v-show="loading" class="modal__loading-overlay flex flex-col gap-4">
                            <div class="lds-ring">
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                            <div v-if="loadingText">{{ loadingText }}</div>
                        </div>
                        <div class="modal__header">
                            <div class="modal__header-wrapper">
                                <div class="modal__header-title">
                                    <slot name="header" />
                                </div>

                                <div class="modal__header-close">
                                    <AppButton @click="close" icon="close" theme="normal" />
                                </div>
                            </div>

                            <div v-if="$slots.caption" class="modal__header-caption">
                                <slot name="caption"></slot>
                            </div>
                        </div>

                        <div class="modal__body" :class="[bodyClass]">
                            <slot></slot>
                        </div>

                        <div v-if="!hideFooter" class="modal__footer">
                            <div class="modal__footer-actions">
                                <slot name="footer-logo"></slot>
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
