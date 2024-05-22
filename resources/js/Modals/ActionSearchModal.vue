<script setup>
import { router } from '@inertiajs/vue3'
import { computed, reactive, ref } from 'vue'

import { useToast } from 'vue-toastification'

import { useAxios } from '@/Composables/useAxios'

import { feedbackPeriods } from '@/Data/purchase'
import { currencyFormater } from '@/Helpers/formater'
import { actionSearchModal } from '@/modals.js'
import { WbHelperImage } from '@/wbHelper'

import AppButton from '@/Components/AppButton.vue'
import AppTable from '@/Components/AppTable.vue'
import QuantityInput from '@/Components/Inputs/QuantityInput.vue'
import SelectInput from '@/Components/Inputs/SelectInput.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import LabelText from '@/Components/LabelText.vue'
import ModalSlide from '@/Components/ModalSlide.vue'
import TableTh from '@/Components/Table/TableTh.vue'

const toast = useToast()
const api = useAxios()
const { loading } = api
const actionPrice = 5

const actions = reactive([])

const productCode = ref('')
const validate = () => {
    let valid = true

    actions.forEach((action) => {
        if (!action.keywords) {
            action.errors.keywords = 'Укажите запрос'
            valid = false
        } else {
            action.errors.keywords = null
        }
    })

    return valid
}

const disableSearchInput = ref(false)
const searchProduct = () => {
    api.post(route('search.do'), {
        product_code: productCode.value,
    })
        .then((response) => {
            disableSearchInput.value = true
            actions.push({
                product_code: '',
                view_time: 1,
                period: {
                    key: 3,
                    name: '3 часа',
                },
                quantity: 1,
                keywords: '',
                product_code: response.data.product.remote_id,
                product: response.data.product,
                errors: {},
            })
        })
        .catch((error) => {})
}

const createAction = function () {
    if (!validate()) {
        return
    }

    api.post(route('actions.search.store'), {
        actions: actions,
        product_code: productCode.value,
    })
        .then(() => {
            router.reload()

            actions.splice(0, actions.length)
            disableSearchInput.value = false

            actionSearchModal.close()

            toast.success('Задача успешно добавлена!')
        })
        .catch((error) => {
            toast.error(error.response.data.message)
        })
}

const remove = (actionIndex) => {
    actions.splice(actionIndex, 1)
}

const clone = (actionIndex) => {
    actions.push({
        ...actions[actionIndex],
    })
}

const totalPrice = computed(() => {
    return actions.reduce((acc, val) => acc + val.quantity * actionPrice, 0)
})
</script>
<template>
    <ModalSlide
        header-class="modal__header_noborder modal__header_pbsm"
        body-class="modal__body_nopadding-top"
        wrapper-class="modal__wrapper_slide-review"
        :show="actionSearchModal.show"
        @close="actionSearchModal.close()"
    >
        <template #header> Поиск по запросу </template>

        <template #caption>
            <div class="flex items-center">
                <div>Укажите запрос по которому будет искаться товар</div>

                <div class="ml-auto flex gap-3">
                    <LabelText theme="info">Цена: {{ actionPrice }}₽/штука</LabelText>
                    <LabelText>Всего: {{ actions.length }}</LabelText>
                    <LabelText>К оплате: {{ currencyFormater.format(totalPrice) }} </LabelText>
                </div>
            </div>
        </template>

        <template #search>
            <TextInput
                v-model="productCode"
                placeholder="Введите артикул"
                wrapper-class="grow"
                size="lg"
                ref="searchInput"
                :disabled="disableSearchInput"
            />

            <AppButton size="lg" :disabled="productCode == ''" @click="searchProduct">
                Добавить
            </AppButton>
        </template>

        <AppTable>
            <template #head>
                <tr>
                    <TableTh class="text-left">Товар</TableTh>
                    <TableTh class="text-left">Ключевой запрос</TableTh>
                    <TableTh class="text-center">Время просмотра</TableTh>
                    <TableTh class="text-center">Количество</TableTh>
                    <TableTh class="text-left">Период</TableTh>
                </tr>
            </template>

            <tr v-for="(action, index) in actions" :key="action.id" class="main-table__tr">
                <td>
                    <div class="flex items-center gap-1 w-80">
                        <div class="product__image">
                            <img
                                :src="
                                    WbHelperImage.constructHostV2(action.product.remote_id) +
                                    '/images/tm/1.webp'
                                "
                                alt=""
                                width="30"
                                height="40"
                            />
                        </div>
                        <div class="product__info">
                            <div class="product__info-title">
                                {{ action.product.name }}
                            </div>
                            <div class="product__info-price">
                                {{ currencyFormater.format(action.product.price) }}
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <TextInput
                        v-model="action.keywords"
                        size="sm"
                        :has-error="action.errors?.keywords != null"
                        :error-message="action.errors?.keywords"
                    />
                </td>
                <td>
                    <div class="flex justify-center">
                        <QuantityInput v-model="action.view_time" :min="1" />
                    </div>
                </td>
                <td>
                    <div class="flex justify-center">
                        <QuantityInput v-model="action.quantity" :min="1" />
                    </div>
                </td>
                <td>
                    <div class="flex items-center gap-1">
                        <SelectInput v-model="action.period" :items="feedbackPeriods" size="md" />
                        <div class="w-10"></div>
                        <AppButton theme="normal" size="sm" icon="copy" @click="clone(index)" />
                        <AppButton theme="normal" size="sm" icon="delete" @click="remove(index)" />
                    </div>
                </td>
            </tr>
        </AppTable>

        <template #actions>
            <AppButton size="lg" :disable="loading" @click="createAction">Отправить</AppButton>
        </template>
    </ModalSlide>
</template>
