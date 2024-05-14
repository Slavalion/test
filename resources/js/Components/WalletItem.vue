<script setup>
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'

import { useAxios } from '@/Composables/useAxios'

import walletStatuses from '@/Data/walletStatuses'
import device from 'vue3-device-detector'
import { useWindowSize } from '@vueuse/core'
import AppButton from './AppButton.vue'
import LabelText from './LabelText.vue'

const props = defineProps({
    wallet: {
        type: Object,
        required: true,
    },
})

const api = useAxios()
const { loading } = api
const { width } = useWindowSize()
const walletStatus = ref(walletStatuses[props.wallet.status])

const updateWallet = (wallet) => {
    wallet.is_updating = true

    api.post(
        route('wallets.update-balance', {
            wallets: [{ id: wallet.id }],
        })
    ).then(() => {})
}

const deleteWallet = (wallet) => {
    wallet.is_updating = true

    api.post(
        route('wallets.delete', {
            wallet: wallet.id,
        })
    ).then(() => {
        router.reload()
    })
}
</script>
<template>
    <div
        v-if="device().isDesktop && width > 390"
        class="wallet"
        :class="{ wallet_loading: wallet.is_updating }"
    >
        <div class="wallet__info">
            <div class="wallet__info-login">
                {{ wallet.name }}
            </div>
            <div class="wallet__info-balance">
                Баланс: {{ wallet.balance }} ₽ от {{ wallet.updated_ts }}
            </div>
        </div>

        <div v-if="walletStatus && walletStatus.code != 'READY'">
            <LabelText :theme="walletStatus.labelTheme">
                {{ walletStatus.title }}
            </LabelText>
        </div>

        <div v-if="walletStatuses.isUpdateAvailable(walletStatus.code)">
            <AppButton
                theme="normal"
                size="sm"
                icon="refresh"
                :disabled="loading"
                @click="updateWallet(wallet)"
            />
        </div>

        <div>
            <AppButton theme="normal" size="sm" icon="delete" @click="deleteWallet(wallet)" />
        </div>

        <div v-show="wallet.is_updating" class="wallet__loading-overlay">
            <div class="lds-ring">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>

    <div v-else class="mobile-wallet" :class="{ wallet_loading: wallet.is_updating }">
        <div class="mobile-wallet__top">
            <div class="wallet__info">
                <div class="wallet__info-login">
                    {{ wallet.name }}
                </div>
                <div class="wallet__info-balance">
                    Баланс: <span class="wallet__info-balance-sum"> {{ wallet.balance }}</span> ₽
                </div>
                <div class="wallet__info-date">от {{ wallet.updated_ts }}</div>
            </div>

            <div class="mobile-wallet__right">
                <div
                    v-if="walletStatuses.isUpdateAvailable(walletStatus.code)"
                    class="mobile-wallet__right-btn"
                >
                    <AppButton
                        theme="normal"
                        size="sm"
                        icon="refresh"
                        :disabled="loading"
                        @click="updateWallet(wallet)"
                    />
                </div>

                <div class="mobile-wallet__right-btn">
                    <AppButton
                        theme="normal"
                        size="sm"
                        icon="delete"
                        @click="deleteWallet(wallet)"
                    />
                </div>
            </div>

            <div v-show="wallet.is_updating" class="wallet__loading-overlay">
                <div class="lds-ring">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
        <div class="mobile-wallet__bottom">
            <div v-if="walletStatus && walletStatus.code != 'READY'">
                <LabelText :theme="walletStatus.labelTheme" class="mobile-wallet__bottom-label">
                    {{ walletStatus.title }}
                </LabelText>
            </div>
        </div>
    </div>
</template>
