<script setup>
import { reactive, ref } from 'vue'
import { useToast } from 'vue-toastification'

import { useAxios } from '@/Composables/useAxios'

import { locationSelector, purchaseGenerator, purchaseSlide } from '@/modals.js'
import { products } from '@/Store/purchases'

import AppButton from '@/Components/AppButton.vue'
import DatePicker from '@/Components/Inputs/DatePicker.vue'
import QuantityInput from '@/Components/Inputs/QuantityInput.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import Modal from '@/Components/Modal.vue'
import LocationSelectorModal from '@/Modals/LocationSelector.vue'

const toast = useToast()
const api = useAxios()
const { loading } = api

const productInput = ref(null)

const open = () => {
    // nextTick(() => productInput.value.focus())
}

const state = reactive({
    productCode: '',
    product: null,
    keywords: [
        {
            value: '',
            counter: 1,
        },
    ],
    addresses: [],
    ranges: [
        {
            date: null,
            startTime: {
                hours: 0,
                minutes: 0,
            },
            endTime: {
                hours: 0,
                minutes: 0,
            },
            counter: 1,
        },
    ],
    errors: {},
})

const searchError = ref('')

const generatePurchases = () => {
    api.post(route('purchase.generate'), state)
        .then((resp) => {
            console.log(resp.data)
            purchaseGenerator.close()
            resp.data.purchases.forEach((element) => {
                products.push({ ...element, purchase_at: new Date(element.purchase_at) })
            })
            purchaseSlide.open()
            toast.success('Выкупы сгенерированы!')
        })
        .catch((error) => alert(JSON.stringify(error.response.data.errors)))
}

const searchProduct = () => {
    api.post(route('search.do'), {
        product_code: state.productCode,
    })
        .then((response) => {
            state.product = response.data.product
            searchError.value = ''
        })
        .catch((error) => {
            searchError.value = error.response.data.message
        })
}

const addKeyword = () => {
    state.keywords.push({
        value: '',
        counter: 1,
    })
}

const removeKeyword = (index) => {
    state.keywords.splice(index, 1)
}

const selectAddress = () => {
    locationSelector.open()
}

const removeAddress = (index) => {
    state.addresses.splice(index, 1)
}

const onAdressSelected = (pickpoint) => {
    console.log(pickpoint)
    state.addresses.push({
        value: pickpoint,
    })
}

const addRange = () => {
    state.ranges.push({
        date: null,
        startTime: {
            hours: 0,
            minutes: 0,
        },
        endTime: {
            hours: 0,
            minutes: 0,
        },
        counter: 1,
    })
}

const removeRange = (index) => {
    state.ranges.splice(index, 1)
}
</script>
<template>
    <Modal
        :show="purchaseGenerator.show"
        @close="purchaseGenerator.close()"
        body-class="modal__body_generator"
        @open="open"
    >
        <template #header> Массовый выкуп </template>

        <div v-if="!state.product" class="space-y-6">
            <div class="flex items-end gap-2">
                <div class="grow">
                    <TextInput
                        v-model="state.productCode"
                        ref="productInput"
                        size="lg"
                        label="Найдите товар для массового выкупа"
                        :has-error="searchError != ''"
                        :error-message="searchError"
                    />
                </div>

                <AppButton size="lg" @click="searchProduct">Добавить</AppButton>
            </div>
        </div>

        <div v-else>
            <div class="space-y-2 mb-6">
                <h5 class="header-5">Ключевой запрос</h5>

                <div
                    v-for="(keyword, index) in state.keywords"
                    :key="keyword"
                    class="flex gap-2 items-center"
                >
                    <div class="grow">
                        <TextInput v-model="keyword.value" />
                    </div>

                    <QuantityInput v-model="keyword.counter" size="md" />

                    <AppButton
                        v-if="state.keywords.length > 1"
                        theme="normal"
                        icon="close"
                        @click="removeKeyword(index)"
                    />
                </div>

                <div>
                    <AppButton theme="normal" icon="plus-circle" @click="addKeyword">
                        Добавить
                    </AppButton>
                </div>
            </div>

            <div class="space-y-2 mb-6">
                <h5 class="header-5">ПВЗ</h5>

                <div class="flex">
                    <AppButton theme="normal" class="grow" @click="selectAddress">
                        Выбрерите на карте
                    </AppButton>
                </div>

                <div
                    v-for="(address, index) in state.addresses"
                    :key="index"
                    class="flex gap-2 items-center"
                >
                    <div class="grow paragraph-3">
                        {{ address.value }}
                    </div>

                    <AppButton
                        v-if="state.addresses.length > 1"
                        theme="normal"
                        icon="close"
                        @click="removeAddress(index)"
                    />
                </div>
            </div>

            <div class="space-y-2">
                <h5 class="header-5">Когда делать выкупы</h5>

                <div
                    v-for="(range, index) in state.ranges"
                    :key="index"
                    class="flex gap-2 items-center"
                >
                    <div class="flex gap-2 grow paragraph-3">
                        <DatePicker v-model="range.date" size="md" type="date" class="basis-1/4" />
                        <DatePicker
                            v-model="range.startTime"
                            size="md"
                            type="time"
                            class="basis-1/4"
                        />
                        <DatePicker
                            v-model="range.endTime"
                            size="md"
                            type="time"
                            class="basis-1/4"
                        />

                        <QuantityInput v-model="range.counter" size="md" />
                    </div>

                    <AppButton
                        v-if="state.ranges.length > 1"
                        theme="normal"
                        icon="close"
                        @click="removeRange(index)"
                    />
                </div>

                <div>
                    <AppButton theme="normal" icon="plus-circle" @click="addRange">
                        Добавить
                    </AppButton>
                </div>
            </div>
        </div>

        <template #actions>
            <AppButton size="lg" :disabled="loading" @click="generatePurchases">
                Отправить
            </AppButton>
        </template>
    </Modal>

    <LocationSelectorModal
        v-if="purchaseGenerator.show"
        map-container="massPurchaseGeneratorMap"
        @select-pickpoint="onAdressSelected"
    />
</template>
