<script setup>
import { router, usePage } from '@inertiajs/vue3'
import { useLocalStorage } from '@vueuse/core'
import { computed, nextTick, onUnmounted, reactive, ref } from 'vue'
import { useToast } from 'vue-toastification'

import { useAxios } from '@/Composables/useAxios'

import wallets from '@/Data/wallets'
import { addWallet } from '../modals.js'

import AppButton from '@/Components/AppButton.vue'
import CaptionBlock from '@/Components/CaptionBlock.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import Modal from '@/Components/Modal.vue'
import ProgressBar from '@/Components/ProgressBar.vue'
import { onMounted } from 'vue'

const page = usePage()
const toast = useToast()
const api = useAxios()

const user = page.props.auth.user

const loginInput = ref(null)
const passwordInput = ref(null)

const walletId = useLocalStorage('addWallet_walletID', null)
const currentStep = useLocalStorage('addWallet_step', 1)
const modalLoading = useLocalStorage('addWallet_loading', false)
const walletProgress = useLocalStorage('addWallet_progress', 0)

const open = () => {
    nextTick(() => loginInput.value.focus())
}

const walletCreds = reactive({
    login: '',
    password: '',
    code: addWallet.walletCode,
    errors: {},
})

const walletConfirm = reactive({
    code: '',
    errors: {},
})

const loginErrors = computed(() => {
    let errors = ''

    Object.keys(walletCreds.errors).forEach((el) => {
        errors += ' ' + walletCreds.errors[el].join(',')
    })

    return errors
})

const disabledNext = computed(() => {
    if (currentStep.value == 1) {
        return !(walletCreds.login != '' && walletCreds.login.indexOf('@') > -1)
    }

    if (currentStep.value == 2) {
        return walletCreds.password == ''
    }

    if (currentStep.value == 3) {
        return walletConfirm.code == ''
    }

    return false
})

const badStatuses = ['bad_pass', 'bad_login', 'bad_code']

const nextStep = function () {
    if (currentStep.value == 3) {
        if (walletConfirm.code == '') {
            return
        }

        modalLoading.value = true

        api.post(route('wallets.confirm'), { code: walletConfirm.code, id: walletId.value })
            .then(() => {
                startListeningAfterThirdStep()
            })
            .catch((error) => {
                currentStep.value = 1
                walletProgress.value = 0
                modalLoading.value = false
                walletCreds.errors = error.response.data.errors ?? {}
            })
    }

    if (currentStep.value == 2) {
        if (walletCreds.password == '') {
            return
        }

        modalLoading.value = true

        api.post(route('wallets.connect'), walletCreds)
            .then((resp) => {
                walletId.value = resp.data.wallet_id
                startListeningAfterSecondStep()
            })
            .catch((error) => {
                currentStep.value = 1
                modalLoading.value = false
                walletCreds.errors = error.response.data.errors ?? {}
            })
    }

    if (currentStep.value == 1) {
        if (walletCreds.login == '' || walletCreds.login.indexOf('@') == -1) {
            toast.error('Для авторизации необходимо использовать email адрес')
            return
        }

        currentStep.value = 2
        walletProgress.value = 33.333333
        nextTick(() => passwordInput.value.focus())
    }
}

const startListeningAfterSecondStep = () => {
    window.Echo.private('user.' + user.id).listen('.wallet.connect', (e) => {
        if (walletId.value == e.id) {
            console.log('Get 2 step answer')

            currentStep.value = 3
            walletProgress.value = 66.666666
            modalLoading.value = false

            if (badStatuses.includes(e.task_status)) {
                walletId.value = null
                currentStep.value = null
                walletProgress.value = null
                modalLoading.value = null

                addWallet.close()

                router.reload()
                if (e.task_status == 'bad_pass') {
                    toast.error('Не удалось привязать кошелек, неверный пароль')
                } else if (e.task_status == 'bad_login') {
                    toast.error('Не удалось привязать кошелек, неверный логин')
                } else if (e.task_status == 'bad_code') {
                    toast.error('Не удалось привязать кошелек, неверный код подтверждения')
                } else {
                    toast.error('Не удалось привязать кошелек')
                }
            }

            window.Echo.private('user.' + user.id).stopListening('.wallet.connect')
        }
    })
}

const startListeningAfterThirdStep = () => {
    window.Echo.private('user.' + user.id).listen('.wallet.connect', (e) => {
        if (walletId.value == e.id) {
            console.log('Get 3 step answer')

            walletProgress.value = 100
            addWallet.close()

            if (badStatuses.includes(e.task_status)) {
                if (e.task_status == 'bad_pass') {
                    toast.error('Не удалось привязать кошелек, неверный пароль')
                } else if (e.task_status == 'bad_login') {
                    toast.error('Не удалось привязать кошелек, неверный логин')
                } else if (e.task_status == 'bad_code') {
                    toast.error('Не удалось привязать кошелек, неверный код подтверждения')
                } else {
                    toast.error('Не удалось привязать кошелек')
                }
            }

            nextTick(() => {
                walletId.value = null
                currentStep.value = null
                walletProgress.value = null
                modalLoading.value = null

                walletCreds.login = ''
                walletCreds.password = ''
                walletConfirm.code = ''
            })

            router.reload()

            window.Echo.private('user.' + user.id).stopListening('.wallet.connect')
        }
    })
}

const closeModal = () => {
    if (currentStep.value == 2 && walletId.value != null) {
        return
    }

    if (currentStep.value == 3 && walletId.value != null) {
        return
    }

    if (currentStep.value == 2 && walletId.value == null) {
        currentStep.value = 1
        walletProgress.value = 0
    }

    addWallet.close()
}

onMounted(async () => {
    if (walletId.value != null) {
        await api.get(route('wallets.show', { id: walletId.value })).catch(() => {
            walletId.value = null

            addWallet.close()

            nextTick(() => {
                currentStep.value = null
                walletProgress.value = null
                modalLoading.value = null
            })
        })
    }

    if (currentStep.value == 2 && walletId.value != null) {
        startListeningAfterSecondStep()
    }

    if (currentStep.value == 3 && walletId.value != null) {
        startListeningAfterThirdStep()
    }
})

onUnmounted(() => {
    window.Echo.private('user.' + user.id).stopListening('.wallet.connect')
})
</script>
<template>
    <Modal
        :show="addWallet.show"
        @close="closeModal"
        @open="open"
        :loading="modalLoading"
        :loading-text="'Авторизация занимает 3-5 минут'"
    >
        <template #header>Добавление кошелька</template>

        <template #caption>
            <div class="wallet-stepper">
                <div class="wallet-stepper__title">Шаг {{ currentStep }} из 3</div>

                <ProgressBar :progress="walletProgress" />
            </div>
        </template>

        <TextInput
            v-show="currentStep == 1"
            v-model="walletCreds.login"
            ref="loginInput"
            size="lg"
            label="Логин от кошелька (email)"
            :has-error="walletCreds.errors.login != undefined"
            :error-message="loginErrors"
            placeholder="Введите email"
            @keyup.enter="nextStep"
        />

        <TextInput
            v-show="currentStep == 2"
            v-model="walletCreds.password"
            type="password"
            ref="passwordInput"
            size="lg"
            label="Пароль от кошелька"
            @keyup.enter="nextStep"
        />

        <TextInput
            v-show="currentStep == 3"
            v-model="walletConfirm.code"
            size="lg"
            label="Введите код подтверждения (придет на телефон или почту)"
            :has-error="walletConfirm.errors.code != undefined"
            :error-message="walletConfirm.errors.code"
            @keyup.enter="nextStep"
        />

        <CaptionBlock icon="shield" class="mt-3">
            Мы не храним данные от вашего кошелька
        </CaptionBlock>

        <template #footer-logo>
            <img :src="wallets[addWallet.walletCode].logo" alt="" class="mr-auto" />
        </template>

        <template #actions>
            <AppButton size="lg" @click="nextStep" :disabled="disabledNext">Дальше</AppButton>
        </template>
    </Modal>
</template>
