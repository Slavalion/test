<script setup>
import { reactive } from 'vue'
import { useToast } from 'vue-toastification'

import { useAxios } from '@/Composables/useAxios'

import AppButton from '@/Components/AppButton.vue'
import SelectInput from '@/Components/Inputs/SelectInput.vue'
import TextareaInput from '@/Components/Inputs/TextareaInput.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

// defineProps({
//     purchases: {
//         type: Object,
//         required: true,
//     },
//     reviews: {
//         type: Object,
//         required: true,
//     },
// })

const api = useAxios()
const toast = useToast()
const { loading } = api

const userTypesList = [
    {
        key: 1,
        name: 'Админам',
    },
    {
        key: 0,
        name: 'Пользователям',
    },
]

const state = reactive({
    message: '',
    userType: userTypesList[0],
})

const sendMessage = async () => {
    if (state.message == '') {
        toast.error('Сообщение не может быть пустым')
        return
    }

    await api.post(route('admin.message-sender.send'), state).then((resp) => {
        toast.success('Рассылка запущена')
        state.message = ''
        state.userType = userTypesList[0]
    })
}
</script>
<template>
    <AuthenticatedLayout>
        <template #header>Рассылка</template>

        <div class="panel panel_p-lg">
            <form @submit.prevent="sendMessage" class="flex flex-col gap-3">
                <div class="grow">
                    <TextareaInput
                        v-model="state.message"
                        size="md"
                        label="Сообщение для отправки"
                        rows="10"
                    />
                </div>
                <div class="flex gap-2">
                    <SelectInput v-model="state.userType" :items="userTypesList" size="md" />
                    <AppButton :disabled="loading || state.message == ''" size="md">
                        Отправить
                    </AppButton>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
