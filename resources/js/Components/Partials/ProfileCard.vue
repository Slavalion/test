<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

import { fillUpBalance } from '@/modals'
import userData from '@/Store/userData'

import IconSettings from '@/Icons/Settings.vue'
import AppButton from '@/Components/AppButton.vue'

const page = usePage()

const formatter = new Intl.NumberFormat('ru-RU', {
    style: 'currency',
    currency: 'RUB',
    // maximumFractionDigits: 3,
})

userData.balance = page.props.auth.user.balance / 100
userData.wallets = page.props.auth.wallets

const balance = computed(() => {
    return formatter.format(userData.balance).replace(',00', '')
})

const userEmail = computed(() => {
    if (page.props.auth.user.email.length > 20) {
        return page.props.auth.user.email.slice(0, 20) + '...'
    }

    return page.props.auth.user.email
})

const animateValue = (start, end, duration) => {
    let startTimestamp = null

    const step = (timestamp) => {
        if (!startTimestamp) {
            startTimestamp = timestamp
        }

        const progress = Math.min((timestamp - startTimestamp) / duration, 1)

        userData.balance = Math.floor(progress * (end - start) + start)

        if (progress < 1) {
            window.requestAnimationFrame(step)
        }
    }

    window.requestAnimationFrame(step)
}

window.Echo.private('user.' + page.props.auth.user.id).listen('.balance.update', (e) => {
    animateValue(userData.balance, e.balance, 500)
    fillUpBalance.close()
})
</script>
<template>
    <div class="profile-card">
        <div class="flex justify-between mb-3">
            <div class="profile-card__email">
                {{ userEmail }}
            </div>
            <div class="profile-card__settings">
                <Link :href="route('profile.edit')">
                    <IconSettings width="20" height="20"></IconSettings>
                </Link>
            </div>
        </div>

        <div class="profile-card__balance">
            Баланс:
            {{ balance }}
        </div>

        <div class="profile-card__fillup">
            <AppButton fillwidth size="lg" @click="fillUpBalance.open()" icon="arrow-up">
                Пополнить баланс
            </AppButton>
        </div>
    </div>
</template>
