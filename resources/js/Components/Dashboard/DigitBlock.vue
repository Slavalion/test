<script setup>
import { ref, watch } from 'vue'
import AppPanel from '../AppPanel.vue'
import { useElementSize } from '@vueuse/core'
import AppIcon from '@/Components/AppIcon.vue'

defineProps({
    digit: {
        type: Number,
        required: true,
    },
    icon: {
        type: String,
        required: true,
    },
})

const el = ref(null)
const baseWidth = ref('40px')
const headerLine = ref('56px')
const svgWidth = ref('10px')
const fontSize = ref('17px')
const fontLine = ref('28px')
const { width } = useElementSize(el)

watch(width, () => {
    baseWidth.value = String(width.value.toFixed(2)) + 'px'
    svgWidth.value = String((0.228 * width.value).toFixed(2)) + 'px'
    headerLine.value = String((0.267 * width.value).toFixed(2)) + 'px'
    fontSize.value = String((0.081 * width.value).toFixed(2)) + 'px'
    fontLine.value = String((0.133 * width.value).toFixed(2)) + 'px'
})
</script>
<template>
    <AppPanel class="digitBlock" ref="el">
        <div class="text-center digitBlock__base">
            <AppIcon :icon="icon" class="digitBlock__icon" />
            <div class="digitBlock__digit">{{ digit }}</div>

            <div class="digitBlock__title">
                <slot></slot>
            </div>
        </div>
    </AppPanel>
</template>

<style lang="scss" scoped>
.digitBlock {
    height: v-bind(baseWidth);
    padding: 5%;

    &__base {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    &__icon {
        width: v-bind(svgWidth);
        height: v-bind(svgWidth);
        margin-bottom: 0.486vw;
    }

    &__digit {
        font-size: v-bind(svgWidth);
        line-height: v-bind(headerLine);
        font-weight: 600;
        letter-spacing: -0.03px;
    }

    &__title {
        font-size: v-bind(fontSize);
        line-height: v-bind(fontLine);
        font-weight: 400;
        letter-spacing: 0.18px;
    }
}
</style>
