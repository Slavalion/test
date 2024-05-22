<script setup>
import Modal from '@/Components/Modal.vue'
import { nextTick, ref } from 'vue'

import { useAxios } from '@/Composables/useAxios'

import { fillUpBalance } from '../modals.js'

import AppButton from '@/Components/AppButton.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'

const api = useAxios()
const { loading } = api

const amounts = {
    '1 000 ₽': '1000',
    '2 000 ₽': '2000',
    '5 000 ₽': '5000',
    '10 000 ₽': '10000',
    '20 000 ₽': '20000',
}

const amount = ref('1000')
const balanceInput = ref(null)

const open = () => {
    nextTick(() => balanceInput.value.focus())
}

const fillBalance = function () {
    api.post(route('balance.fill'), { amount: amount.value }).then((resp) => {
        window.open(resp.data.redirect_to, '_blank')
    })
}
</script>
<template>
    <Modal :show="fillUpBalance.show" @close="fillUpBalance.close()" @open="open">
        <template #header> Пополнить баланс </template>

        <template #caption>
            С баланса списывается стоимость услуг за сервис, сам товар оплачивается отдельно в
            разделе «Выкупы»
        </template>

        <div class="fillup-modal__icons">
            <img src="/images/icons-pay.svg" alt="" />
        </div>

        <div class="fillup-modal__input">
            <TextInput
                v-model="amount"
                ref="balanceInput"
                size="xl"
                icon="rub"
                placeholder="Введите сумму"
            />
        </div>

        <div class="fillup-modal__fill-buttons">
            <AppButton
                v-for="(value, key) in amounts"
                :key="key"
                size="lg"
                theme="normal"
                :class="{ btn_selected: amount == value }"
                @click="amount = value"
            >
                {{ key }}
            </AppButton>
        </div>

        <template #actions>
            <AppButton size="lg" :disabled="loading" @click="fillBalance">Пополнить</AppButton>
        </template>
    </Modal>
</template>
