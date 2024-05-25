<script setup>
import { onMounted, ref, watch } from 'vue'
import { useElementSize } from '@vueuse/core'

const props = defineProps({
    variant: {
        type: String,
        required: true,
        default: 'logistic',
    },
    title: {
        type: String,
        required: true,
    },
    legend: {
        type: Object,
        default: {
            // 1: {
            //     id: 1,
            //     name: 'выдано QR',
            //     color: '#1665FF',
            // },
            // 2: {
            //     id: 2,
            //     name: 'заборов из ПВЗ',
            //     color: '#16C050',
            // },
        },
    },
    dataSource: {
        type: Object,
        default: {
            0: {
                time: '09:00',
                qr: 0.8,
                pvz: 3.8,
            },
            1: {
                time: '10:00',
                qr: 3,
                pvz: 2.9,
            },
            2: {
                time: '11:00',
                qr: 1.1,
                pvz: 4,
            },
        },
    },
})

const el = ref(null)
const { width } = useElementSize(el)
const previousWidth = ref(1132)
const widthChart = ref('1000px')
const heightChart = ref('310px')
const widthDPI = ref(1000)
const heightDPI = ref(310)

const deltaDots = ref(6)
const baseHeight = ref('500px')
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

const qrData = ref([])
const pvzData = ref([])
const infoDots = ref([])
const activeDot = ref([])

const canvasElement = ref(undefined)
const context = ref(undefined)

watch(width, () => {
    if (
        Math.abs(Math.round(width.value) - previousWidth.value) >
        Math.round(previousWidth.value / 100)
    ) {
        previousWidth.value = Math.round(width.value)

        baseHeight.value = String((0.442 * width.value).toFixed(2)) + 'px' //504px
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
        dim2.value = String((0.002 * width.value).toFixed(2)) + 'px' //2.
        widthChart.value = String((0.8834 * width.value).toFixed(0)) + 'px' //width canvas.
        heightChart.value = String((0.2739 * width.value).toFixed(0)) + 'px' //height canvas

        const widthCanvas = useElementSize(canvasElement).width.value

        infoDots.value = []

        Object.keys(props.dataSource).forEach((i, index) => {
            infoDots.value.push(
                [
                    Math.round(
                        (68 + 39.5 * index) *
                            (widthChart.value / 1000) *
                            (widthCanvas / widthChart.value)
                    ),
                    Math.round(
                        (((291 - 28 * props.dataSource[i].qr) * widthChart.value) / 1000) *
                            (widthCanvas / widthChart.value)
                    ),
                    'qr',
                    props.dataSource[i].qr,
                ],
                [
                    Math.round(
                        (((68 + 39.5 * index) * widthChart.value) / 1000) *
                            (widthCanvas / widthChart.value)
                    ),
                    Math.round(
                        (((291 - 28 * props.dataSource[i].pvz) * widthChart.value) / 1000) *
                            (widthCanvas / widthChart.value)
                    ),
                    'pvz',
                    props.dataSource[i].pvz,
                ]
            )
        })

        render()
    }
})

watch(activeDot, () => {
    if (activeDot.value.length > 0) {
        console.log(activeDot)
        console.log('x:' + activeDot.value[0] + ' y:' + activeDot.value[1])
    }
})

const mouseOnCanvas = (e) => {
    if (
        infoDots.value.filter(
            (dot) =>
                dot[0] - deltaDots.value < e.offsetX &&
                dot[0] + deltaDots.value > e.offsetX &&
                dot[1] - deltaDots.value < e.offsetY &&
                dot[1] + deltaDots.value > e.offsetY
        ).length > 0
    ) {
        const activeDots = infoDots.value.filter(
            (dot) =>
                dot[0] - deltaDots.value < e.offsetX &&
                dot[0] + deltaDots.value > e.offsetX &&
                dot[1] - deltaDots.value < e.offsetY &&
                dot[1] + deltaDots.value > e.offsetY
        )
        activeDot.value = activeDots[0]
    } else if (activeDot.value.length > 0) {
        activeDot.value = []
    }

    // console.log('x:' + e.offsetX + ' y:' + e.offsetY)
    // console.log(
    //     'x:' + String(e.pageX - e.target.offsetLeft) + ' y:' + String(e.pageY - e.target.offsetTop)
    // )
}

onMounted(() => {
    previousWidth.value = Math.round(width.value)

    Object.keys(props.dataSource).forEach((i, index) => {
        qrData.value[i] = props.dataSource[i].qr
        pvzData.value[i] = props.dataSource[i].pvz

        infoDots.value.push(
            [
                Math.round((68 + 38 * index) * (widthChart.value / 1000)),
                Math.round(((291 - 28 * props.dataSource[i].qr) * widthChart.value) / 1000),
                'qr',
                props.dataSource[i].qr,
            ],
            [
                Math.round(((68 + 38 * index) * widthChart.value) / 1000),
                Math.round(((291 - 28 * props.dataSource[i].pvz) * widthChart.value) / 1000),
                'pvz',
                props.dataSource[i].pvz,
            ]
        )
    })

    context.value = canvasElement.value?.getContext('2d') || undefined
    context.value.width = widthDPI.value
    context.value.height = heightDPI.value

    render()
})

function render() {
    if (!context.value) {
        return
    }

    context.value.clearRect(0, 0, widthDPI.value, heightDPI.value)

    context.value.beginPath()
    context.value.moveTo(50, 292)
    context.value.lineTo(1000, 292)
    context.value.lineWidth = 2
    context.value.strokeStyle = '#000'
    context.value.stroke()

    context.value.beginPath()
    for (let i = 0; i < 5; i++) {
        context.value.moveTo(50, 10 + 56 * i)
        context.value.lineTo(1000, 10 + 56 * i)
    }
    context.value.strokeStyle = '#EEEEEE'
    context.value.stroke()

    context.value.font = '10px Verdana'
    context.value.fillStyle = 'navy'
    if (props.variant === 'logistic') {
        for (let i = 0; i < 6; i++) {
            context.value.fillText(String(10 - 2 * i), 30, 14 + 56 * i)
        }
    } else if (props.variant === 'balanse' || props.variant === 'income') {
        for (let i = 0; i < 6; i++) {
            context.value.fillText(i === 5 ? '0' : String(10 - 2 * i) + '000 p', 10, 14 + 56 * i)
        }
    }

    context.value.font = '8px Verdana'
    context.value.fillStyle = '#3C5A7B'
    context.value.fillText('09:00', 50, 305)
    for (let i = 1; i < 16; i++) {
        context.value.fillText(String(i + 9) + ':00', 50 + 38 * i, 305)
    }
    for (let i = 0; i < 9; i++) {
        context.value.fillText('0' + String(i) + ':00', 50 + 38 * (i + 16), 305)
    }

    if (props.variant === 'logistic' || props.variant === 'balanse') {
        context.value.beginPath()
        context.value.moveTo(68, 291 - 28 * pvzData.value[0])
        for (let i = 1; i < 26; i++) {
            context.value.lineTo(68 + 38 * (i + 1), 291 - 28 * pvzData.value[i])
        }
        context.value.lineWidth = 2
        context.value.strokeStyle = props.legend[2].color // '#16C050'
        context.value.stroke()

        context.value.beginPath()
        context.value.moveTo(68, 291 - 28 * qrData.value[0])
        for (let i = 1; i < 26; i++) {
            context.value.lineTo(68 + 38 * (i + 1), 291 - 28 * qrData.value[i])
        }
        context.value.lineWidth = 2
        context.value.strokeStyle = props.legend[1].color //'#1665FF'
        context.value.stroke()
    }
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
        <div>
            <div class="toast" v-show="activeDot.length > 0" style="margin-left: 120px"></div>
            <canvas
                ref="canvasElement"
                @mousemove="mouseOnCanvas"
                :width="widthDPI"
                :height="heightDPI"
            />
        </div>
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
        width: 96%;
        display: flex;
        justify-content: space-between;
        flex-direction: row;
        padding-left: v-bind(dim14);

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
}

.toast {
    position: absolute;
    min-width: 20px;
    min-height: 20px;
    border-radius: 50%;
    background-color: red;
}

canvas {
    width: 100%;
}
</style>
