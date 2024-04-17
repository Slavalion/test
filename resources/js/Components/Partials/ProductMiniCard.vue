<script setup>
import { currencyFormater } from '@/Helpers/formater'
import { WbHelperImage } from '@/wbHelper'

defineProps({
    product: {
        type: Object,
        required: true,
    },
    taskId: {
        type: String,
        default: '',
    },
})
</script>
<template>
    <div class="product-mini">
        <div class="product-mini__image">
            <a
                :href="'https://www.wildberries.ru/catalog/' + product.remote_id + '/detail.aspx'"
                target="_blank"
            >
                <img
                    :src="WbHelperImage.constructHostV2(product.remote_id) + '/images/tm/1.webp'"
                    alt=""
                    width="30"
                    height="40"
                />
            </a>
        </div>
        <div class="product-mini__info">
            <div class="product-mini__info-title">
                <a
                    :href="
                        'https://www.wildberries.ru/catalog/' + product.remote_id + '/detail.aspx'
                    "
                    target="_blank"
                >
                    {{ taskId }}
                    {{ product.name }}
                </a>
            </div>
            <div class="product-mini__info-price">
                {{ currencyFormater.format(product.price / 100) }}
            </div>
        </div>
    </div>
</template>
