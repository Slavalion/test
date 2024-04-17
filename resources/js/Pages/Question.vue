<script setup>
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'

defineProps({
    result: {
        type: String,
        default: '',
    },
    questions: {
        type: Array,
        default: () => [],
    },
})

const form = useForm({
    product_id: '',
    gender: '',
    pub_date: '',
    question: '',
})
</script>

<template>
    <Head title="Liker" />

    <AuthenticatedLayout>
        <template #header>Вопросы</template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-900">
                    <form @submit.prevent="form.post(route('question.add'))" class="mt-6 space-y-6">
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
                            <InputLabel value="Пол" />

                            <div class="flex space-x-4">
                                <div class="flex items-center">
                                    <input
                                        id="genderMale"
                                        type="radio"
                                        value="1"
                                        v-model="form.gender"
                                        class="rounded-full cursor-pointer border-gray-300 text-cyan-600 shadow-sm focus:ring-cyan-600 mb-1"
                                    />

                                    <InputLabel
                                        for="genderMale"
                                        value="М"
                                        class="pl-2 cursor-pointer"
                                    />
                                </div>

                                <div class="flex items-center">
                                    <input
                                        id="genderFemale"
                                        type="radio"
                                        value="2"
                                        v-model="form.gender"
                                        class="rounded-full cursor-pointer border-gray-300 text-cyan-600 shadow-sm focus:ring-cyan-600 mb-1"
                                    />

                                    <InputLabel
                                        for="genderFemale"
                                        value="Ж"
                                        class="pl-2 cursor-pointer"
                                    />
                                </div>
                            </div>

                            <InputError class="mt-2" :message="form.errors.gender" />
                        </div>

                        <div>
                            <InputLabel for="pub_date" value="Дата публикации" />

                            <TextInput
                                id="pub_date"
                                type="datetime-local"
                                class="mt-1 block w-full"
                                v-model="form.pub_date"
                                required
                                autofocus
                                autocomplete="pub_date"
                            />

                            <InputError class="mt-2" :message="form.errors.pub_date" />
                        </div>

                        <div>
                            <InputLabel for="question" value="Вопрос" />

                            <TextInput
                                id="question"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.question"
                                required
                            />

                            <InputError class="mt-2" :message="form.errors.question" />
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
                    v-for="question in questions"
                    :key="question"
                    class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-900"
                >
                    <div class="flex flex-col gap-2 leading-tight">
                        <span class="text-xl font-medium"
                            >Вопрос к товару №{{ question.product_id }}</span
                        >
                        <span class="">Дата публикации: {{ question.pub_date }}</span>
                        <span>
                            Статус:
                            <span v-if="question.status == 'process'" class="text-amber-500">
                                ожидает</span
                            >
                            <span v-else class="text-lime-600"> завершен</span>
                        </span>
                        <span class="">{{ question.question }}</span>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>