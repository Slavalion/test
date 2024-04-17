<script setup>
import { computed, ref } from 'vue'

import { useAxios } from '@/Composables/useAxios'
import { useToast } from 'vue-toastification'

import TextInput from '@/Components/Inputs/TextInput.vue'
import AppButton from '@/Components/AppButton.vue'
import SelectInput from '@/Components/Inputs/SelectInput.vue'

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    prices: {
        type: Object,
        required: true,
    },
})

const toast = useToast()
const api = useAxios()

const { loading } = api

const priceTypes = {
    purchase: {
        name: 'Выкуп',
    },
    review: {
        name: 'Отзыв',
    },
    pick_up: {
        name: 'Забор',
    },
}

const prices = ref(
    props.prices.reduce((acc, val) => {
        return [
            ...acc,
            {
                type: val.type,
                name: priceTypes[val.type].name,
                value: val.value,
            },
        ]
    }, [])
)

const priceItems = computed(() => {
    return Object.keys(priceTypes).reduce((acc, val) => {
        if (prices.value.find((el) => el.type == val)) {
            return acc
        }

        return [
            ...acc,
            {
                key: val,
                name: priceTypes[val].name,
            },
        ]
    }, [])
})

const selectedPriceType = ref(priceItems.value[0])

const savePrices = () => {
    api.post(route('admin.users.set-prices'), {
        user_id: props.user.id,
        prices: prices.value.reduce((acc, val) => ({ ...acc, [val.type]: val.value }), {}),
    }).then(() => toast.success('Цены сохранены'))
}

const addPersonalPrice = () => {
    prices.value.push({
        type: selectedPriceType.value.key,
        name: selectedPriceType.value.name,
        value: 0,
    })

    if (priceItems.value.length) {
        selectedPriceType.value = priceItems.value[0]
    }
}
</script>
<template>
    <div class="panel panel_p-lg">
        <div class="text-lg font-bold mb-4">Персональные цены</div>

        <div class="space-y-4">
            <div v-for="price in prices" class="flex justify-between">
                <div>{{ price.name }}</div>
                <TextInput v-model="price.value" size="sm" />
            </div>

            <div v-if="priceItems.length" class="flex gap-2 items-center justify-end">
                <SelectInput v-model="selectedPriceType" :items="priceItems" />
                <AppButton @click="addPersonalPrice" size="sm" theme="normal" icon="plus-circle" />
            </div>

            <div class="flex items-center">
                <AppButton class="ml-auto" @click="savePrices">Сохранить</AppButton>
            </div>
        </div>
    </div>
</template>
