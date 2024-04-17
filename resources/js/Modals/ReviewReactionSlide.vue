<script setup>
import { computed, ref } from 'vue'
import dayjs from 'dayjs'
import { useToast } from 'vue-toastification'
import { router } from '@inertiajs/vue3'

import { useAxios } from '@/Composables/useAxios'
import { reviewReactionSlide } from '@/modals.js'
import { wbProduct, wbFeedbacks } from '@/Store/wbReviews'
import { feedbackPeriods } from '@/Data/purchase'
import { currencyFormater } from '@/Helpers/formater'

import ModalSlide from '@/Components/ModalSlide.vue'
import LabelText from '@/Components/LabelText.vue'
import AppTable from '@/Components/AppTable.vue'
import TableTh from '@/Components/Table/TableTh.vue'
import SelectInput from '@/Components/Inputs/SelectInput.vue'
import AppButton from '@/Components/AppButton.vue'
import QuantityInput from '@/Components/Inputs/QuantityInput.vue'
import AppIcon from '@/Components/AppIcon.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'

const api = useAxios()

const reactionPrice = 8
const ratingStars = [1, 2, 3, 4, 5]

const reactions = computed(() => {
    return wbFeedbacks.value.filter((el) => el.total_likes > 0 || el.total_dislikes > 0)
})

const totalLikes = computed(() => {
    return reactions.value.reduce((acc, val) => acc + val.total_likes, 0)
})
const totalDislikes = computed(() => {
    return reactions.value.reduce((acc, val) => acc + val.total_dislikes, 0)
})
const totalPrice = computed(() => {
    return reactions.value.reduce(
        (acc, val) => acc + val.total_likes * reactionPrice + val.total_dislikes * reactionPrice,
        0
    )
})

const createReviewReactions = () => {
    const data = reactions.value.map((el) => {
        const reactionType = el.total_likes > 0 ? 'like' : 'dislike'

        return {
            id: el.id,
            period: el.period.key,
            type: reactionType,
            total: reactionType == 'like' ? el.total_likes : el.total_dislikes,
        }
    })

    api.post(route('review-reactions.store'), {
        product_code: wbProduct.value.id,
        reactions: data,
    })
        .then((resp) => {
            reviewReactionSlide.close()
            router.reload()
            useToast().success('Задачи успешно добавлены!')
        })
        .catch((err) => {
            useToast().error('Что-то пошло не так!')
        })
}

const open = () => {}

const sortTypes = [
    {
        key: 'date',
        name: 'По дате',
    },
    {
        key: 'valuation',
        name: 'По оценке',
    },
    {
        key: 'rank',
        name: 'По полезности',
    },
]

const search = ref('')
const sort = ref({
    key: 'rank',
    name: 'По полезности',
})

const order = ref('asc')

const wbFeedbacksFiltered = computed(() => {
    let sortedFeedbacks = wbFeedbacks.value

    if (sort.value.key == 'valuation') {
        sortedFeedbacks = sortedFeedbacks.sort((a, b) => {
            if (a.productValuation < b.productValuation) {
                return order.value == 'asc' ? -1 : 1
            }

            if (a.productValuation > b.productValuation) {
                return order.value == 'asc' ? 1 : -1
            }

            return 0
        })
    }

    if (sort.value.key == 'date') {
        sortedFeedbacks = sortedFeedbacks.sort((a, b) => {
            if (dayjs(a.createdDate).isSame(dayjs(b.createdDate))) {
                return 0
            }

            if (dayjs(a.createdDate).isBefore(dayjs(b.createdDate))) {
                return order.value == 'asc' ? -1 : 1
            }

            if (dayjs(a.createdDate).isAfter(dayjs(b.createdDate))) {
                return order.value == 'asc' ? 1 : -1
            }
        })
    }

    if (sort.value.key == 'rank') {
        sortedFeedbacks = sortedFeedbacks.sort((a, b) => {
            if (a.rank < b.rank) {
                return order.value == 'asc' ? 1 : -1
            }

            if (a.rank > b.rank) {
                return order.value == 'asc' ? -1 : 1
            }

            return 0
        })
    }

    if (search.value == '') {
        return sortedFeedbacks
    }

    return sortedFeedbacks.filter((el) => {
        return el.text.toLowerCase().match(search.value.toLowerCase()) ? true : false
    })
})

const setOrder = (orderVal) => {
    // console.log(order.value)
    order.value = orderVal
}
</script>
<template>
    <ModalSlide
        header-class="modal__header_noborder modal__header_pbsm"
        body-class="modal__body_nopadding-top"
        wrapper-class="modal__wrapper_slide-review"
        :show="reviewReactionSlide.show"
        @close="reviewReactionSlide.close()"
        @open="open"
    >
        <template #header>
            <span>Отзывы к "{{ wbProduct.name }}"</span>
        </template>

        <template #caption>
            <div class="flex items-center">
                <div>Укажите необходимое количество лайков и дизлайков к каждому отзыву.</div>

                <div class="ml-auto flex gap-3">
                    <LabelText theme="info">Цена: 8₽/штука</LabelText>
                    <LabelText>Лайков: {{ totalLikes }}</LabelText>
                    <LabelText>Дизлайков: {{ totalDislikes }}</LabelText>
                    <LabelText>К оплате: {{ currencyFormater.format(totalPrice) }}</LabelText>
                </div>
            </div>

            <div class="mt-6 flex gap-4">
                <div>
                    <TextInput v-model="search" size="md" icon="search" />
                </div>

                <div class="ml-auto flex gap-2">
                    <SelectInput v-model="sort" :items="sortTypes" size="md" />
                    <AppButton
                        theme="normal"
                        icon="sort"
                        :class="{ btn_selected: 'asc' == order, 'rotate-180': true }"
                        @click="setOrder('asc')"
                    />
                    <AppButton
                        theme="normal"
                        icon="sort"
                        :class="{ btn_selected: 'desc' == order }"
                        @click="setOrder('desc')"
                    />
                </div>
            </div>
        </template>

        <div v-if="wbFeedbacksFiltered.length == 0" class="empty-state">
            <div class="empty-state__image">
                <img src="/images/Search.svg" alt="Нет отзывов" width="250" height="200" />
            </div>
            <div class="empty-state__info">
                <div class="empty-state__title">Нет отзывов</div>
                <ul class="empty-state__list">
                    <li>Отзывы для данного товара не найдены</li>
                </ul>
            </div>
        </div>
        <div v-else>
            <AppTable>
                <template #head>
                    <tr>
                        <TableTh class="text-left">Отзыв</TableTh>
                        <TableTh class="text-left">Период добавления</TableTh>
                    </tr>
                </template>

                <tr v-for="review in wbFeedbacksFiltered" :key="review.id" class="main-table__tr">
                    <td class="align-top">
                        <div class="flex">
                            <div class="w-[256px]">
                                <div class="font-bold mb-2">
                                    <span v-if="review.wbUserDetails.name">
                                        {{ review.wbUserDetails.name }}
                                    </span>
                                    <span v-else="review.wbUserDetails.name"> Имя не указано </span>
                                </div>
                                <div class="flex gap-2 mb-2">
                                    <svg
                                        v-for="value in ratingStars"
                                        :key="value"
                                        :class="{
                                            'rating-input__star_active':
                                                review.productValuation >= value,
                                        }"
                                        class="rating-input__star w-4 h-4"
                                        width="40"
                                        height="39"
                                        viewBox="0 0 40 39"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            id="Star 5"
                                            d="M18.1534 1.43986C18.8365 -0.20273 21.1635 -0.202727 21.8466 1.43986L25.879 11.1347C26.167 11.8272 26.8182 12.3003 27.5658 12.3602L38.0322 13.1993C39.8055 13.3415 40.5245 15.5545 39.1734 16.7118L31.1992 23.5427C30.6296 24.0306 30.3808 24.7961 30.5549 25.5256L32.9911 35.7391C33.4039 37.4695 31.5214 38.8372 30.0032 37.9099L21.0425 32.4368C20.4025 32.0458 19.5975 32.0458 18.9575 32.4368L9.9968 37.9099C8.4786 38.8372 6.5961 37.4695 7.00887 35.7391L9.44515 25.5256C9.61916 24.7961 9.37042 24.0306 8.80084 23.5427L0.826553 16.7118C-0.52452 15.5545 0.194535 13.3415 1.96784 13.1993L12.4342 12.3602C13.1818 12.3003 13.833 11.8272 14.121 11.1347L18.1534 1.43986Z"
                                            fill="#E7EAF0"
                                        />
                                    </svg>
                                </div>
                                <div>
                                    {{
                                        dayjs(review.createdDate)
                                            .locale('ru')
                                            .format('DD/MM/YYYY HH:mm')
                                    }}
                                </div>
                            </div>
                            <div class="w-[420px]">
                                {{ review.text }}
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="flex">
                            <div class="w-[160px]">
                                <SelectInput
                                    v-model="review.period"
                                    :items="feedbackPeriods"
                                    size="md"
                                />
                            </div>
                            <div class="pl-8 w-[236px]">
                                <div class="flex items-cente relative mb-3">
                                    <div class="flex items-center pr-3">
                                        <AppIcon icon="like" class="w-4 h-4 mr-2" />
                                        <span class="w-[64px]">Лайки</span>
                                    </div>
                                    <QuantityInput v-model="review.total_likes" size="md" />

                                    <div
                                        v-show="review.total_dislikes > 0"
                                        class="absolute top-0 left-0 w-full h-full bg-white opacity-80"
                                    ></div>
                                </div>
                                <div class="flex items-center relative">
                                    <div class="flex items-center pr-3">
                                        <AppIcon icon="dislike" class="w-4 h-4 mr-2" />
                                        <span class="w-[64px]">Дизлайки</span>
                                    </div>
                                    <QuantityInput v-model="review.total_dislikes" size="md" />

                                    <div
                                        v-show="review.total_likes > 0"
                                        class="absolute top-0 left-0 w-full h-full bg-white opacity-80"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </AppTable>
        </div>

        <template #actions>
            <AppButton size="lg" :disabled="reactions.length == 0" @click="createReviewReactions">
                Добавить
            </AppButton>
        </template>
    </ModalSlide>
</template>
