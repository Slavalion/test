<script setup>
import AppButton from '@/Components/AppButton.vue'
import { WbHelperImage } from '@/wbHelper'
import { currencyFormater } from '@/Helpers/formater'

defineOptions({
    inheritAttrs: false,
})

const props = defineProps({
    purchase: {
        type: Object,
        default: {},
    },
    index: {
        type: Number,
        default: 0,
    },
})

const emit = defineEmits(['openAddReview'])
</script>

<template>
    <div class="mobile-review__item">
        <div class="product__image mobile-review__item-image">
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
                        WbHelperImage.constructHostV2(purchase?.product?.remote_id) +
                        '/images/tm/1.webp'
                    "
                    alt=""
                    width="30"
                    height="40"
                />
            </a>
        </div>
        <div class="mobile-review__item-info">
            <div class="mobile-review__item-info-top">
                <div class="product__info-price">
                    {{ currencyFormater.format(purchase?.product?.price / 100) }}
                </div>
                <div class="product__address">{{ purchase.total }} шт.</div>
            </div>
            <div class="mobile-review__item-info-bottom">
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
            <div class="mobile-review__item-info-code" v-if="purchase?.product?.remote_id">
                code: <span>{{ purchase?.product?.remote_id }}</span>
            </div>
        </div>

        <div class="mobile-review__item-addBtn">
            <AppButton @click="$emit('openAddReview', index)">Оставить отзыв</AppButton>
        </div>
    </div>
</template>
