<script setup>
const props = defineProps({
    accounts: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <div class="panel w-full">
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
            <div class="left-column">{{ name }}</div>
            <div class="right-column">{{ value }}</div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.panel {
    padding: 24px;
    border-radius: 16px;
}

.line {
    display: flex;
    flex-direction: row;
    width: 100%;
    border-top: 1px solid #e7eaf0;
    padding-top: 12px;
    padding-bottom: 12px;

    &:first-child {
        border-top: none;
    }

    & .left-column {
        width: 35%;
    }

    & .rigth-column {
        width: 65%;
    }
}
</style>
