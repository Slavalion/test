<script setup>
import { Head, router } from '@inertiajs/vue3'
import dayjs from 'dayjs'
import { computed, ref } from 'vue'

import { currencyFormater } from '@/Helpers/formater'
import userData from '@/Store/userData'
import { fillUpBalance, pickUpImport } from '@/modals'

import AppButton from '@/Components/AppButton.vue'
import AppPagination from '@/Components/AppPagination.vue'
import AppTable from '@/Components/AppTable.vue'
import EmptyState from '@/Components/EmptyState.vue'
import LabelText from '@/Components/LabelText.vue'
import TableTh from '@/Components/Table/TableTh.vue'
import { useAxios } from '@/Composables/useAxios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PickUpImportModal from '@/Modals/PickUpImportModal.vue'

const props = defineProps({
    pickUps: {
        type: Array,
        required: true,
    },
    section: {
        type: String,
        required: true,
    },
    paginator: {
        type: Array,
        required: true,
    },
    pendingTotalSum: {
        type: Number,
        required: true,
    },
})

const api = useAxios()
const { loading } = api

const currentSection = ref(props.section)

const balanceDelta = computed(() => {
    return props.pendingTotalSum / 100 - userData.balance
})

const setSection = (section) => {
    currentSection.value = section

    router.reload({
        only: ['pickUps', 'paginator', 'section'],
        data: { section: currentSection.value, page: 1 },
    })
}

const removePickUp = (pickUpID) => {
    api.post(route('pick-ups.destroy', { pickUp: pickUpID })).then(() => router.reload())
}
</script>
<template>
    <Head>
        <title>Услуги по забору товара</title>
    </Head>

    <AuthenticatedLayout>
        <template #header>Услуги по забору товара</template>

        <div class="-mt-4 mb-4">
            <LabelText theme="danger"> Заявки принимаются с 00:00 до 10:55 текущего дня </LabelText>
        </div>

        <div v-if="balanceDelta > 0" class="flex mb-4">
            <LabelText theme="danger">
                У вас недостаточно средств для проведения заборов (не хватает
                {{ currencyFormater.format(balanceDelta) }})
            </LabelText>

            <AppButton theme="outline" size="sm" class="ml-4" @click="fillUpBalance.open()">
                Пополнить баланс
            </AppButton>
        </div>

        <div class="panel mb-6">
            <div class="flex gap-1.5">
                <AppButton
                    theme="normal"
                    :class="{ btn_selected: 'pending' == currentSection }"
                    @click="setSection('pending')"
                >
                    Ожидает обработки
                </AppButton>
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
                    <AppButton size="md" @click="pickUpImport.open()"> Импорт XLS </AppButton>
                </div>
            </div>
        </div>

        <div v-if="pickUps.length" class="panel panel_product">
            <AppTable>
                <template #head>
                    <tr>
                        <TableTh class="text-left">Товар</TableTh>
                        <TableTh class="text-left">Артикул</TableTh>
                        <TableTh class="text-left"></TableTh>
                    </tr>
                </template>

                <tr v-for="pickUp in pickUps" :key="pickUp.id" class="main-table__tr">
                    <td>
                        <div>{{ pickUp.product.name }}</div>
                        <div class="text-xs text-gray-400">
                            {{ pickUp.address }}
                        </div>
                        <div class="text-xs text-gray-400">
                            {{ pickUp.phone }}
                        </div>
                        <div class="text-xs text-gray-400">
                            #{{ pickUp.id }}
                            {{ dayjs(pickUp.created_at).format('D/M/YYYY h:m') }}
                        </div>
                    </td>
                    <td>
                        <div class="pr-6">
                            {{ pickUp.product_id }}
                        </div>
                    </td>
                    <td>
                        <div class="pl-6 flex gap-2 justify-end">
                            <LabelText v-if="pickUp.status == 0" theme="info">
                                Ожидает обработки
                            </LabelText>
                            <LabelText v-if="pickUp.status == 1" theme="info">
                                В процессе
                            </LabelText>
                            <LabelText v-if="pickUp.status == 2" theme="success">
                                Завершено
                            </LabelText>
                            <LabelText v-if="pickUp.status == -1" theme="danger">
                                Не забран
                            </LabelText>
                            <LabelText v-if="pickUp.status == -10" theme="danger">
                                Не забран (ошибка при получении)
                            </LabelText>
                            <LabelText v-if="pickUp.status == -2" theme="danger">
                                Недостаточно средств
                            </LabelText>
                            <LabelText v-if="pickUp.status == -3" theme="danger">
                                Не найден адрес
                            </LabelText>
                            <LabelText v-if="pickUp.status == -4" theme="danger">
                                Адрес не поддерживается
                            </LabelText>
                            <AppButton
                                v-if="pickUp.status == 0"
                                size="sm"
                                icon="delete"
                                theme="danger"
                                @click="removePickUp(pickUp.id)"
                            />
                        </div>
                    </td>
                </tr>
            </AppTable>

            <AppPagination :links="paginator" />
        </div>
        <div v-else class="panel flex flex-col grow">
            <EmptyState class="grow">
                <div class="header-5 mb-1.5">Заборов пока нет</div>
            </EmptyState>
        </div>
    </AuthenticatedLayout>

    <PickUpImportModal />
</template>
