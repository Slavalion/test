<script setup>
import { watch, computed } from 'vue'
import AppButton from '@/Components/AppButton.vue'
import { fillUpBalance } from '@/modals'

const props = defineProps(['tarrif', 'keyID'])

const formatter = new Intl.NumberFormat('ru-RU')

const oldSum = computed(() => {
    return formatter.format(Math.round(+props.tarrif.price_old * +props.tarrif.quantity))
})

const newSum = computed(() => {
    return formatter.format(Math.round(+props.tarrif.price_single * +props.tarrif.quantity))
})
</script>
<template>
    <div
        :class="
            props.keyID === 1
                ? 'tarrife-card tarrife-card__blueCard'
                : props.keyID === 5
                  ? 'tarrife-card tarrife-card__darkCard'
                  : 'tarrife-card '
        "
    >
        <div>
            <h4>{{ $t(props.tarrif.type) }}</h4>
        </div>

        <div>
            <div class="tarrife-card__info">
                <h1>{{ props.tarrif.quantity }} {{ $t(props.tarrif.task_type) }}</h1>
                <h5>{{ props.tarrif.price_old }}&#8381; за штуку</h5>
                <h5 v-if="+props.tarrif.price_old > +props.tarrif.price_single">
                    <span class="oldSum">
                        {{ oldSum }}
                    </span>
                    &nbsp;
                    <span class="newSum"> {{ newSum }} &#8381; в месяц </span>
                </h5>
                <h5 v-else>{{ newSum }} &#8381; в месяц</h5>
            </div>

            <div class="tarrife-card__buyIt">
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
