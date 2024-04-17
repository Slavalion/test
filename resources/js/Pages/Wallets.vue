<script setup>
import { Head, usePage } from '@inertiajs/vue3'
import { onMounted, onUnmounted } from 'vue'

import walletsConfig from '@/Data/wallets'
import { addWallet } from '@/modals'

import AppButton from '@/Components/AppButton.vue'
import LabelText from '@/Components/LabelText.vue'
import WalletItem from '@/Components/WalletItem.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import AddWallet from '@/Modals/AddWallet.vue'

const props = defineProps({
    wallets: {
        type: Object,
        default: () => {},
    },
})

const page = usePage()

onMounted(() => {
    window.Echo.private('user.' + page.props.auth.user.id).listen('.wallet.update-balance', (e) => {
        const wallet = props.wallets.yoomoney.find((el) => el.id == e.id)

        if (wallet) {
            wallet.is_updating = false
            wallet.balance = e.balance
        }
    })
})

onUnmounted(() => {
    window.Echo.private('user.' + page.props.auth.user.id).stopListening('.wallet.update-balance')
})
</script>
<template>
    <Head>
        <title>Кошельки</title>
    </Head>

    <AuthenticatedLayout>
        <template #header>
            <span>Кошельки</span>
            <LabelText
                v-if="wallets.qiwi == 0 && wallets.yoomoney == 0"
                theme="warning"
                class="ml-4"
            >
                Добавьте кошельки, чтобы оплачивать выкупы
            </LabelText>
        </template>

        <div class="flex flex-col gap-4">
            <div
                v-for="(walletConfig, walletCode) in walletsConfig"
                :key="walletCode"
                class="panel panel_p-lg"
            >
                <div
                    class="wallet-header"
                    :class="{ 'wallet-header_disabled': walletConfig.disabled }"
                >
                    <img :src="walletConfig.logo" alt="" />

                    <LabelText v-if="walletConfig.disabled" theme="warning" class="ml-auto">
                        Временно недоступен
                    </LabelText>

                    <AppButton
                        theme="normal"
                        icon="plus-circle"
                        @click="addWallet.open(walletCode)"
                    >
                        Добавить
                    </AppButton>
                </div>

                <div
                    v-if="walletConfig.description"
                    v-html="walletConfig.description"
                    class="mt-2 text-sm"
                ></div>

                <WalletItem
                    v-for="wallet in wallets[walletCode]"
                    :key="wallet.id"
                    :wallet="wallet"
                />
            </div>
        </div>
        <div>
            <AddWallet />
        </div>
    </AuthenticatedLayout>
</template>
