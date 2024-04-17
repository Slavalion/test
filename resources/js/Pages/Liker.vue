<script setup>
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { reactive } from 'vue'

const props = defineProps({
    phpVersion: {
        type: String,
        required: true,
    },
    result: {
        type: String,
        default: '',
    },
    tasks: {
        type: Array,
        default: () => [],
    },
})

const state = reactive({
    tasks: props.tasks,
    query: '',
    error: '',
})

// console.log(state)

const form = useForm({
    link: '',
    count: '',
    product_id: '',
    period: '',
    keywords: '',
})

window.Echo.channel('tasks').listen('TaskProgressUpdate', (e) => {
    const task = e.task
    let localTask = state.tasks.find((el) => {
        return el.id == task.id
    })
    // console.log(localTask)
    localTask.progress = task.progress
})
</script>

<template>
    <Head title="Liker" />

    <AuthenticatedLayout>
        <template #header> Добавить задачу "Лайкер" </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-900">
                    <form @submit.prevent="form.post(route('liker.do'))" class="mt-6 space-y-6">
                        <div>
                            <InputLabel for="link" value="Ссылка" />

                            <TextInput
                                id="link"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.link"
                                required
                                autofocus
                                autocomplete="link"
                            />

                            <InputError class="mt-2" :message="form.errors.link" />
                        </div>

                        <div>
                            <InputLabel for="count" value="Количество лайков" />

                            <TextInput
                                id="count"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.count"
                                required
                            />

                            <InputError class="mt-2" :message="form.errors.count" />
                        </div>

                        <div>
                            <InputLabel for="product_id" value="Артикул" />

                            <TextInput
                                id="product_id"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.product_id"
                            />

                            <InputError class="mt-2" :message="form.errors.product_id" />
                        </div>

                        <div>
                            <InputLabel for="keywords" value="Ключевой запрос" />

                            <TextInput
                                id="keywords"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.keywords"
                            />

                            <InputError class="mt-2" :message="form.errors.keywords" />
                        </div>

                        <div>
                            <InputLabel for="period" value="Период" />

                            <select
                                class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full"
                                ref="select"
                                id="period"
                                v-model="form.period"
                            >
                                <option value="3" selected>3 часа</option>
                                <option value="12" selected>12 часов</option>
                                <option value="24" selected>1 день</option>
                                <option value="168" selected>1 неделя</option>
                                <option value="336" selected>2 недели</option>
                            </select>

                            <InputError class="mt-2" :message="form.errors.period" />
                        </div>

                        <div class="flex items-center gap-4">
                            <PrimaryButton :disabled="form.processing">Отправить</PrimaryButton>

                            <Transition
                                enter-from-class="opacity-0"
                                leave-to-class="opacity-0"
                                class="transition ease-in-out"
                            >
                                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">
                                    {{ result }}
                                </p>
                            </Transition>
                        </div>
                    </form>
                </div>

                <div
                    v-for="task in state.tasks"
                    :key="task"
                    class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-900"
                >
                    <div class="flex flex-col gap-2">
                        <span class="text-xl font-medium">{{ task.link }}</span>
                        <span class="text-xl font-medium"
                            >{{ task.progress }} / {{ task.count }}</span
                        >
                        <span
                            v-if="task.progress == task.count"
                            class="text-xl font-medium text-lime-600"
                            >Завершено</span
                        >
                        <span v-else class="text-xl font-medium text-amber-500">В процессе</span>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
