<script setup>
import { onMounted, ref, watch } from 'vue'
import { useElementSize } from '@vueuse/core'

const props = defineProps({
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
            3: {
                time: '12:00',
                qr: 2.1,
                pvz: 6,
            },
            4: {
                time: '13:00',
                qr: 3,
                pvz: 4,
            },
            5: {
                time: '14:00',
                qr: 5,
                pvz: 3,
            },
            6: {
                time: '15:00',
                qr: 2.2,
                pvz: 2,
            },
            7: {
                time: '16:00',
                qr: 3.4,
                pvz: 6,
            },
            8: {
                time: '17:00',
                qr: 4.8,
                pvz: 2.8,
            },
            9: {
                time: '18:00',
                qr: 3,
                pvz: 2.6,
            },
            10: {
                time: '19:00',
                qr: 4,
                pvz: 1.7,
            },
            11: {
                time: '20:00',
                qr: 6.2,
                pvz: 3.8,
            },
            12: {
                time: '21:00',
                qr: 1,
                pvz: 3.8,
            },
            13: {
                time: '22:00',
                qr: 1.2,
                pvz: 3.4,
            },
            14: {
                time: '23:00',
                qr: 0.8,
                pvz: 3.8,
            },
            15: {
                time: '00:00',
                qr: 0.7,
                pvz: 3.8,
            },
            16: {
                time: '01:00',
                qr: 5,
                pvz: 2.1,
            },
            17: {
                time: '02:00',
                qr: 1.2,
                pvz: 3.8,
            },
            18: {
                time: '03:00',
                qr: 1.4,
                pvz: 3.8,
            },
            19: {
                time: '04:00',
                qr: 3,
                pvz: 2,
            },
            20: {
                time: '05:00',
                qr: 5,
                pvz: 3,
            },
            21: {
                time: '06:00',
                qr: 1,
                pvz: 3.5,
            },
            22: {
                time: '07:00',
                qr: 0.8,
                pvz: 3.8,
            },
            23: {
                time: '08:00',
                qr: 8.2,
                pvz: 0.8,
            },
        },
    },
})

const el = ref(null)
const { width } = useElementSize(el)
const previousWidth = ref(1132)
const widthCart = ref(1000)
const heightCart = ref(310)
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
        dim2.value = String((0.002 * width.value).toFixed(2)) + 'px' //2.32px
    }
})

const canvasElement = ref(undefined)
const context = ref(undefined)

onMounted(() => {
    previousWidth.value = Math.round(width.value)
    console.log(props.dataSource)

    Object.keys(props.dataSource).forEach((i) => {
        console.log(props.dataSource[i])
        qrData.value[i] = props.dataSource[i].qr
        pvzData.value[i] = props.dataSource[i].pvz
    })

    console.log(qrData.value)

    context.value = canvasElement.value?.getContext('2d') || undefined

    render()
})

function render() {
    if (!context.value) {
        return
    }

    context.value.beginPath()

    context.value.moveTo(50, 10)
    context.value.lineTo(950, 10)
    context.value.moveTo(50, 66)
    context.value.lineTo(950, 66)
    context.value.moveTo(50, 122)
    context.value.lineTo(950, 122)
    context.value.moveTo(50, 178)
    context.value.lineTo(950, 178)
    context.value.moveTo(50, 234)
    context.value.lineTo(950, 234)
    context.value.strokeStyle = '#EEEEEE'
    context.value.stroke()
    context.value.beginPath()
    context.value.moveTo(50, 290)
    context.value.lineTo(970, 290)
    context.value.lineWidth = 2
    context.value.strokeStyle = '#000'
    context.value.stroke()

    context.value.beginPath()
    context.value.moveTo(55, 291 - 28 * pvzData.value[0])
    for (let i = 1; i < 26; i++) {
        context.value.lineTo(55 + 38 * (i + 1), 291 - 28 * pvzData.value[i])
    }
    context.value.lineWidth = 2
    context.value.strokeStyle = '#16C050'
    context.value.stroke()

    context.value.beginPath()
    context.value.moveTo(55, 291 - 28 * qrData.value[0])
    for (let i = 1; i < 26; i++) {
        context.value.lineTo(55 + 38 * (i + 1), 291 - 28 * qrData.value[i])
    }
    context.value.lineWidth = 2
    context.value.strokeStyle = '#1665FF'
    context.value.stroke()

    context.value.font = '10px Verdana'
    context.value.fillStyle = 'navy'
    context.value.fillText('10', 28, 14)
    context.value.fillText('8', 30, 70)
    context.value.fillText('6', 30, 126)
    context.value.fillText('4', 30, 182)
    context.value.fillText('2', 30, 238)
    context.value.fillText('0', 30, 295)
    context.value.font = '9px Verdana'
    context.value.fillStyle = '#3C5A7B'
    context.value.fillText('09:00', 50, 308)
    for (let i = 1; i < 16; i++) {
        context.value.fillText(String(i + 9) + ':00', 50 + 37 * i, 308)
    }
    for (let i = 0; i < 9; i++) {
        context.value.fillText('0' + String(i) + ':00', 645 + 37 * i, 308)
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
        <canvas ref="canvasElement" :width="widthCart" :height="heightCart" />
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
    // background-color: aqua;
}
</style>
