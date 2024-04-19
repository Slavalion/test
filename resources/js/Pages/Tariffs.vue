<script setup>
import { reactive, ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import TarrifeCard from '@/Components/Partials/TarrifeCard.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import AppButton from '@/Components/AppButton.vue'

const props = defineProps({
    tariffs: {
        type: Array,
        required: true,
    },
})

const currentSection = ref('purchase')

const setSection = (section) => {
    currentSection.value = section
}

const tariffsItems = reactive(
    props.tariffs.reduce((acc, curVal) => {
        return [
            ...acc,
            {
                ...curVal,
            },
        ]
    }, [])
)

const actualSection = reactive(
    tariffsItems.reduce((acc, curVal) => {
        if (acc.includes(curVal.task_type)) {
            return acc
        }
        return [...acc, curVal.task_type]
    }, [])
)
</script>
<template>
    <Head title="Tariffs" />

    <AuthenticatedLayout>
        <template #header>Тарифы</template>

        <div class="panel mb-6">
            <div class="flex flex-row gap-1.5">
                <AppButton
                    v-for="section in actualSection"
                    theme="normal"
                    :class="{ btn_selected: section == currentSection }"
                    @click="setSection(section)"
                >
                    {{ $t(section + 'Title') }}
                </AppButton>
            </div>
        </div>

        <div class="container flex flex-row">
            <div
                class="tarifCard"
                v-for="(tarrif, index) in tariffsItems.filter(
                    (tarrif) => tarrif.task_type === currentSection
                )"
            >
                <TarrifeCard :tariff="tarrif" :keyID="index" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style lang="scss" scoped>
/* we will explain what these classes do next! */
.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}

.container {
    width: 100%;
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    grid-template-rows: 260px;
    gap: 16px;
}

@media (max-width: 1000px) {
    .container {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 750px) {
    .container {
        grid-template-columns: 1fr;
    }
}
</style>
