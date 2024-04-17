<script setup>
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

import AppButton from '@/Components/AppButton.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import TextareaInput from '@/Components/Inputs/TextareaInput.vue'
import { useAxios } from '@/Composables/useAxios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const api = useAxios()

const requestData = ref('')

const taskID = ref('')
const purchase = ref({})

const getByTaskID = () => {
    api.get('/admin/dev/get-by-task-id', { task_id: taskID.value }).then(
        (resp) => (purchase.value = resp.data)
    )
}
</script>
<template>
    <Head>
        <title>DEV Раздел</title>
    </Head>

    <AuthenticatedLayout>
        <template #header>DEV Раздел</template>

        <div class="grid grid-cols-1 gap-4">
            <div class="panel panel_p-lg">
                <div class="flex gap-2">
                    <div class="grow">
                        <TextInput v-model="taskID" />
                    </div>
                    <AppButton @click="getByTaskID">Отправить</AppButton>
                </div>
                <div>
                    <pre>{{ purchase }}</pre>
                </div>
            </div>

            <div class="panel panel_p-lg">
                <TextInput v-model="requestData" />
                <TextareaInput v-model="requestData" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
