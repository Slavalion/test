<script setup>
import { ref, watch } from 'vue'
import { useWindowSize } from '@vueuse/core'
import AppIcon from './AppIcon.vue'

const emit = defineEmits(['clickBtn'])

const { width } = useWindowSize()
const baseWidth = ref(480)
const previousWidth = ref(480)
const el = ref(null)

watch(width, () => {
    if (
        Math.abs(Math.round(el.value.offsetWidth) - previousWidth.value) >
        Math.round(previousWidth.value / 100)
    ) {
        baseWidth.value = el.value.offsetWidth
    }
})
</script>

<template>
    <div class="pt-9 flex justify-center telegram-auth" ref="el">
        <div
            class="telegram-btn"
            @click="$emit('clickBtn')"
            :style="{
                height: (0.196 * baseWidth).toFixed(0) + 'px',
                width: baseWidth.toFixed(1) + 'px',
                gap: (0.0588 * baseWidth).toFixed(1) + 'px',
                'border-radius': (0.0392 * baseWidth).toFixed(1) + 'px',
                padding:
                    (0.0588 * baseWidth).toFixed(1) +
                    'px ' +
                    (0.0466 * baseWidth).toFixed(1) +
                    'px',
            }"
        >
            <AppIcon
                icon="telegramm"
                :style="{
                    height: (0.196 * baseWidth).toFixed(1) + 'px',
                    width: (0.0784 * baseWidth).toFixed(1) + 'px',
                }"
            />
            <span
                :style="{
                    'font-size': (0.0417 * baseWidth).toFixed(1) + 'px',
                    'line-height': (0.0686 * baseWidth).toFixed(1) + 'px',
                }"
                >Войти с помощью Telegram</span
            >
        </div>
        <div ref="telegram" id="tg-auth-widget" style="display: none"></div>
    </div>
</template>

<style lang="scss" scoped>
@import './../../scss/vars';

.telegram-auth {
    width: 100%;
}

.telegram-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: $functionalBlue10;
    color: $neuralNeural10;
    transition: 0.3s;
    cursor: pointer;

    &:hover {
        background-color: $functionalBlue20;
    }

    & span {
        font-weight: 400;
        letter-spacing: 0.12px;
    }
}
</style>
