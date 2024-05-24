<script setup>
import { onMounted, ref, watch } from 'vue'
import { useElementSize } from '@vueuse/core'

defineProps({
    title: {
        type: String,
        required: true,
    },
    legend: {
        type: Object,
        default: {
            1: {
                id: 1,
                name: 'выдано QR',
                color: '#1665FF',
            },
            2: {
                id: 2,
                name: 'заборов из ПВЗ',
                color: '#16C050',
            },
        },
    },
})

const red = 'red'
const green = 'green'

const el = ref(null)
const { width } = useElementSize(el)
const baseHeight = ref('540px')
const dim44 = ref('44px')
const dim40 = ref('40px')
const dim36 = ref('36px')
const dim30 = ref('30px')
const dim24 = ref('24px')
const dim23 = ref('23px')
const dim20 = ref('20px')
const dim16 = ref('16px')
const dim14 = ref('14px')
const dim6 = ref('6px')
const dim2 = ref('2px')

watch(width, () => {
    baseHeight.value = String((0.483 * width.value).toFixed(2)) + 'px' //524px
    dim44.value = String((0.0388 * width.value).toFixed(2)) + 'px' //44px
    dim40.value = String((0.0353 * width.value).toFixed(2)) + 'px' //40px
    dim36.value = String((0.0318 * width.value).toFixed(2)) + 'px' //36px
    dim30.value = String((0.0265 * width.value).toFixed(2)) + 'px' //30px
    dim24.value = String((0.0212 * width.value).toFixed(2)) + 'px' //24px
    dim23.value = String((0.0203 * width.value).toFixed(2)) + 'px' //23px
    dim20.value = String((0.0177 * width.value).toFixed(2)) + 'px' //20px
    dim16.value = String((0.0141 * width.value).toFixed(2)) + 'px' //16px
    dim14.value = String((0.0124 * width.value).toFixed(2)) + 'px' //14px
    dim6.value = String((0.0053 * width.value).toFixed(2)) + 'px' //6px
    dim2.value = String((0.002 * width.value).toFixed(2)) + 'px' //2.32px
})

const canvasElement = ref(undefined)
const context = ref(undefined)

onMounted(() => {
    context.value = canvasElement.value?.getContext('2d') || undefined

    render()
})

function render() {
    if (!context.value) {
        return
    }

    context.value.fillText('CHAT', 50, 50)
}
</script>

<template>
    <div class="panel w-full chart" ref="el">
        <div class="chart-header">
            <div class="chart-header__title">{{ title }}</div>
            <div class="chart-header__legend">
                <div class="chart-header__legend-item" v-for="leg of legend">
                    <div
                        class="chart-header__legend-item kub"
                        :style="'background-color:' + leg.color"
                    ></div>
                    <div class="chart-header__legend-item-name">{{ leg.name }}</div>
                </div>
            </div>
        </div>
        <canvas ref="canvasElement" width="200" height="200" />
    </div>
</template>

<style lang="scss" scoped>
.chart {
    padding-left: v-bind(dim40);
    padding-right: v-bind(dim40);
    padding-bottom: v-bind(dim44);
    padding-top: v-bind(dim30);
    border-radius: v-bind(dim16);
    height: v-bind(baseHeight);
    display: flex;
    flex-direction: column;
    gap: v-bind(dim23);

    &-header {
        width: 100%;
        display: flex;
        justify-content: space-between;
        flex-direction: row;

        &__title {
            color: rgba(86, 91, 102, 1);
            font-weight: 600;
            font-size: v-bind(dim23);
            line-height: v-bind(dim36);
            letter-spacing: 0.12px;
        }

        &__legend {
            display: flex;
            justify-content: flex-end;
            flex-direction: row;
            gap: v-bind(dim20);

            &-item {
                display: flex;
                justify-content: flex-end;
                flex-direction: row;
                gap: v-bind(dim6);
                align-items: center;

                &-name {
                    font-weight: 400;
                    font-size: v-bind(dim14);
                    line-height: v-bind(dim23);
                }
            }
        }
    }
}

.kub {
    min-width: v-bind(dim16);
    min-height: v-bind(dim16);
    width: v-bind(dim16);
    height: v-bind(dim16);
    border-radius: v-bind(dim2);
    // background-color: aqua;
}
</style>
