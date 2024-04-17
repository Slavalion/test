<script setup>
import { Head, router } from '@inertiajs/vue3'

import { useAxios } from '@/Composables/useAxios'

import { currencyFormater } from '@/Helpers/formater'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import AppTable from '@/Components/AppTable.vue'
import TableTh from '@/Components/Table/TableTh.vue'

defineProps({
    products: {
        type: Array,
        required: true,
    },
})

const api = useAxios()

const setProductDimensions = (product) => {
    api.post(route('admin.products.set-dimensions'), {
        product_id: product.id,
        dimensions: product.dimensions,
    }).then(() => {
        router.reload()
    })
}
</script>
<template>
    <Head>
        <title>Товары</title>
    </Head>

    <AuthenticatedLayout>
        <template #header>Товары</template>

        <div class="panel panel_p-lg">
            <AppTable>
                <template #head>
                    <tr>
                        <TableTh>ID</TableTh>
                        <TableTh>Артикул</TableTh>
                        <TableTh>Кошелек</TableTh>
                        <TableTh>Цена</TableTh>
                        <TableTh>Габариты</TableTh>
                    </tr>
                </template>

                <tr v-for="product in products" :key="product" class="main-table__tr">
                    <td class="text-center">{{ product.id }}</td>
                    <td class="text-center">{{ product.remote_id }}</td>
                    <td class="text-center">{{ product.name }}</td>
                    <td class="text-center">
                        {{ currencyFormater.format(product.price / 100) }}
                    </td>
                    <td class="text-center">
                        <div class="input-wrapper">
                            <div class="flex justify-center gap-2">
                                <div class="radio-group">
                                    <input
                                        id="smallSize"
                                        type="radio"
                                        :name="'gender' + product.id"
                                        value="1"
                                        v-model="product.dimensions"
                                        @change="setProductDimensions(product)"
                                        class=""
                                    />
                                    <label for="smallSize">Мал</label>
                                </div>
                                <div class="radio-group">
                                    <input
                                        id="largeSize"
                                        type="radio"
                                        :name="'gender' + product.id"
                                        value="2"
                                        v-model="product.dimensions"
                                        @change="setProductDimensions(product)"
                                        class=""
                                    />
                                    <label for="largeSize">Бол</label>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </AppTable>
        </div>
    </AuthenticatedLayout>
</template>
