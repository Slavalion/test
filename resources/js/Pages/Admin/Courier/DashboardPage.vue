<script setup>
import AppButton from '@/Components/AppButton.vue'
import AppPanel from '@/Components/AppPanel.vue'
import CheckboxInput from '@/Components/Inputs/CheckboxInput.vue'
import LabelText from '@/Components/LabelText.vue'
import { useAxios } from '@/Composables/useAxios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import dayjs from 'dayjs'
import { useToast } from 'vue-toastification'

defineProps({
    couriers: {
        type: Array,
        required: true,
    },
    orders: {
        type: Array,
        required: true,
    },
    deliveryStats: {
        type: Object,
        required: true,
    },
    pickUpStats: {
        type: Object,
        required: true,
    },
    warningDeliveries: {
        type: Object,
        required: true,
    },
})

const api = useAxios()
const toast = useToast()

const setPreference = (courier) => {
    api.post(route('admin.courier.set-preference', { courier: courier.id }), {
        is_active_courier: courier.preferences.is_active_courier,
    }).then(() => {
        toast.success('Статус активности изменен')
    })
}
</script>
<template>
    <Head>
        <title>Статистика</title>
    </Head>

    <AuthenticatedLayout>
        <template #header>Статистика (сегодня)</template>

        <div class="grid gap-8">
            <div class="grid grid-cols-2 gap-8">
                <AppPanel padding="lg">
                    <div class="mb-2 font-bold text-xl">Заборы</div>

                    <div class="divide-y">
                        <div class="flex justify-between py-2">
                            <div>Всего</div>
                            <div>{{ deliveryStats.total }} шт.</div>
                        </div>
                        <div class="flex justify-between py-2">
                            <div>Не обработано</div>
                            <div>{{ deliveryStats.PENDING }} шт.</div>
                        </div>
                        <div class="flex justify-between py-2">
                            <div>Забрано</div>
                            <div>{{ deliveryStats.PICKED_UP }} шт.</div>
                        </div>
                        <div class="flex justify-between py-2">
                            <div>Не смогли забрать</div>
                            <div>{{ deliveryStats.FAILURE }} шт.</div>
                        </div>
                    </div>
                </AppPanel>

                <AppPanel padding="lg">
                    <div class="mb-2 font-bold text-xl">Заборы (загруженные)</div>

                    <div class="divide-y">
                        <div class="flex justify-between py-2">
                            <div>Всего</div>
                            <div>{{ pickUpStats.total }} шт.</div>
                        </div>
                        <div class="flex justify-between py-2">
                            <div>Не обработано</div>
                            <div>{{ pickUpStats.PENDING }} шт.</div>
                        </div>
                        <div class="flex justify-between py-2">
                            <div>Забрано</div>
                            <div>{{ pickUpStats.PICKED_UP }} шт.</div>
                        </div>
                        <div class="flex justify-between py-2">
                            <div>Не смогли забрать</div>
                            <div>{{ pickUpStats.FAILURE }} шт.</div>
                        </div>
                    </div>
                </AppPanel>
            </div>

            <AppPanel v-if="warningDeliveries.length > 0" padding="lg">
                <div class="mb-2 font-bold text-xl">ВЫКУПЫ СКОРО УЕДУТ</div>
                <div class="divide-y">
                    <div v-for="delivery in warningDeliveries" :key="delivery.id" class="py-2">
                        <div>Выкуп #{{ delivery.deliveryable.task_id }}</div>
                        <div class="flex justify-between">
                            <div>Выкуплен:</div>
                            <div>
                                {{
                                    dayjs(delivery.deliveryable.purchase_at).format(
                                        'D/M/YYYY hh:mm'
                                    )
                                }}
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <div>Доступен к забору с:</div>
                            <div>
                                {{
                                    dayjs(delivery.deliveryable.ready_to_pick_up_at).format(
                                        'D/M/YYYY hh:mm'
                                    )
                                }}
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <div>Текущий статус доставки (из выкупа):</div>
                            <div>{{ delivery.deliveryable.delivery_status }}</div>
                        </div>
                        <div class="flex justify-between">
                            <div>Текущий статус доставки (из курьерского списка):</div>
                            <div>
                                <template v-if="delivery.picked_up_status == 1"> Забран </template>
                                <template v-else-if="delivery.picked_up_status == 0">
                                    Не обработан
                                </template>
                                <template v-else-if="delivery.picked_up_status == -1">
                                    Отсутствует
                                </template>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <div>Адрес:</div>
                            <div>{{ delivery.pickpoint_address.address }}</div>
                        </div>
                    </div>
                </div>
            </AppPanel>

            <AppPanel padding="lg">
                <div class="mb-2 font-bold text-xl">Зоны</div>
                <div class="divide-y">
                    <div v-for="order in orders" :key="order.id" class="py-2 flex justify-between">
                        <div>
                            {{ order.pickpoint_zone?.name }}
                        </div>
                        <div>{{ order.livecargo_deliveries_count }} шт.</div>
                    </div>
                </div>
            </AppPanel>

            <AppPanel padding="lg">
                <div class="mb-2 font-bold text-xl">Курьеры</div>
                <div class="divide-y">
                    <div
                        v-for="courier in couriers"
                        :key="courier.id"
                        class="py-2 flex items-center gap-4"
                    >
                        <div class="w-56">
                            <div class="flex gap-2">
                                <span class="text-gray-500">#{{ courier.id }}</span>
                                <span>{{ courier.name }}</span>
                            </div>
                            <div class="text-gray-400 text-xs">{{ courier.email }}</div>
                        </div>

                        <div class="px-2">
                            <CheckboxInput
                                name="remember"
                                v-model:checked="courier.preferences.is_active_courier"
                                @change="setPreference(courier)"
                            >
                                Активен
                            </CheckboxInput>
                        </div>

                        <div class="ml-auto flex gap-2 items-center">
                            <span class="text-sm">Нажал "Забрано":</span>
                            <LabelText>
                                Наши выкупы: <b>{{ courier.total_main_wb_approved }}</b> |
                                {{ courier.total_main }} шт.
                            </LabelText>
                            <LabelText>
                                Загруженные QR коды: {{ courier.total_external }} шт.
                            </LabelText>
                            <LabelText theme="success" no-icon="">
                                Всего: {{ courier.total_picked_up }} шт.
                            </LabelText>
                        </div>
                        <div>
                            <a :href="`/admin/courier/${courier.id}/download-stat`" download>
                                <AppButton icon="excel-file" size="sm" theme="outline" />
                            </a>
                        </div>
                    </div>
                </div>
            </AppPanel>
        </div>
    </AuthenticatedLayout>
</template>
