<script setup>
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { useToast } from 'vue-toastification'

import { useAxios } from '@/Composables/useAxios'

import { pickUpImport } from '@/modals.js'

import AppButton from '@/Components/AppButton.vue'
import Modal from '@/Components/Modal.vue'

const toast = useToast()
const api = useAxios()

const importExcel = ref(null)

const importPickUps = () => {
    let data = new FormData()
    data.append('file', importExcel.value)

    api.post(route('pick-ups.import'), data)
        .then((resp) => {
            pickUpImport.close()

            toast.success('Заборы имортированны из Excel!')

            const errors = resp.data.errors

            if (errors.product_not_found > 0) {
                toast.warning('Пропущено строк (не найден товар) ' + errors.product_not_found)
            }

            if (errors.empty_phone > 0) {
                toast.warning('Пропущено строк (нет телефона) ' + errors.empty_phone)
            }

            if (errors.empty_code > 0) {
                toast.warning('Пропущено строк (нет кода получения) ' + errors.empty_code)
            }

            if (errors.wrong_address > 0) {
                toast.warning('Пропущено строк (не верный адрес) ' + errors.wrong_address)
            }

            if (errors.empty_qr_code > 0) {
                toast.warning('Пропущено строк (отсутсвует qr код) ' + errors.empty_qr_code)
            }

            if (errors.missing_addresses) {
                let message = ''
                errors.missing_addresses.forEach((el) => {
                    message = `${el}; ${message}`
                })
                toast.warning(message)
            }

            router.reload()
        })
        .catch((error) => {
            if (error.response.data.error) {
                toast.error(error.response.data.error)
            }
        })
}

const selectFile = (e) => {
    importExcel.value = e.target.files[0]
}
</script>
<template>
    <Modal
        :show="pickUpImport.show"
        @close="pickUpImport.close()"
        body-class="modal__body_generator"
    >
        <template #header> Импортировать из Excel </template>

        <div class="excel-import__step">
            <div class="excel-import__step-caption">1. Скачайте и заполните шаблон</div>

            <a href="/files/import-pick-up-template.xlsx" target="_blank" download>
                <AppButton fillwidth theme="outline" size="lg" icon="file-download">
                    Скачать шаблон
                </AppButton>
            </a>
        </div>

        <div class="excel-import__step">
            <div class="excel-import__step-caption">2. Веберите или перетащиет файл шаблона</div>

            <div class="input-dndfile">
                <input
                    type="file"
                    id="importPickUpExcel"
                    @change="selectFile"
                    class="input-dndfile__input"
                    accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                />

                <label for="importPickUpExcel" class="input-dndfile__label">
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
            <AppButton size="lg" @click="importPickUps" :disabled="importExcel == null">
                Импортировать
            </AppButton>
        </template>
    </Modal>
</template>
