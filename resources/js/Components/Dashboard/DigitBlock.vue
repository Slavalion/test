<script setup>
import AppPanel from '../AppPanel.vue'

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
const { height } = useElementSize(el)

watch(height, () => {
    baseWidth.value = String(height.value.toFixed(2)) + 'px'
    svgWidth.value = String((0.228 * height.value).toFixed(2)) + 'px'
    headerLine.value = String((0.267 * height.value).toFixed(2)) + 'px'
    fontSize.value = String((0.081 * height.value).toFixed(2)) + 'px'
    fontLine.value = String((0.133 * height.value).toFixed(2)) + 'px'
})
</script>
<template>
    <AppPanel>
        <div class="text-center">
            <div>Icon</div>
            <div>{{ digit }}</div>
            <div>
                <slot></slot>
            </div>
        </div>
    </AppPanel>
</template>

<style lang="scss" scoped>
.digitBlock {
    height: 100%;

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

@media (min-width: 1441px) {
    .digitBlock__digit {
        font-size: 48px;
        line-height: 56px;
    }
}
</style>
