<script setup>
import { router, usePage } from '@inertiajs/vue3'
import { computed, nextTick, ref, onMounted } from 'vue'

import { useAxios } from '@/Composables/useAxios'

import AppButton from '@/Components/AppButton.vue'
import DatePicker from '@/Components/Inputs/DatePicker.vue'
import QuantityInput from '@/Components/Inputs/QuantityInput.vue'
import SelectInput from '@/Components/Inputs/SelectInput.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import LabelText from '@/Components/LabelText.vue'
import ModalSlide from '@/Components/ModalSlide.vue'
import PurchaseTotals from '@/Components/Partials/PurchaseTotals.vue'

import LocationSelectorModal from '@/Modals/LocationSelector.vue'
import { fillUpBalance, locationSelector, purchaseSlide } from '@/modals.js'

import CheckboxInput from '@/Components/Inputs/CheckboxInput.vue'
import { genders } from '@/Data/purchase.js'
import { currencyFormater } from '@/Helpers/formater.js'
import { products } from '@/Store/purchases'
import userData from '@/Store/userData'
import { WbHelperImage } from '@/wbHelper.js'

const page = usePage()
const api = useAxios()

const searchInput = ref(null)
const productCode = ref('')
const loading = ref(false)
const isPro = ref(false)
const isTestMode = ref(false)

const paymentType = ref({
    id: 'yoomoney',
    name: 'Yoomoney',
})

const open = () => {
    nextTick(() => searchInput.value.focus())
}

const userHasTgPayment = computed(
    () => usePage().props.auth.user.preferences?.paymentChatId != null
)
const userIsAdmin = ref(usePage().props.auth.user.role == 1)

const searchHasError = ref(false)
const searchErrorMessage = ref('')

const productsHasSizes = computed(() => {
    return products.filter((el) => el.sizes.length).length > 0
})

const priceTotalComputed = computed(() => {
    return products.reduce(
        (accumulator, product) => accumulator + product.price * product.quantity,
        0
    )
})

const priceServiceComputed = computed(() => {
    if ([21, 30, 51, 61].includes(page.props.auth.user.id)) {
        return products.length * 45
    }

    if ([36, 38, 41, 54, 85, 86, 107, 157].includes(page.props.auth.user.id)) {
        return products.length * 40
    }

    return products.length * page.props.prices.purchase
})

const walletsTotal = computed(() => {
    return userData.wallets.reduce((accumulator, wallet) => accumulator + wallet.balance, 0)
})

const enoughBalance = computed(() => {
    return userData.balance > priceServiceComputed.value || paymentType.value.id == 'telegram'
})

const hasWallets = computed(() => {
    return userData.wallets.length > 0 || paymentType.value.id == 'telegram'
})

const notEnoughWalletBalance = computed(() => {
    return walletsTotal.value < priceTotalComputed.value && paymentType.value.id == 'yoomoney'
})

const searchProduct = () => {
    api.post(route('search.do'), {
        product_code: productCode.value,
    })
        .then((response) => {
            products.push({
                ...response.data.product,
                // purchase_at: response.data.product.purchase_at,
            })
            searchHasError.value = false
        })
        .catch((error) => {
            searchHasError.value = true
            searchErrorMessage.value = error.response.data.message
        })
}

const remove = (productIndex) => {
    products.splice(productIndex, 1)
}

const clone = (productIndex) => {
    products.push({
        ...products[productIndex],
    })
}

const validate = () => {
    let valid = true

    products.forEach((product) => {
        if (product.keywords == '') {
            valid = false
            product.errors.keywords = 'Укажите запрос'
        } else {
            delete product.errors.keywords
        }

        if (product.address == '') {
            valid = false
            product.errors.address = 'Укажите адрес'
        } else {
            delete product.errors.address
        }

        if (product.purchase_at == null) {
            valid = false
            product.errors.purchase_at = 'Укажите дату'
        } else {
            delete product.errors.purchase_at
        }
    })

    return valid
}

const createPurchase = () => {
    loading.value = true
    if (!validate()) {
        loading.value = false
        return
    }

    api.post(route('purchase.add'), {
        products: products,
        payment_type: paymentType.value.id,
        type: isPro.value ? 'pro' : 'default',
        test_mode: isTestMode.value,
    }).then(() => {
        products.splice(0, products.length)
        searchInput.value = ''
        purchaseSlide.close()
        router.reload()
        loading.value = false
        isPro.value = false
        isTestMode.value = false
    })
}

const selectedProduct = ref(null)

const selectAddress = (product) => {
    selectedProduct.value = product
    locationSelector.open()
}

const onAdressSelected = (pickpoint) => {
    if (selectedProduct.value == null) {
        products.forEach((el) => {
            if (el.address == '') {
                el.address = pickpoint
            }
        })
    } else {
        selectedProduct.value.address = pickpoint
        selectedProduct.value = null
    }
}

onMounted(() => {
    if (document.getElementById('purchaseSladeInput')) {
        document.getElementById('purchaseSladeInput').type = 'number'
        console.log(document.getElementById('purchaseSladeInput').type)
    }
})
</script>
<template>
    <ModalSlide
        header-class="modal__header_noborder"
        body-class="modal__body_nopadding-top"
        :show="purchaseSlide.show"
        @close="purchaseSlide.close()"
        @open="open"
    >
        <template #header>
            <span>Создать выкуп</span>

            <LabelText v-if="!hasWallets" theme="danger" class="ml-4"> Нет кошельков </LabelText>

            <LabelText v-if="!enoughBalance" theme="danger" class="ml-4">
                Недостаточно средств
            </LabelText>

            <AppButton
                v-if="!enoughBalance"
                theme="outline"
                size="sm"
                class="ml-4"
                @click="fillUpBalance.open()"
            >
                Пополнить баланс
            </AppButton>

            <LabelText v-if="notEnoughWalletBalance" theme="warning" class="ml-4">
                На ваших кошельках недостаточно средств для выкупа
            </LabelText>
        </template>

        <template #caption>
            <PurchaseTotals
                :quantity="products.length"
                :total-price="priceTotalComputed"
                :total-service="priceServiceComputed"
            />
        </template>

        <template #search>
            <TextInput
                v-model="productCode"
                id="purchaseSladeInput"
                placeholder="Введите артикул"
                wrapper-class="grow"
                size="lg"
                ref="searchInput"
                :has-error="searchHasError"
                :error-message="searchErrorMessage"
            />
            <AppButton size="lg" :disabled="productCode == ''" @click="searchProduct">
                Добавить
            </AppButton>

            <div v-if="userIsAdmin" class="ml-auto flex gap-2">
                <CheckboxInput v-model:checked="isPro" :value="isPro">
                    Создать выкупы "PRO"
                </CheckboxInput>
                <CheckboxInput v-model:checked="isTestMode" :value="isTestMode">
                    Тестовый режим
                </CheckboxInput>
            </div>

            <div v-if="products.length > 0 && userHasTgPayment">
                <SelectInput
                    v-model="paymentType"
                    :items="[
                        { id: 'yoomoney', name: 'Yoomoney' },
                        { id: 'telegram', name: 'Telegram' },
                    ]"
                    size="lg"
                ></SelectInput>
            </div>
        </template>

        <div v-if="products.length == 0" class="empty-state">
            <div class="empty-state__image">
                <img src="/images/Search.svg" alt="Товары не добавлены" width="250" height="200" />
            </div>
            <div class="empty-state__info">
                <div class="empty-state__title">Товары не добавлены</div>
                <ul class="empty-state__list">
                    <li>Добавьте товары по артикулу</li>
                    <li>Заполните нужные поля</li>
                    <li>Создайте выкупы и следите за статусами</li>
                </ul>
            </div>
        </div>
        <div v-else>
            <div class="products-header products-header_slide">
                <div class="products-header__product">Товар</div>
                <div class="products-header__code">Артикул</div>
                <div class="products-header__quantity">Количество</div>
                <div class="products-header__gender">Пол</div>
                <div v-if="productsHasSizes" class="products-header__size">Размер</div>
                <div class="products-header__keywords">Поисковой запрос</div>
                <div class="products-header__purchase-at">Дата выкупа</div>
                <div class="products-header__address">
                    <span class="mr-1">Адрес</span>
                    <AppButton
                        icon="plus-circle"
                        size="sm"
                        theme="normal"
                        class="whitespace-nowrap"
                        @click="locationSelector.open(0)"
                    >
                        Добавить адрес
                    </AppButton>
                </div>
            </div>
            <div class="product-list product-list_slide">
                <div v-for="(product, index) in products" :key="product.id" class="product">
                    <div class="product__image">
                        <img
                            :src="
                                WbHelperImage.constructHostV2(product.remote_id) +
                                '/images/tm/1.webp'
                            "
                            alt=""
                            width="30"
                            height="40"
                        />
                    </div>

                    <div class="product__info">
                        <div class="product__info-title">
                            {{ product.name }}
                        </div>
                        <div class="product__info-price">
                            {{ currencyFormater.format(product.price) }}
                        </div>
                    </div>

                    <div class="product__code">
                        {{ product.remote_id }}
                    </div>

                    <div class="product__quantity">
                        <QuantityInput v-model="product.quantity" :min="1" :max="99" />
                    </div>

                    <div class="product__gender">
                        <SelectInput :items="genders" v-model="product.gender" />
                    </div>

                    <div v-if="productsHasSizes" class="product__size">
                        <SelectInput
                            :items="product.sizes"
                            v-model="product.size"
                            :has-error="product.errors.size != null"
                            :error-message="product.errors?.size"
                        />
                    </div>

                    <div class="product__keywords">
                        <TextInput
                            v-model="product.keywords"
                            size="sm"
                            :has-error="product.errors.keywords != null"
                            :error-message="product.errors?.keywords"
                        />
                    </div>

                    <div class="product__date">
                        <DatePicker
                            v-model="product.purchase_at"
                            :has-error="product.errors.purchase_at != null"
                            :error-message="product.errors?.purchase_at"
                        />
                    </div>

                    <div class="product__address">
                        <AppButton
                            v-if="product.address == ''"
                            icon="plus-circle"
                            size="sm"
                            :theme="product.errors.address == null ? 'normal' : 'danger'"
                            class="whitespace-nowrap"
                            @click="selectAddress(product)"
                        >
                            Добавить адрес
                        </AppButton>

                        <div v-else>
                            <span class="trancated" :title="product.address">
                                {{ product.address }}
                            </span>
                            <AppButton
                                theme="normal"
                                size="sm"
                                icon="edit"
                                @click="selectAddress(product)"
                            />
                        </div>
                    </div>

                    <div class="product__actions">
                        <AppButton theme="normal" size="sm" icon="copy" @click="clone(index)" />
                        <AppButton theme="normal" size="sm" icon="delete" @click="remove(index)" />
                    </div>
                </div>
            </div>
        </div>

        <template #actions>
            <AppButton
                size="lg"
                :disabled="
                    products.length == 0 ||
                    !enoughBalance ||
                    !hasWallets ||
                    loading ||
                    notEnoughWalletBalance
                "
                :loading="loading"
                @click="createPurchase"
            >
                Создать
            </AppButton>
        </template>
    </ModalSlide>

    <LocationSelectorModal v-if="purchaseSlide.show" @select-pickpoint="onAdressSelected" />
</template>
