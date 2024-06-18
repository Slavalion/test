<script setup>
import { ref, watch } from 'vue'
import { useElementSize } from '@vueuse/core'

const props = defineProps({})

const el4 = ref(null)
const { width } = useElementSize(el4)
const baseHeight = ref('540px')
const levelLine = ref('663px')
const dim24 = ref('24px')
const dim22 = ref('22px')
const dim20 = ref('20px')
const dim16 = ref('16px')
const dim13 = ref('13px')
const dim12 = ref('12px')
const dim6 = ref('6px')

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
})
</script>

<template>
    <div class="panel w-full" ref="el4">
        <div class="line">
            <div class="col1">Услуга</div>
            <div class="col2">Юзер</div>
            <div class="col3">Дата</div>
            <div class="col4">Причина</div>
            <div class="col5">
                <button>Удалить все</button>
            </div>
        </div>

        <div class="line" v-for="n in 13">
            <div class="col1 blue">Отзыв</div>
            <div class="col2">@casfa</div>
            <div class="col3">1</div>
            <div class="col4">Недостаточно средств</div>
            <div class="col5">
                <button>Удалить</button>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.panel {
    padding: v-bind(dim24);
    border-radius: v-bind(dim16);
    display: flex;
    flex-direction: column;
}

button {
    background-color: #e0281b;
    color: white;
    padding-top: v-bind(dim6);
    padding-left: v-bind(dim12);
    padding-bottom: v-bind(dim6);
    padding-right: v-bind(dim12);
    border-radius: v-bind(dim6);
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
}

.col1 {
    width: 10%;
}
.col2 {
    width: 22%;
}
.col3 {
    width: 8%;
}
.col4 {
    width: 50%;
}
.col5 {
    width: 10%;
    display: flex;
    justify-content: flex-end;
    align-items: center;
}
.blue {
    color: #1665ff;
}
</style>
