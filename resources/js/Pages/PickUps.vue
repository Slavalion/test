<script setup>
import { Head, router } from '@inertiajs/vue3'
import dayjs from 'dayjs'
import { computed, ref, watch } from 'vue'
import Modal from '@/Components/ModalMobileTariffs.vue'
import { currencyFormater } from '@/Helpers/formater'
import userData from '@/Store/userData'
import { fillUpBalance, pickUpImport } from '@/modals'
import device from 'vue3-device-detector'
import { useWindowSize } from '@vueuse/core'
import AppButton from '@/Components/AppButton.vue'
import AppPagination from '@/Components/AppPagination.vue'
import AppTable from '@/Components/AppTable.vue'
import EmptyState from '@/Components/EmptyState.vue'
import LabelText from '@/Components/LabelText.vue'
import TableTh from '@/Components/Table/TableTh.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PickUpImportModal from '@/Modals/PickUpImportModal.vue'
import AppIcon from '@/Components/AppIcon.vue'

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

const currentSection = ref(props.section)
const { width } = useWindowSize()
const isModalShowed = ref(false)
const mobileCurrentSection = ref('Ожидает обработки')

const actualSections = ['pending', 'process', 'done', 'all']

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

const nextSection = (section) => {
    setSection(section)
    isModalShowed.value = false
}

watch(
    () => currentSection.value,
    () => {
        if (currentSection.value === 'pending') {
            mobileCurrentSection.value = 'Ожидает обработки'
        } else if (currentSection.value === 'process') {
            mobileCurrentSection.value = 'В процессе'
        } else if (currentSection.value === 'done') {
            mobileCurrentSection.value = 'Выполненные '
        } else {
            mobileCurrentSection.value = 'Все'
        }
    }
)
</script>
<template>
    <Head>
        <title>Услуги по забору товара</title>
    </Head>

    <AuthenticatedLayout>
        <template #header>Услуги по забору товара</template>

        <div :class="device().isDesktop && width > 390 ? '-mt-4 mb-4' : 'mobile-pickup-danger'">
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

        <div v-if="!(device().isDesktop && width > 390)" class="input-wrapper">
            <div @click="isModalShowed = !isModalShowed" class="mobile-section-input">
                <p>{{ mobileCurrentSection }}</p>
                <AppIcon icon="chevron-down" />
            </div>
        </div>

        <div class="panel mb-6" v-if="device().isDesktop && width > 390">
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

        <div
            v-if="pickUps.length"
            :class="
                device().isDesktop && width > 390 ? 'panel panel_product' : 'mobile-pickup__body'
            "
        >
            <AppTable v-if="device().isDesktop && width > 390">
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
                        <div class="pl-6 flex justify-end">
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
                            <LabelText v-if="pickUp.status == -2" theme="danger">
                                Недостаточно средств
                            </LabelText>
                            <LabelText v-if="pickUp.status == -3" theme="danger">
                                Не найден адрес
                            </LabelText>
                            <LabelText v-if="pickUp.status == -4" theme="danger">
                                Адрес не поддерживается
                            </LabelText>
                        </div>
                    </td>
                </tr>
            </AppTable>

            <div v-else>
                <div v-for="pickUp in pickUps" :key="pickUp.id" class="mobile-pickup__item">
                    <div class="mobile-pickup__top">
                        <div class="mobile-pickup__top-header">
                            <div class="mobile-pickup__top-id">
                                <span class="mobile-pickup__top-span">id: </span>
                                {{ pickUp.product_id }}
                            </div>
                            <div class="mobile-pickup__top-phone">
                                <span class="mobile-pickup__top-tel">тел.: </span>{{ pickUp.phone }}
                            </div>
                        </div>
                        <div class="mobile-pickup__name">{{ pickUp.product.name }}</div>
                        <div class="mobile-pickup__adress">
                            {{ pickUp.address }}
                        </div>

                        <div class="mobile-pickup__pickup">
                            <span class="mobile-pickup__pickup-span">pickUp:&nbsp; </span>
                            # {{ pickUp.id }}
                            {{ dayjs(pickUp.created_at).format('D/M/YYYY h:m') }}
                        </div>
                    </div>
                    <div class="mobile-pickup__bottom">
                        <LabelText v-if="pickUp.status == 0" theme="info">
                            Ожидает обработки
                        </LabelText>
                        <LabelText v-if="pickUp.status == 1" theme="info"> В процессе </LabelText>
                        <LabelText v-if="pickUp.status == 2" theme="success"> Завершено </LabelText>
                        <LabelText v-if="pickUp.status == -1" theme="danger"> Не забран </LabelText>
                        <LabelText v-if="pickUp.status == -2" theme="danger">
                            Недостаточно средств
                        </LabelText>
                        <LabelText v-if="pickUp.status == -3" theme="danger">
                            Не найден адрес
                        </LabelText>
                        <LabelText v-if="pickUp.status == -4" theme="danger">
                            Адрес не поддерживается
                        </LabelText>
                    </div>
                </div>
            </div>

            <AppPagination :links="paginator" v-if="device().isDesktop && width > 390" />
        </div>
        <div
            v-else
            :class="
                device().isDesktop && width > 390
                    ? 'panel flex flex-col grow'
                    : 'mobile-pickup__empty'
            "
        >
            <EmptyState class="grow">
                <div class="header-5 mb-1.5">Заборов пока нет</div>
            </EmptyState>
        </div>
        <Modal
            :show="isModalShowed"
            :currentSection="currentSection"
            :sections="actualSections"
            @close="nextSection"
            @open="disableInput = true"
        />
    </AuthenticatedLayout>

    <PickUpImportModal />
</template>
