<script setup>
import { ref, watch } from 'vue'
import { currencyFormater } from '@/Helpers/formater.js'

import LabelText from '@/Components/LabelText.vue'

const props = defineProps({
    quantity: {
        type: Number,
        default: 0,
    },
    totalPrice: {
        type: Number,
        default: 0,
    },
    totalService: {
        type: Number,
        default: 0,
    },
})

const priceTotal = ref(props.totalPrice)
const priceService = ref(props.totalService)

watch(
    () => props.totalPrice,
    (newVal, oldVal) => {
        currencyFormater.animateValue(priceTotal, oldVal, newVal, 200)
    }
)

watch(
    () => props.totalService,
    (newVal, oldVal) => {
        currencyFormater.animateValue(priceService, oldVal, newVal, 200)
    }
)
</script>
<template>
    <template v-if="quantity == 0">
        Добавьте товары по артикулу, чтобы рассчитать сумму выкупа
    </template>
    <div v-else class="flex gap-1.5">
        <LabelText>
            Товаров:
            {{ quantity }} шт.
        </LabelText>

        <LabelText>
            Сумма выкупа:
            {{ currencyFormater.format(priceTotal) }}
        </LabelText>

        <LabelText>
            Услуги сервиса:
            {{ currencyFormater.format(priceService) }}
        </LabelText>
    </div>
</template>
