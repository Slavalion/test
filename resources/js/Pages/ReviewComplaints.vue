<script setup>
import { Head, router, usePage } from '@inertiajs/vue3'
import dayjs from 'dayjs'
import { onBeforeUnmount, ref } from 'vue'

import { useAxios } from '@/Composables/useAxios'

import { reviewComplaintSlide } from '@/modals'
import { wbFeedbacks, wbProduct } from '@/Store/wbReviews'

import AppButton from '@/Components/AppButton.vue'
import AppTable from '@/Components/AppTable.vue'
import EmptyState from '@/Components/EmptyState.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import LabelText from '@/Components/LabelText.vue'
import ProgressBar from '@/Components/ProgressBar.vue'
import TableTh from '@/Components/Table/TableTh.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import ReviewComplaintSlide from '@/Modals/ReviewComplaintSlide.vue'

const props = defineProps({
    reviewComplaints: {
        type: Array,
        required: true,
    },
    section: {
        type: String,
        required: true,
    },
})

const page = usePage()
const api = useAxios()

const currentSection = ref(props.section)
const productCode = ref('')

const getReviews = () => {
    api.post(route('review-reactions.search'), {
        product_code: productCode.value,
    }).then((resp) => {
        wbProduct.value = resp.data.product
        wbFeedbacks.value = resp.data.reviews.map((el) => {
            return {
                id: el.id,
                wbUserDetails: {
                    name: el.wbUserDetails.name,
                },
                productValuation: el.productValuation,
                rank: el.rank,
                createdDate: el.createdDate,
                text: el.text,
                period: {
                    key: 3,
                    name: '3 часа',
                },
                type: {
                    key: 'obscene_language',
                    name: 'Нецензурная лексика',
                },
                total: 0,
            }
        })
        reviewComplaintSlide.open()
    })
}

const setSection = (section) => {
    currentSection.value = section

    router.reload({
        only: ['reviewComplaints'],
        data: { section: currentSection.value },
    })
}

onBeforeUnmount(() => {
    wbProduct.value = []
    wbFeedbacks.value = []
})
</script>
<template>
    <Head>
        <title>Жалобы на отзывы</title>
    </Head>

    <AuthenticatedLayout>
        <template #header>Жалобы на отзывы</template>

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
                    <TextInput v-model="productCode" size="md" placeholder="Артикул товара" />
                    <AppButton size="md" :disabled="productCode == ''" @click="getReviews">
                        Добавить
                    </AppButton>
                </div>
            </div>
        </div>

        <div v-if="reviewComplaints.length" class="panel panel_product">
            <AppTable>
                <template #head>
                    <tr>
                        <TableTh class="text-left">Товар</TableTh>
                        <TableTh class="text-left">Артикул</TableTh>
                        <TableTh class="text-left">Жалобы</TableTh>
                        <TableTh class="text-left"></TableTh>
                    </tr>
                </template>

                <tr v-for="review in reviewComplaints" :key="review.id" class="main-table__tr">
                    <td>
                        <div>{{ review.product?.name }}</div>
                        <div class="text-xs text-gray-400">
                            ID{{ review.id }} от
                            {{ dayjs(review.created_at).format('D/M/YYYY H:m') }}
                        </div>

                        <template v-if="true">
                            <div>
                                Process:
                                <div class="flex gap-1 flex-wrap leading-none text-gray-300">
                                    <span
                                        v-for="item in review.review_complaints"
                                        :key="item.task_id"
                                    >
                                        {{ item.task_id }},
                                    </span>
                                </div>
                            </div>
                        </template>
                    </td>
                    <td>
                        <div class="pr-6">
                            {{ review.product_id }}
                        </div>
                    </td>
                    <td>
                        <div class="w-[234px]">
                            <div>{{ review.progress }} из {{ review.total }}</div>
                            <ProgressBar :progress="(review.progress / review.total) * 100" />
                        </div>
                    </td>
                    <td>
                        <div class="pl-6 flex justify-end">
                            <LabelText v-if="review.status == 1" theme="info">В процессе</LabelText>
                            <LabelText v-else theme="success">Завершено</LabelText>
                        </div>
                    </td>
                </tr>
            </AppTable>
        </div>

        <div v-else class="panel flex flex-col grow">
            <EmptyState class="grow">
                <div class="header-5 mb-1.5">Реакций на отзывы пока нет</div>
            </EmptyState>
        </div>

        <ReviewComplaintSlide />
    </AuthenticatedLayout>
</template>
