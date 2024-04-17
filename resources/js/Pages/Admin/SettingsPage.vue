<script setup>
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'

import { useAxios } from '@/Composables/useAxios'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import CheckboxInput from '@/Components/Inputs/CheckboxInput.vue'
import { useToast } from 'vue-toastification'
import { watch } from 'vue'
import { globalSettings } from '@/Store/globalSettings'
import TextInput from '@/Components/Inputs/TextInput.vue'
import AppButton from '@/Components/AppButton.vue'

const props = defineProps({
    settings: {
        type: Object,
        required: true,
    },
})

const api = useAxios()
const toast = useToast()

const maintenanceMode = ref(props.settings.maintenance_mode)

const prices = ref({
    purchase: props.settings['price.purchase'] ?? 0,
    review: props.settings['price.review'] ?? 0,
    pick_up: props.settings['price.pick_up'] ?? 0,
    review_reaction: props.settings['price.review_reaction'] ?? 0,
    review_complaint: props.settings['price.review_complaint'] ?? 0,
    action_cart: props.settings['price.action_cart'] ?? 0,
    action_search: props.settings['price.action_search'] ?? 0,
})

const savePrices = () => {
    api.post(route('admin.settings.set-prices'), prices.value).then(() =>
        toast.success('Цены сохранены')
    )
}

const saveSetting = () => {
    api.post(route('admin.settings.set'), {
        key: 'maintenance_mode',
        value: maintenanceMode.value,
    }).then(() => {
        globalSettings.value.maintenance_mode = maintenanceMode.value
        toast.success('Настройки сохранены')
    })
}

watch(maintenanceMode, () => {
    saveSetting()
})
</script>
<template>
    <Head>
        <title>Настройки</title>
    </Head>

    <AuthenticatedLayout>
        <template #header>Настройки</template>

        <div class="space-y-6">
            <div class="panel panel_p-lg">
                <div class="flex">
                    <CheckboxInput v-model:checked="maintenanceMode">
                        Режим обслуживания
                    </CheckboxInput>
                </div>
            </div>

            <div class="panel panel_p-lg">
                <div class="mb-4">Цены</div>

                <div class="space-y-4">
                    <TextInput v-model="prices.purchase" label="Выкуп" />
                    <TextInput v-model="prices.review" label="Отзыв" />
                    <TextInput v-model="prices.pick_up" label="Забор" />
                    <TextInput v-model="prices.review_reaction" label="Реакция на отзыв" />
                    <TextInput v-model="prices.review_complaint" label="Жалоба на отзыв" />
                    <TextInput v-model="prices.action_cart" label="Добавление в корзину" />
                    <TextInput v-model="prices.action_search" label="Поиск товара" />

                    <AppButton class="ml-auto" @click="savePrices">Сохранить</AppButton>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
