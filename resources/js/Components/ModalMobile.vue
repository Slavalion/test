<script setup>
import { onMounted, onUnmounted, watch } from 'vue'
import AppButton from './AppButton.vue'

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    currentSection: {
        type: String,
        default: 'all',
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

const close = (nextSection) => {
    if (props.closeable) {
        emit('close', nextSection)
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
            <div class="mobile-modal" v-show="show">
                <div class="mobile-purchases-filters">
                    <AppButton
                        theme="normal"
                        :class="{ btn_selected: 'processing' == currentSection }"
                        @click="close('processing')"
                    >
                        Активные
                    </AppButton>
                    <AppButton
                        theme="normal"
                        :class="{ btn_selected: 'done' == currentSection }"
                        @click="close('done')"
                    >
                        Завершенные
                    </AppButton>
                    <AppButton
                        theme="normal"
                        :class="{ btn_selected: 'unavailable' == currentSection }"
                        @click="close('unavailable')"
                    >
                        Недоступные
                    </AppButton>
                    <AppButton
                        theme="normal"
                        :class="{ btn_selected: 'all' == currentSection }"
                        @click="close('all')"
                    >
                        Все
                    </AppButton>
                </div>
            </div>
        </transition>
    </teleport>
</template>
<style lang="scss" scoped></style>
