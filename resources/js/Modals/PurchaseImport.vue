<script setup>
import { ref } from 'vue'
import { useToast } from 'vue-toastification'

import { useAxios } from '@/Composables/useAxios'

import { products } from '@/Store/purchases'
import { purchaseImport, purchaseSlide } from '@/modals.js'

import Modal from '@/Components/Modal.vue'
import AppButton from '@/Components/AppButton.vue'

const toast = useToast()
const api = useAxios()

const importExcel = ref(null)

const importPurchases = () => {
    let data = new FormData()
    data.append('file', importExcel.value)

    api.post(route('purchase.import'), data).then((resp) => {
        products.splice(0, products.length)
        resp.data.purchases.forEach((element) => {
            products.push({ ...element, purchase_at: new Date(element.purchase_at) })
        })
        purchaseImport.close()
        purchaseSlide.open()
        toast.success('Выкупы имортированны из Excel!')
    })
}

const selectFile = (e) => {
    importExcel.value = e.target.files[0]
}
</script>
<template>
    <Modal
        :show="purchaseImport.show"
        @close="purchaseImport.close()"
        body-class="modal__body_generator"
    >
        <template #header> Импортировать из Excel </template>

        <div class="excel-import__step">
            <div class="excel-import__step-caption">1. Скачайте и заполните шаблон</div>

            <div class="grid grid-cols-2 gap-4">
                <a href="/files/import-template.xlsx" target="_blank" download>
                    <AppButton fillwidth theme="outline" size="lg" icon="file-download">
                        Шаблон (1 товар 1 день)
                    </AppButton>
                </a>
                <a href="/files/import-template-mass.xlsx" target="_blank" download>
                    <AppButton fillwidth theme="outline" size="lg" icon="file-download">
                        Шаблон (массовый)
                    </AppButton>
                </a>
            </div>

            <div class="text-xs pt-2 text-gray-500">
                * в массовом шаблоне адреса ПВЗ указываются на второй странице
            </div>
        </div>

        <div class="excel-import__step">
            <div class="excel-import__step-caption">2. Веберите или перетащите файл шаблона</div>

            <div class="input-dndfile">
                <input
                    type="file"
                    id="importExcel"
                    @change="selectFile"
                    class="input-dndfile__input"
                    accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                />

                <label for="importExcel" class="input-dndfile__label">
                    <div v-if="!importExcel" class="input-dndfile__label-caption">
                        Перетащите или выберите файл
                    </div>
                    <div v-else>
                        {{ importExcel.name }}
                    </div>
                </label>
            </div>
        </div>

        <template #actions>
            <AppButton size="lg" @click="importPurchases" :disabled="importExcel == null">
                Импортировать
            </AppButton>
        </template>
    </Modal>
</template>
