<script setup>
import { usePage } from '@inertiajs/vue3'
import { computed, onMounted, onUnmounted, reactive, ref } from 'vue'

import AppIcon from '@/Components/AppIcon.vue'
import DeliveryBlock from '@/Components/Courier/DeliveryBlock.vue'

const props = defineProps({
    zone: {
        type: Object,
        required: true,
    },
    address: {
        type: Object,
        required: true,
    },
    deliveries: {
        type: Object,
        required: true,
    },
    completedDeliveries: {
        type: Object,
        required: true,
    },
    completedExternalDeliveries: {
        type: Object,
        required: true,
    },
    missedExternalDeliveries: {
        type: Object,
        required: true,
    },
    completedDeliveriesNotApproved: {
        type: Object,
        required: true,
    },
    totalDeliveries: {
        type: Number,
        required: true,
    },
})

const page = usePage()

const deliveriesState = reactive(props.deliveries)
const completedDeliveriesState = reactive(props.completedDeliveries)
const completedExternalDeliveriesState = reactive(props.completedExternalDeliveries)
const missedExternalDeliveriesState = reactive(props.missedExternalDeliveries)
const completedDeliveriesNotApprovedState = reactive(props.completedDeliveriesNotApproved ?? {})

const showCompleted = ref(false)
const showExternalCompleted = ref(false)
const showExternalMissed = ref(false)
const showCompletedNotApproved = ref(false)

const totalCompletedDeliveries = computed(() => {
    return Object.values(props.completedDeliveries).reduce((a, v) => a + v.length, 0)
})
const totalCompletedExternalDeliveries = computed(() => {
    return Object.values(props.completedExternalDeliveries).reduce((a, v) => a + v.length, 0)
})
const totalMissedExternalDeliveries = computed(() => {
    return Object.values(props.missedExternalDeliveries).reduce((a, v) => a + v.length, 0)
})
const totalCompletedDeliveriesNotApproved = computed(() => {
    return Object.values(completedDeliveriesNotApprovedState).reduce((a, v) => a + v.length, 0)
})

const onStatusUpdated = (e) => {
    if (deliveriesState[e.phone]) {
        const delivery = deliveriesState[e.phone].find((el) => el.id == e.deliveryId)

        if (delivery.type == 'mpbtop') {
            if (!Array.isArray(completedDeliveriesNotApprovedState[e.phone])) {
                completedDeliveriesNotApprovedState[e.phone] = []
            }

            completedDeliveriesNotApprovedState[e.phone].push(delivery)
        } else {
            if (e.status == 1) {
                if (!Array.isArray(completedExternalDeliveriesState[e.phone])) {
                    completedExternalDeliveriesState[e.phone] = []
                }

                completedExternalDeliveriesState[e.phone].push(delivery)
            } else if (e.status == -1) {
                if (!Array.isArray(missedExternalDeliveriesState[e.phone])) {
                    missedExternalDeliveriesState[e.phone] = []
                }

                missedExternalDeliveriesState[e.phone].push(delivery)
            }
        }

        if (deliveriesState[e.phone].length == 1) {
            delete deliveriesState[e.phone]
        } else {
            const deliveryIndex = deliveriesState[e.phone].findIndex((el) => el.id == e.deliveryId)
            deliveriesState[e.phone].splice(deliveryIndex, 1)
        }
    }
}

onMounted(() => {
    window.Echo.private('user.' + page.props.auth.user.id).listen(
        '.courier.delivery.update',
        (e) => {
            if (e.deliveryable.delivery_status != 'picked_up') {
                return
            }

            const deliveryPhone = e.deliveryable.delivery_phone

            if (!completedDeliveriesNotApprovedState[deliveryPhone]) {
                return
            }

            const delivery = completedDeliveriesNotApprovedState[deliveryPhone].find(
                (el) => el.id == e.id
            )

            if (!delivery) {
                return
            }

            if (!Array.isArray(completedDeliveriesState[deliveryPhone])) {
                completedDeliveriesState[deliveryPhone] = []
            }

            completedDeliveriesState[deliveryPhone].push(delivery)

            if (completedDeliveriesNotApprovedState[deliveryPhone].length == 1) {
                delete completedDeliveriesNotApprovedState[deliveryPhone]
            } else {
                const deliveryIndex = completedDeliveriesNotApprovedState[deliveryPhone].findIndex(
                    (el) => el.id == e.id
                )
                completedDeliveriesNotApprovedState[deliveryPhone].splice(deliveryIndex, 1)
            }
        }
    )
})

onUnmounted(() => {
    window.Echo.private('user.' + page.props.auth.user.id).stopListening('.courier.delivery.update')
})
</script>
<template>
    <div class="w-full mx-auto">
        <h1 class="text-4xl font-bold text-center py-8">{{ zone.name }}</h1>

        <div class="">
            <div class="text-xl p-2 bg-teal-900 rounded-lg text-white">
                {{ address.address }} ({{ totalDeliveries }} шт.)
            </div>

            <div class="px-2 my-2">
                <div
                    class="text-white bg-green-600 rounded px-2 py-2 flex justify-between"
                    @click="showCompleted = !showCompleted"
                >
                    <span>Забранные ({{ totalCompletedDeliveries }}шт.)</span>
                    <AppIcon
                        icon="chevron-down"
                        class="stroke-white"
                        :class="{ 'rotate-180': showCompleted }"
                    />
                </div>
                <div v-show="showCompleted">
                    <DeliveryBlock
                        v-for="(data, phone) in completedDeliveriesState"
                        :key="phone"
                        :delivery-data="data"
                        :delivery-phone="phone"
                    />
                </div>
            </div>

            <div class="px-2 my-2">
                <div
                    class="text-white bg-green-600 rounded px-2 py-2 flex justify-between"
                    @click="showExternalCompleted = !showExternalCompleted"
                >
                    <span>Забранные загруженные ({{ totalCompletedExternalDeliveries }}шт.)</span>
                    <AppIcon
                        icon="chevron-down"
                        class="stroke-white"
                        :class="{ 'rotate-180': showExternalCompleted }"
                    />
                </div>
                <div v-show="showExternalCompleted">
                    <DeliveryBlock
                        v-for="(data, phone) in completedExternalDeliveriesState"
                        :key="phone"
                        :delivery-data="data"
                        :delivery-phone="phone"
                    />
                </div>
            </div>

            <div class="px-2 my-2">
                <div
                    class="text-white bg-red-600 rounded px-2 py-2 flex justify-between"
                    @click="showExternalMissed = !showExternalMissed"
                >
                    <span>Не забранные загруженные ({{ totalMissedExternalDeliveries }}шт.)</span>
                    <AppIcon
                        icon="chevron-down"
                        class="stroke-white"
                        :class="{ 'rotate-180': showExternalMissed }"
                    />
                </div>
                <div v-show="showExternalMissed">
                    <DeliveryBlock
                        v-for="(data, phone) in missedExternalDeliveriesState"
                        :key="phone"
                        :delivery-data="data"
                        :delivery-phone="phone"
                    />
                </div>
            </div>

            <div class="px-2 my-2">
                <div
                    class="text-white bg-orange-600 rounded px-2 py-2 flex justify-between"
                    @click="showCompletedNotApproved = !showCompletedNotApproved"
                >
                    <span>
                        Забранные не подтвержденные ({{ totalCompletedDeliveriesNotApproved }}шт.)
                    </span>
                    <AppIcon
                        icon="chevron-down"
                        class="stroke-white"
                        :class="{ 'rotate-180': showCompletedNotApproved }"
                    />
                </div>
                <div v-show="showCompletedNotApproved">
                    <DeliveryBlock
                        v-for="(data, phone) in completedDeliveriesNotApprovedState"
                        :key="phone"
                        :delivery-data="data"
                        :delivery-phone="phone"
                    />
                </div>
            </div>

            <DeliveryBlock
                v-for="(data, phone) in deliveriesState"
                :key="phone"
                :delivery-data="data"
                :delivery-phone="phone"
                @status-updated="onStatusUpdated"
            />
        </div>
    </div>
</template>
