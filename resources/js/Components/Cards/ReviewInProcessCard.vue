<script setup>
import { WbHelperImage } from '@/wbHelper'
import LabelText from '@/Components/LabelText.vue'

defineOptions({
    inheritAttrs: false,
})

const props = defineProps({
    review: {
        type: Object,
        default: {},
    },
})

const stars = [1, 2, 3, 4, 5]
</script>

<template>
    <div class="mobile-review__mainInfo">
        <div class="mobile-review__item">
            <div class="product__image mobile-review__item-image">
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
                            WbHelperImage.constructHostV2(review.purchase?.product?.remote_id) +
                            '/images/tm/1.webp'
                        "
                        alt=""
                        width="30"
                        height="40"
                    />
                </a>
            </div>
            <div class="mobile-review__item-info-proc">
                <div class="mobile-review__item-info-top">
                    <div class="mobile-review__item-info-code">
                        #{{ review.purchase?.group_id }}-{{ review.purchase?.id }} TID#{{
                            review.purchase?.task_id
                        }}

                        <!-- {{ review.purchase?.product?.remote_id ? 'code: ' : '' }}
                    <span>{{ review.purchase?.product?.remote_id }}</span> -->
                    </div>
                    <div class="product__address">
                        <div class="product-review__rating mobile-review__rating">
                            <svg
                                v-for="star in stars"
                                :key="star"
                                :class="{
                                    'product-review__rating-star_active': star <= review.rate,
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
                    </div>
                </div>
                <div class="mobile-review__item-info-bottom">
                    <div class="mobile-review__item-info-code">
                        <a
                            :href="
                                'https://www.wildberries.ru/catalog/' +
                                review.purchase?.product?.remote_id +
                                '/detail.aspx'
                            "
                            target="_blank"
                        >
                            {{ review.purchase?.product?.name }}
                        </a>
                    </div>
                    <div class="mobile-review__item-info-code">
                        {{ review.purchase?.product?.remote_id ? 'code: ' : '' }}
                        <span>{{ review.purchase?.product?.remote_id }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="mobile-review__statusInfo">
            <div>
                <div class="mobile-review__statusInfo-text">Опубликовано:</div>
                <div class="mobile-review__item-info-code">
                    {{ review.public_ts }}
                </div>
            </div>
            <div>
                <LabelText
                    v-if="review.status == 'process' || review.status == 'processing'"
                    theme="info"
                >
                    В работе
                </LabelText>
                <LabelText v-if="review.status == 'failed'" theme="danger"> Ошибка </LabelText>
                <LabelText v-if="review.status == 'done'" theme="success"> Завершен </LabelText>
            </div>
        </div>
        <div class="mobile-review__commentInfo">
            <div class="mobile-review__commentInfo-text">Комментарий:</div>
            <div class="mobile-review__item-info-code">
                {{ review.review }}
            </div>
        </div>
    </div>
</template>
