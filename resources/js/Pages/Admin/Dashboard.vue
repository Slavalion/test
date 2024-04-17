<script setup>
import { Head } from '@inertiajs/vue3'

import DigitBlock from '@/Components/Dashboard/DigitBlock.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

defineProps({
    totals: {
        type: Object,
        required: true,
    },
    missedPurchases: {
        type: Array,
        required: true,
    },
})
</script>
<template>
    <Head>
        <title>Статистика</title>
    </Head>

    <AuthenticatedLayout>
        <template #header>Статистика</template>

        <div class="space-y-4">
            <div class="grid grid-cols-5 gap-4">
                <DigitBlock icon="setting" :digit="totals.purchases">выкупов</DigitBlock>
                <DigitBlock icon="setting" :digit="totals.reviews">отзывов</DigitBlock>
                <DigitBlock icon="setting" :digit="totals.reviewReactions">
                    реакций на отзывы
                </DigitBlock>
                <DigitBlock icon="setting" :digit="totals.reviewComplaints">
                    жалоб на отзывы
                </DigitBlock>
                <DigitBlock icon="setting" :digit="totals.cartActions">
                    добавлений в корзину
                </DigitBlock>
            </div>

            <div class="panel panel_p-lg">
                <div class="mb-4">Пропущенные выкупы</div>
                <div>
                    <pre v-for="purchase in missedPurchases" :key="purchase.id">{{ purchase }}</pre>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
