<script setup>
import { Head } from '@inertiajs/vue3'

import AppTable from '@/Components/AppTable.vue'
import LabelText from '@/Components/LabelText.vue'
import ProductMiniCard from '@/Components/Partials/ProductMiniCard.vue'
import TableTh from '@/Components/Table/TableTh.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

defineProps({
    pickUpDeliveries: {
        type: Array,
        required: true,
    },
})
</script>
<template>
    <Head>
        <title>Livecargo</title>
    </Head>

    <AuthenticatedLayout>
        <template #header>Ден + Наташа = ❤️ ({{ pickUpDeliveries.length }})</template>

        <div class="panel panel_p-lg">
            <AppTable>
                <template #head>
                    <tr>
                        <TableTh align="left">IDs</TableTh>
                        <TableTh align="left">Товар</TableTh>
                        <TableTh>Адрес</TableTh>
                        <TableTh>Статус</TableTh>
                    </tr>
                </template>

                <tr v-for="delivery in pickUpDeliveries" :key="delivery" class="main-table__tr">
                    <td>
                        <div class="pr-2">
                            <div class="whitespace-nowrap">Забора {{ delivery.id }}</div>
                            <div class="whitespace-nowrap">
                                Выкупа {{ delivery.deliveryable.id }}
                            </div>
                            <div class="whitespace-nowrap">
                                Task ID {{ delivery.deliveryable.task_id }}
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="block">
                            <ProductMiniCard :product="delivery.deliveryable.product" />
                        </div>
                    </td>
                    <td class="text-center">{{ delivery.deliveryable.address }}</td>
                    <td class="text-center">
                        <div class="flex justify-end">
                            <LabelText v-if="delivery.picked_up_status == 0" theme="info">
                                В процессе
                            </LabelText>
                            <LabelText v-if="delivery.picked_up_status == 1" theme="success">
                                Забран
                            </LabelText>
                            <LabelText v-if="delivery.picked_up_status == -1" theme="danger">
                                Отстутсвует
                            </LabelText>
                        </div>
                    </td>
                </tr>
            </AppTable>
        </div>
    </AuthenticatedLayout>
</template>
