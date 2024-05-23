<script setup>
import { ref, reactive, watch } from 'vue'
import { useElementSize } from '@vueuse/core'

const props = defineProps({
    accounts: {
        type: Object,
        required: true,
    },
})

const el2 = ref(null)
const { width } = useElementSize(el2)
const baseHeight = ref('540px')
const levelLine = ref('663px')
const dim24 = ref('24px')
const dim22 = ref('22px')
const dim20 = ref('20px')
const dim16 = ref('16px')
const dim13 = ref('13px')
const dim12 = ref('12px')
const dim6 = ref('6px')

const page = reactive({
    title: '56px',
})

watch(width, () => {
    dim24.value = String((0.0212 * width.value).toFixed(2)) + 'px' //24px
    baseHeight.value = String((0.483 * width.value).toFixed(2)) + 'px' //524px
    dim22.value = String((0.01943 * width.value).toFixed(2)) + 'px' //22px
    dim20.value = String((0.01767 * width.value).toFixed(2)) + 'px' //20px
    dim16.value = String((0.0141 * width.value).toFixed(2)) + 'px' //16px
    dim13.value = String((0.01148 * width.value).toFixed(2)) + 'px' //13px
    dim12.value = String((0.0106 * width.value).toFixed(2)) + 'px' //12px
    dim6.value = String((0.0053 * width.value).toFixed(2)) + 'px' //12px
    levelLine.value = String((0.61 * width.value).toFixed(2)) + 'px'
    // fontSize.value = String((0.081 * height.value).toFixed(2)) + 'px'
    // fontLine.value = String((0.133 * height.value).toFixed(2)) + 'px'
})
</script>

<template>
    <div class="panel w-full" ref="el2">
        <div class="line">
            <div class="left-column">Статус</div>
            <div class="right-column">Количество</div>
        </div>
        <div
            class="line"
            v-for="(value, name, index) in Object.fromEntries(
                Object.entries(props.accounts).filter(([key]) => key != 'total')
            )"
            :key="index"
        >
            <div class="left-column">{{ $t(name + 'Accounts') }}</div>
            <div class="right-column">
                <div
                    class="right-column__line"
                    :style="
                        'width: ' + ((0.61 * width * value) / props.accounts.free).toFixed(2) + 'px'
                    "
                ></div>
                <div class="right-column__text">{{ value }}</div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.panel {
    padding: v-bind(dim24);
    border-radius: v-bind(dim16);
    height: v-bind(baseHeight);
}

.line {
    display: flex;
    flex-direction: row;
    width: 100%;
    border-top: 1px solid #e7eaf0;
    padding-top: v-bind(dim12);
    padding-bottom: v-bind(dim12);
    font-size: v-bind(dim13);
    line-height: v-bind(dim20);
    font-weight: 400;
    gap: v-bind(dim6);

    &:first-child {
        border-top: none;
        padding-top: 0;
    }

    &:last-child {
        padding-bottom: 0;
    }

    & .left-column,
    & .right-column {
        user-select: none;
        height: v-bind(dim22);
        display: flex;
        align-items: center;
    }

    & .right-column {
        width: 61.16%;

        &__line {
            position: absolute;
            // width: v-bind(levelLine);
            max-width: v-bind(levelLine);
            height: v-bind(dim22);
            background-color: rgba(22, 101, 255, 0.2);
            z-index: 0;
            border-radius: v-bind(dim6);
        }

        &__text {
            margin-left: v-bind(dim12);
            z-index: 1;
            text-align: center;
            display: inline-block;
            vertical-align: middle;

            line-height: v-bind(dim16);
        }
    }

    & .left-column {
        width: 38.3%;
    }
}
</style>
