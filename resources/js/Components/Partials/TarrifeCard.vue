<script setup>
import { computed } from 'vue'
import AppButton from '@/Components/AppButton.vue'
import { fillUpBalance } from '@/modals'

const props = defineProps(['tariff', 'keyID'])

const formatter = new Intl.NumberFormat('ru-RU')

const oldSum = computed(() => {
    return formatter.format(Math.round(+props.tariff.price_old * +props.tariff.quantity))
})

const newSum = computed(() => {
    return formatter.format(Math.round(+props.tariff.price_single * +props.tariff.quantity))
})
</script>
<template>
    <div
        :class="
            props.keyID === 1
                ? 'tariffe-card tariffe-card__blueCard'
                : props.keyID === 5
                  ? 'tariffe-card tariffe-card__darkCard'
                  : 'tariffe-card '
        "
    >
        <div>
            <h4>{{ $t(props.tariff.type) }}</h4>
        </div>

        <div>
            <div class="tariffe-card__info">
                <h1>{{ props.tariff.quantity }} {{ $t(props.tariff.task_type) }}</h1>
                <h5>{{ props.tariff.price_old }}&#8381; за штуку</h5>
                <h5 v-if="+props.tariff.price_old > +props.tariff.price_single">
                    <span class="oldSum">
                        {{ oldSum }}
                    </span>
                    &nbsp;
                    <span class="newSum"> {{ newSum }} &#8381; в месяц </span>
                </h5>
                <h5 v-else>{{ newSum }} &#8381; в месяц</h5>
            </div>

            <div class="tariffe-card__buyIt">
                <AppButton
                    :theme="props.keyID === 1 || props.keyID === 5 ? 'outline' : 'active'"
                    fillwidth
                    size="lg"
                    @click="fillUpBalance.open()"
                >
                    Купить
                </AppButton>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.oldSum {
    text-decoration: line-through;
}
</style>
