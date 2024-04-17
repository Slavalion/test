<script setup>
import { Head, router, usePage } from '@inertiajs/vue3'
import dayjs from 'dayjs'
import { ref } from 'vue'

import { actionSearchModal } from '@/modals'

import AppButton from '@/Components/AppButton.vue'
import AppTable from '@/Components/AppTable.vue'
import EmptyState from '@/Components/EmptyState.vue'
import LabelText from '@/Components/LabelText.vue'
import ProgressBar from '@/Components/ProgressBar.vue'
import TableTh from '@/Components/Table/TableTh.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import ActionSearchModal from '@/Modals/ActionSearchModal.vue'

const props = defineProps({
    actions: {
        type: Array,
        required: true,
    },
    section: {
        type: String,
        required: true,
    },
})

const page = usePage()

const currentSection = ref(props.section)

const setSection = (section) => {
    currentSection.value = section

    router.reload({
        only: ['actions'],
        data: { section: currentSection.value },
    })
}
</script>
<template>
    <Head>
        <title>Поиск по запросу</title>
    </Head>

    <AuthenticatedLayout>
        <template #header>Поиск по запросу</template>

        <div class="panel mb-6">
            <div class="flex gap-1.5">
                <AppButton
                    theme="normal"
                    :class="{ btn_selected: 'process' == currentSection }"
                    @click="setSection('process')"
                >
                    В процессе
                </AppButton>
                <AppButton
                    theme="normal"
                    :class="{ btn_selected: 'done' == currentSection }"
                    @click="setSection('done')"
                >
                    Выполненные
                </AppButton>
                <AppButton
                    theme="normal"
                    :class="{ btn_selected: 'all' == currentSection }"
                    @click="setSection('all')"
                >
                    Все
                </AppButton>

                <div class="ml-auto flex gap-3">
                    <AppButton size="md" @click="actionSearchModal.open()"> Добавить </AppButton>
                </div>
            </div>
        </div>

        <div v-if="actions.length" class="panel panel_product">
            <AppTable>
                <template #head>
                    <tr>
                        <TableTh class="text-left">Товар</TableTh>
                        <TableTh class="text-left">Артикул</TableTh>
                        <TableTh class="text-left">Прогресс</TableTh>
                        <TableTh class="text-left"></TableTh>
                    </tr>
                </template>

                <tr v-for="action in actions" :key="action.id" class="main-table__tr">
                    <td>
                        <div>{{ action.product.name }}</div>
                        <div class="text-xs text-gray-400">
                            #{{ action.id }} от
                            {{ dayjs(action.created_at).format('D/M/YYYY h:m') }}
                        </div>
                    </td>
                    <td>
                        <div class="pr-6">
                            {{ action.product_id }}
                        </div>
                    </td>
                    <td>
                        <div class="w-[234px]">
                            <div>{{ action.progress }} из {{ action.total }}</div>
                            <ProgressBar :progress="(action.progress / action.total) * 100" />
                        </div>
                    </td>
                    <td>
                        <div class="pl-6 flex justify-end">
                            <LabelText v-if="action.status == 1" theme="info">В процессе</LabelText>
                            <LabelText v-else theme="success">Завершено</LabelText>
                        </div>
                    </td>
                </tr>
            </AppTable>
        </div>
        <div v-else class="panel flex flex-col grow">
            <EmptyState class="grow">
                <div class="header-5 mb-1.5">Задач пока нет</div>
            </EmptyState>
        </div>
    </AuthenticatedLayout>

    <ActionSearchModal />
</template>
