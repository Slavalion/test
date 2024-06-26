<script setup>
import { Head, router } from '@inertiajs/vue3'
import { ref, reactive, watch } from 'vue'
import device from 'vue3-device-detector'
import { useWindowSize } from '@vueuse/core'
import { useAxios } from '@/Composables/useAxios'
import AppIcon from '@/Components/AppIcon.vue'
import { genders, statuses } from '@/Data/purchase'
import { purchaseSlide } from '@/modals'
import { WbHelperImage } from '@/wbHelper.js'
import ProgressBar from '@/Components/PurchaseProgressBar.vue'
import AppButton from '@/Components/AppButton.vue'
import AppPagination from '@/Components/AppPagination.vue'
import EmptyState from '@/Components/EmptyState.vue'
import LabelText from '@/Components/LabelText.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import Modal from '@/Components/ModalMobile.vue'

const props = defineProps({
    section: {
        type: String,
        default: 'processing',
    },
    purchaseGroups: {
        type: Array,
        default: () => [],
    },
    paginator: {
        type: Array,
        required: true,
    },
})

const api = useAxios()
const { width } = useWindowSize()
const currentSection = ref(props.section)
const isModalShowed = ref(false)
const disableInput = ref(false)
const mobileCurrentSection = ref('Активные')
const messageTitle = ref('Нет активных выкупов')

watch(
    () => currentSection.value,
    () => {
        if (currentSection.value === 'processing') {
            mobileCurrentSection.value = 'Активные'
            messageTitle.value = 'Нет активных выкупов'
        } else if (currentSection.value === 'done') {
            mobileCurrentSection.value = 'Завершенные'
            messageTitle.value = 'Нет завершёных выкупов'
        } else if (currentSection.value === 'unavailable') {
            mobileCurrentSection.value = ' Недоступные'
            messageTitle.value = 'Нет недоступных выкупов'
        } else {
            mobileCurrentSection.value = 'Все'
            messageTitle.value = 'Пока нет выкупов'
        }
    }
)

const setSection = (section) => {
    currentSection.value = section

    router.reload({
        only: ['purchaseGroups', 'paginator'],
        data: {
            section: currentSection.value,
            page: 1,
        },
    })
}

const deletePurchase = (purchaseId) => {
    api.post(route('purchases.destroy'), { purchase_id: purchaseId })
        .then((resp) => {
            router.reload({
                only: ['purchaseGroups'],
                data: {
                    section: currentSection.value,
                },
            })
        })
        .catch((error) => {
            alert(error.response.data.message)
        })
}

const purchaseGroupsItems = reactive(
    props.purchaseGroups.reduce((acc, curVal) => {
        return [
            ...acc,
            {
                ...curVal,
                collapsed: true,
                progress: 100,
            },
        ]
    }, [])
)

const nextSection = (nextSection) => {
    isModalShowed.value = false
    disableInput.value = false
    setSection(nextSection)
}
</script>

<template>
    <Head title="Liker" />

    <AuthenticatedLayout>
        <template #header> Выкупы </template>

        <div v-if="!(device().isDesktop && width > 390)" class="input-wrapper">
            <div @click="isModalShowed = !isModalShowed" class="mobile-section-input">
                <p>{{ mobileCurrentSection }}</p>
                <AppIcon icon="chevron-down" />
            </div>
        </div>

        <div v-if="device().isDesktop && width > 390" class="panel mb-6">
            <div class="flex gap-1.5">
                <AppButton
                    theme="normal"
                    :class="{ btn_selected: 'processing' == currentSection }"
                    @click="setSection('processing')"
                >
                    Активные
                </AppButton>
                <AppButton
                    theme="normal"
                    :class="{ btn_selected: 'done' == currentSection }"
                    @click="setSection('done')"
                >
                    Завершенные
                </AppButton>
                <AppButton
                    theme="normal"
                    :class="{ btn_selected: 'unavailable' == currentSection }"
                    @click="setSection('unavailable')"
                >
                    Недоступные
                </AppButton>
                <!-- <AppButton
                    theme="normal"
                    :class="{ btn_selected: 'not_enough_w_balance' == currentSection }"
                    @click="setSection('not_enough_w_balance')"
                >
                    Кошелек исчерпан
                </AppButton>
                <AppButton
                    theme="normal"
                    :class="{ btn_selected: 'pickpoint_overloaded' == currentSection }"
                    @click="setSection('pickpoint_overloaded')"
                >
                    ПВЗ перегружен
                </AppButton> -->
                <AppButton
                    theme="normal"
                    :class="{ btn_selected: 'all' == currentSection }"
                    @click="setSection('all')"
                >
                    Все
                </AppButton>

                <a
                    class="ml-auto"
                    :href="'/purchase/download?status=' + currentSection"
                    target="_blank"
                    download
                >
                    <AppButton>Скачать XLS</AppButton>
                </a>
            </div>
        </div>

        <template v-if="purchaseGroups.length > 0">
            <div
                v-for="group in purchaseGroupsItems"
                :key="group.id"
                :class="
                    device().isDesktop && width > 390
                        ? 'panel panel_product mb-6'
                        : 'mobile-purchasesGroup'
                "
            >
                <div class="purchase-group" v-if="device().isDesktop && width > 390">
                    <div class="purchase-group purchase-group__title">
                        <div
                            :class="{ 'rotate-180': !group.collapsed }"
                            class="transition-all"
                            @click="group.collapsed = !group.collapsed"
                        >
                            <AppIcon icon="chevron-down" />
                        </div>
                        <div class="purhcase-group__title">
                            #{{ group.id }} Выкуп от {{ group.created_ts }}
                        </div>
                        <div></div>
                        <LabelText>
                            Товаров:
                            {{ group.purchases.length }} шт.
                        </LabelText>
                        <LabelText>
                            Сумма выкупов:
                            {{ group.total_sum }} ₽
                        </LabelText>
                    </div>
                    <div class="purchase-group__progress">
                        <p>
                            Завершено
                            {{
                                group.purchases.filter((purchase) => purchase.status === 'done')
                                    .length
                            }}
                            из {{ group.purchases.length }}
                        </p>
                        <ProgressBar
                            :progress="
                                Math.round(
                                    (group.purchases.filter(
                                        (purchase) => purchase.status === 'done'
                                    ).length *
                                        100) /
                                        group.purchases.length
                                )
                            "
                        />
                    </div>
                </div>

                <div class="mobile-purchase-group" v-else>
                    <div class="mobile-purchase-group__title">
                        #{{ group.id }} Выкуп от {{ group.created_ts }}
                    </div>
                    <div class="mobile-purchase-group__body">
                        <div
                            :class="{ 'rotate-180': !group.collapsed }"
                            class="transition-all mobile-purchase-group__body__collapsed"
                            @click="group.collapsed = !group.collapsed"
                        >
                            <AppIcon icon="chevron-down" />
                        </div>
                        <div class="mobile-purchase-group__info">
                            <div class="mobile-purchase-group__progress">
                                <p>
                                    Завершено
                                    {{
                                        group.purchases.filter(
                                            (purchase) => purchase.status === 'done'
                                        ).length
                                    }}
                                    из {{ group.purchases.length }}
                                </p>
                                <ProgressBar
                                    :progress="
                                        Math.round(
                                            (group.purchases.filter(
                                                (purchase) => purchase.status === 'done'
                                            ).length *
                                                100) /
                                                group.purchases.length
                                        )
                                    "
                                />
                            </div>
                            <div class="mobile-purchase-group__info__text">
                                Товаров:
                                {{ group.purchases.length }} шт.
                            </div>
                            <div class="mobile-purchase-group__info__text">
                                Сумма выкупов:
                                {{ group.total_sum }} ₽
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="products-header products-header_deliveries"
                    v-show="group.collapsed"
                    v-if="device().isDesktop && width > 390"
                >
                    <div class="products-header__product">Товар</div>
                    <div class="products-header__code">Артикул</div>
                    <div class="products-header__quantity">Кол-во</div>
                    <div class="products-header__gender">Пол</div>
                    <div class="products-header__size">Размер</div>
                    <div class="products-header__keywords">Поисковой запрос</div>
                    <div class="products-header__address">
                        <span class="mr-1">Адрес ПВЗ</span>
                    </div>
                </div>
                <div class="product-list product-list_deliveries">
                    <div
                        v-if="device().isDesktop && width > 390"
                        v-for="purchase in group.purchases"
                        :key="purchase.id"
                        class="product"
                        v-show="group.collapsed"
                    >
                        <div class="product__product">
                            <div class="product__image">
                                <a
                                    :href="
                                        'https://www.wildberries.ru/catalog/' +
                                        purchase.product?.remote_id +
                                        '/detail.aspx'
                                    "
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    <img
                                        :src="
                                            WbHelperImage.constructHostV2(
                                                purchase.product?.remote_id
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
                                            purchase.product?.remote_id +
                                            '/detail.aspx'
                                        "
                                        target="_blank"
                                        rel="noopener noreferrer"
                                    >
                                        {{ purchase.task_id }} {{ purchase.product?.name }}
                                    </a>
                                </div>
                                <div class="product__info-price">
                                    <!-- {{ currencyFormater.format(purchase.product.price / 100) }} -->
                                    {{ purchase.purchase_ts }}
                                </div>
                            </div>
                        </div>
                        <div class="product__code">
                            {{ purchase.product?.remote_id }}
                        </div>
                        <div class="product__quantity">
                            {{ purchase.quantity }}
                        </div>
                        <div class="product__gender">
                            {{ genders[purchase.gender].name }}
                        </div>
                        <div class="product__size">
                            {{ purchase.size?.name ? purchase.size?.name : '---' }}
                        </div>
                        <div class="product__keywords">
                            <span class="font-bold" v-if="purchase.search_position > 0">
                                ({{ purchase.search_position }})
                            </span>
                            {{ purchase.keywords }}
                        </div>
                        <div class="product__address" :title="purchase.address">
                            {{ purchase.address }}
                        </div>
                        <div class="product__actions">
                            <LabelText :theme="statuses[purchase.status].theme">
                                {{ statuses[purchase.status].title }}
                            </LabelText>
                            <AppButton
                                v-if="purchase.status == 'processing'"
                                size="sm"
                                icon="delete"
                                theme="danger"
                                @click="deletePurchase(purchase.id)"
                            />
                        </div>
                    </div>
                    <div
                        v-else
                        v-for="purchase in group.purchases"
                        :key="'mobile-' + purchase.id"
                        class="mobile-product-card"
                        v-show="group.collapsed"
                    >
                        <div class="mobile-product-card__image">
                            <a
                                :href="
                                    'https://www.wildberries.ru/catalog/' +
                                    purchase.product?.remote_id +
                                    '/detail.aspx'
                                "
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                <img
                                    :src="
                                        WbHelperImage.constructHostV2(purchase.product?.remote_id) +
                                        '/images/tm/1.webp'
                                    "
                                    alt=""
                                    width="30"
                                    height="40"
                                />
                            </a>
                        </div>
                        <div class="mobile-product-card__info">
                            <div class="mobile-product-card__info__top">
                                <div class="product__code">
                                    <span class="product__code__text">Код:</span>
                                    {{ purchase.product?.remote_id }}
                                </div>
                                <div class="product__quantity">Кол-во: {{ purchase.quantity }}</div>
                            </div>
                            <div class="mobile-product-card__info__bottom">
                                <!-- <div class="product__quantity">Кол-во: {{ purchase.quantity }}</div> -->
                                <div class="product__actions">
                                    <LabelText :theme="statuses[purchase.status].theme">
                                        {{ statuses[purchase.status].title }}
                                    </LabelText>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <AppPagination :links="paginator" v-if="device().isDesktop && width > 390" />
        </template>

        <div
            v-if="purchaseGroups.length == 0"
            class="panel flex flex-col grow"
            :class="
                device().isDesktop && width > 390
                    ? 'panel flex flex-col grow'
                    : 'mobile-purchases-empty'
            "
        >
            <EmptyState class="grow">
                <template #title>{{ messageTitle }}</template>
                <template #image>
                    <img
                        src="/images/empty-purchases.svg"
                        alt="Нет активных выкупов"
                        width="250"
                        height="200"
                    />
                </template>

                <p>Создайте выкуп, чтобы начать продвигать товары в рейтинге</p>

                <AppButton icon="plus-circle" @click="purchaseSlide.open()"
                    >Создать выкуп</AppButton
                >
            </EmptyState>
        </div>

        <Modal
            :show="isModalShowed"
            :currentSection="currentSection"
            @close="nextSection"
            @open="disableInput = true"
        />
    </AuthenticatedLayout>
</template>

<style lang="scss" scoped>
.purchase-group {
    justify-content: space-between;

    &__progress {
        display: flex;
        flex-direction: column;
        width: 22.15%;

        p {
            font-size: 13px;
            line-height: 20px;
            letter-spacing: 0.22px;
        }
    }

    &__title {
        padding-bottom: 0;
    }
}

.transition-all {
    cursor: pointer;
}
</style>
