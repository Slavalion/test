<script setup>
import { Head, router } from '@inertiajs/vue3'
import { ref, watch, onMounted } from 'vue'
import AppIcon from '@/Components/AppIcon.vue'
import { currencyFormater } from '@/Helpers/formater'
import { addReview } from '@/modals'
import { WbHelperImage } from '@/wbHelper'
import Modal from '@/Components/ModalMobileReview.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import device from 'vue3-device-detector'
import { useWindowSize } from '@vueuse/core'
import AppButton from '@/Components/AppButton.vue'
import AppPagination from '@/Components/AppPagination.vue'
import EmptyState from '@/Components/EmptyState.vue'
import LabelText from '@/Components/LabelText.vue'
import AddReview from '@/Modals/AddReview.vue'
import ReviewCard from '@/Components/Cards/ReviewCard.vue'
import ReviewInProcessCard from '@/Components/Cards/ReviewInProcessCard.vue'

const props = defineProps({
    section: {
        type: [String, null],
        default: null,
    },
    availablePurchases: {
        type: Array,
        default: () => [],
    },
    reviews: {
        type: Array,
        default: () => [],
    },
    reviewsPaginator: {
        type: Array,
        default: () => [],
    },
})

const stars = [1, 2, 3, 4, 5]
const { width } = useWindowSize()
const isSmallScreen = ref(false)
const currentSection = ref(props.section)
const selectedPurchase = ref(0)
const isModalShowed = ref(false)
const mobileCurrentSection = ref('Доступные')
const actualSections = ['', 'processing', 'done']

const openAddReview = (purchaseIndex) => {
    selectedPurchase.value = purchaseIndex
    addReview.open()
}
const setSection = (section) => {
    currentSection.value = section

    router.reload({
        only: ['reviews', 'reviewsPaginator', 'availablePurchases'],
        data: {
            section: currentSection.value,
            page: 1,
        },
    })
}

const nextSection = (section) => {
    setSection(section)
    isModalShowed.value = false
}

watch(width, () => {
    isSmallScreen.value = !(device().isDesktop && width.value > 390)
})

watch(
    () => currentSection.value,
    () => {
        if (currentSection.value === 'processing') {
            mobileCurrentSection.value = ' В работе'
        } else if (currentSection.value === 'done') {
            mobileCurrentSection.value = 'Опубликованные'
        } else {
            mobileCurrentSection.value = 'Доступные'
        }
    }
)

onMounted(() => {
    isSmallScreen.value = !(device().isDesktop && width.value > 390)
})
</script>

<template>
    <Head>
        <title>Отзывы</title>
    </Head>

    <AuthenticatedLayout>
        <template #header>Отзывы</template>

        <div v-if="isSmallScreen" class="input-wrapper">
            <div @click="isModalShowed = !isModalShowed" class="mobile-section-input">
                <p>{{ mobileCurrentSection }}</p>
                <AppIcon icon="chevron-down" />
            </div>
        </div>

        <div class="panel mb-6" v-else>
            <div class="flex gap-1.5">
                <AppButton
                    theme="normal"
                    :class="{ btn_selected: null == currentSection }"
                    @click="setSection('')"
                >
                    Доступные
                </AppButton>
                <AppButton
                    theme="normal"
                    :class="{ btn_selected: 'processing' == currentSection }"
                    @click="setSection('processing')"
                >
                    В работе
                </AppButton>
                <AppButton
                    theme="normal"
                    :class="{ btn_selected: 'done' == currentSection }"
                    @click="setSection('done')"
                >
                    Опубликованные
                </AppButton>
            </div>
        </div>

        <template v-if="!currentSection">
            <div
                v-if="availablePurchases.length"
                :class="isSmallScreen ? 'mobile-review' : 'panel panel_product'"
            >
                <div v-if="!isSmallScreen" class="products-header products-header_deliveries">
                    <div class="products-header__product" style="flex: 0 0 52.1946564885%">
                        Товар
                    </div>
                    <div class="products-header__code">Артикул</div>
                    <div class="products-header__address">Доступно отзывов</div>
                </div>
                <div v-if="!isSmallScreen" class="product-list product-list_deliveries">
                    <div
                        v-for="(purchase, index) in availablePurchases"
                        :key="purchase.id"
                        class="product"
                    >
                        <div
                            class="product__product"
                            style="flex: 0 0 52.1946564885%; max-width: 52.1946564885%"
                        >
                            <div class="product__image">
                                <a
                                    :href="
                                        'https://www.wildberries.ru/catalog/' +
                                        purchase?.product?.remote_id +
                                        '/detail.aspx'
                                    "
                                    target="_blank"
                                >
                                    <img
                                        :src="
                                            WbHelperImage.constructHostV2(
                                                purchase?.product?.remote_id
                                            ) + '/images/tm/1.webp'
                                        "
                                        alt=""
                                        width="30"
                                        height="40"
                                    />
                                </a>
                            </div>
                            <div class="product__info">
                                <div class="product__info-title">
                                    <a
                                        :href="
                                            'https://www.wildberries.ru/catalog/' +
                                            purchase?.product?.remote_id +
                                            '/detail.aspx'
                                        "
                                        target="_blank"
                                    >
                                        {{ purchase?.product?.name }}
                                    </a>
                                </div>
                                <div class="product__info-price">
                                    {{ currencyFormater.format(purchase?.product?.price / 100) }}
                                </div>
                            </div>
                        </div>
                        <div class="product__code">
                            {{ purchase?.product?.remote_id }}
                        </div>
                        <div class="product__address">{{ purchase.total }} шт.</div>
                        <div class="product__actions">
                            <AppButton @click="openAddReview(index)">Оставить отзыв</AppButton>
                        </div>
                    </div>
                </div>

                <div v-else class="mobile-review__body">
                    <ReviewCard
                        v-for="(purchase, index) in availablePurchases"
                        :key="purchase.id"
                        class="mobile-review__item"
                        :purchase="purchase"
                        :index="index"
                        @openAddReview="openAddReview"
                    />
                </div>
            </div>

            <div v-else class="panel flex flex-col grow">
                <EmptyState class="grow">
                    <div
                        class="header-5 mb-1.5 text-center"
                        :class="isSmallScreen ? 'mobile-review__empty-title' : ''"
                    >
                        Доступных отзывов пока нет
                    </div>
                    <div
                        class="text-center paragraph-3"
                        :class="isSmallScreen ? 'mobile-review__empty-paragraph' : ''"
                    >
                        Чтобы добавить отзыв, <br />
                        заберите товар из ПВЗ
                    </div>
                </EmptyState>
            </div>
        </template>

        <template v-else>
            <template v-if="reviews.length">
                <div :class="isSmallScreen ? 'mobile-review' : 'panel panel_product'">
                    <div v-if="!isSmallScreen" class="products-header products-header_deliveries">
                        <div class="products-header__product">Товар</div>
                        <div class="products-header__code">Артикул</div>
                        <div class="products-header__review">Отзыв</div>
                        <div class="products-header__address">Дата публикации</div>
                    </div>
                    <div v-if="!isSmallScreen" class="product-list product-list_deliveries">
                        <div
                            v-for="review in reviews"
                            :key="review.id"
                            class="product"
                            style="align-items: flex-start"
                        >
                            <div class="product__product">
                                <div class="product__image">
                                    <a
                                        :href="
                                            'https://www.wildberries.ru/catalog/' +
                                            review.purchase?.product?.remote_id +
                                            '/detail.aspx'
                                        "
                                        target="_blank"
                                    >
                                        <img
                                            :src="
                                                WbHelperImage.constructHostV2(
                                                    review.purchase?.product?.remote_id
                                                ) + '/images/tm/1.webp'
                                            "
                                            alt=""
                                            width="30"
                                            height="40"
                                        />
                                    </a>
                                </div>
                                <div class="product__info">
                                    <div class="product__info-title">
                                        <a
                                            :href="
                                                'https://www.wildberries.ru/catalog/' +
                                                review.purchase?.product?.remote_id +
                                                '/detail.aspx'
                                            "
                                            target="_blank"
                                        >
                                            #TID{{ review.purchase?.task_id }}
                                            {{ review.purchase?.product?.name }}
                                        </a>
                                    </div>
                                    <div class="product__info-price">
                                        {{
                                            currencyFormater.format(
                                                review.purchase?.product?.price / 100
                                            )
                                        }}
                                    </div>
                                </div>
                            </div>
                            <div class="product__code">
                                {{ review.purchase?.product?.remote_id }}
                            </div>
                            <div class="product__review">
                                <div class="product-review">
                                    <div class="product-review__id">
                                        #{{ review.purchase?.group_id }}-{{
                                            review.purchase?.id
                                        }}
                                        TID#{{ review.purchase?.task_id }}
                                    </div>
                                    <div class="product-review__rating">
                                        <svg
                                            v-for="star in stars"
                                            :key="star"
                                            :class="{
                                                'product-review__rating-star_active':
                                                    star <= review.rate,
                                            }"
                                            class="product-review__rating-star"
                                            width="20"
                                            height="20"
                                            viewBox="0 0 20 20"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                id="Star 5"
                                                d="M9.07668 1.21993C9.41827 0.398635 10.5817 0.398636 10.9233 1.21993L12.9395 6.06735C13.0835 6.41358 13.4091 6.65015 13.7829 6.68012L19.0161 7.09966C19.9027 7.17074 20.2623 8.27725 19.5867 8.85592L15.5996 12.2713C15.3148 12.5153 15.1904 12.8981 15.2774 13.2628L16.4956 18.3695C16.702 19.2348 15.7607 19.9186 15.0016 19.455L10.5213 16.7184C10.2012 16.5229 9.79876 16.5229 9.47875 16.7184L4.9984 19.455C4.2393 19.9186 3.29805 19.2348 3.50444 18.3695L4.72257 13.2628C4.80958 12.8981 4.68521 12.5153 4.40042 12.2713L0.413277 8.85592C-0.26226 8.27725 0.0972674 7.17074 0.983922 7.09966L6.21712 6.68012C6.59091 6.65015 6.91652 6.41358 7.06052 6.06735L9.07668 1.21993Z"
                                                fill="#E7EAF0"
                                            />
                                        </svg>
                                    </div>
                                    <div class="product-review__review-title">Комментарий</div>
                                    <div class="product-review__review">
                                        {{ review.review }}
                                    </div>
                                </div>
                            </div>
                            <div class="product__address">
                                <div class="mb-1.5">{{ review.public_ts }}</div>
                                <div>
                                    <LabelText
                                        v-if="
                                            review.status == 'process' ||
                                            review.status == 'processing'
                                        "
                                        theme="info"
                                    >
                                        В работе
                                    </LabelText>
                                    <LabelText v-if="review.status == 'failed'" theme="danger">
                                        Ошибка
                                    </LabelText>
                                    <LabelText v-if="review.status == 'done'" theme="success">
                                        Завершен
                                    </LabelText>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="mobile-review__body">
                        <ReviewInProcessCard
                            v-for="review in reviews"
                            :review="review"
                            :key="review.id"
                        />
                    </div>
                </div>

                <AppPagination :links="reviewsPaginator" v-if="!isSmallScreen" />
            </template>

            <div v-else class="panel flex flex-col grow">
                <EmptyState class="grow">
                    <div
                        class="header-5 mb-1.5"
                        :class="isSmallScreen ? 'mobile-review__empty-title' : ''"
                    >
                        Отзывов пока нет
                    </div>
                </EmptyState>
            </div>
        </template>

        <AddReview
            v-if="availablePurchases.length"
            :model-value="availablePurchases[selectedPurchase]?.product_id"
        />
        <Modal
            :show="isModalShowed"
            currentSection="currentSection"
            :sections="actualSections"
            @close="nextSection"
            @open="disableInput = true"
        />
    </AuthenticatedLayout>
</template>
