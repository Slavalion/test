<script setup>
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppButton from '@/Components/AppButton.vue'
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

const sections = ['services', 'stuck', 'logistic', 'accounts']
const currentSection = ref('stuck')

const setSection = (section) => {
    currentSection.value = section
}
</script>
<template>
    <Head>
        <title>Статистика</title>
    </Head>

    <AuthenticatedLayout>
        <template #header>Статистика</template>

        <div class="panel mb-6">
            <div class="flex gap-1.5">
                <AppButton
                    v-for="section in sections"
                    theme="normal"
                    :class="{ btn_selected: section == currentSection }"
                    @click="setSection(section)"
                >
                    {{ $t(section + 'Title') }}
                </AppButton>
            </div>
        </div>

        <div class="space-y-4">
            <div class="grid grid-cols-5 gap-4">
                <DigitBlock icon="blue-purchase" :digit="totals.purchases">выкупов</DigitBlock>
                <DigitBlock icon="blue-review" :digit="totals.reviews">отзывов</DigitBlock>
                <DigitBlock icon="blue-like" :digit="totals.reviewReactions">
                    реакций на отзывы
                </DigitBlock>
                <DigitBlock icon="blue-question" :digit="totals.reviewComplaints">
                    жалоб на отзывы
                </DigitBlock>
                <DigitBlock icon="blue-star" :digit="totals.cartActions">
                    добавлений в корзину
                </DigitBlock>
            </div>

            <div class="panel panel_p-lg" v-if="currentSection === 'stuck'">
                <div class="mb-4">Пропущенные выкупы</div>
                <div>
                    <pre v-for="purchase in missedPurchases" :key="purchase.id">{{ purchase }}</pre>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
