<script setup>
import { watch } from 'vue'
import AppButton from '@/Components/AppButton.vue'
import { fillUpBalance } from '@/modals'

const props = defineProps(['tarrif', 'keyID'])

watch(props, () => {
    console.log(props)
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
                <h5>{{ props.tarrif.price_single }}&#8381; за штуку</h5>
                <h5>{{ +props.tarrif.price_single * +props.tarrif.quantity }}&#8381; в месяц</h5>
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

<style scoped lang="scss"></style>
