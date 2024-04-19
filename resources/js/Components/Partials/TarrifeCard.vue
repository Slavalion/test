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
            <div>
                <p>{{ props.tarrif.quantity }} {{ $t(props.tarrif.task_type) }}</p>
                <p>{{ props.tarrif.task_type }}</p>
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
.tarrife-card {
    min-width: 254px;
    min-height: 260px;
    padding: 24px;
    background-color: #ffffff;
    border: 0.5px solid #e7eaf0;
    border-radius: 16px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;

    &__blueCard {
        background-color: #1665ff;
        color: white;
    }

    &__darkCard {
        background-color: #283b62;
        color: white;
    }
}
</style>
