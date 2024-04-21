<script setup>
import { ref } from 'vue'
import AppButton from '@/Components/AppButton.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
//import TextInput from '@/Components/TextInput.vue'
import EmptyState from '@/Components/EmptyState.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import AppTable from '@/Components/AppTable.vue'
import dayjs from 'dayjs'
import TableTh from '@/Components/Table/TableTh.vue'
import LabelText from '@/Components/LabelText.vue'

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

const searchVendorCode = ref('')

const currentSection = ref('available')

const actualSection = ['available', 'published']

const setSection = (section) => {
    currentSection.value = section
}
</script>

<template>
    <Head title="Questions" />

    <AuthenticatedLayout>
        <template #header>Вопросы</template>

        <div class="panel mb-6">
            <div class="flex flex-row gap-1.5">
                <AppButton
                    v-for="section in actualSection"
                    theme="normal"
                    :class="{ btn_selected: section == currentSection }"
                    @click="setSection(section)"
                >
                    {{ $t(section + 'Title') }}
                </AppButton>

                <div class="ml-auto flex gap-3">
                    <TextInput
                        v-model="searchVendorCode"
                        size="md"
                        placeholder="Поиск по артикулу"
                    />
                </div>
            </div>
        </div>

        <div v-if="questions.length" class="panel panel_product grow">
            <AppTable>
                <template #head>
                    <tr>
                        <TableTh class="text-left">Товар</TableTh>
                        <TableTh class="text-left">Артикул</TableTh>
                        <TableTh class="text-left">Кол-во</TableTh>
                        <TableTh class="text-left">Пол</TableTh>
                        <TableTh class="text-left">Размер</TableTh>
                        <TableTh class="text-left">Поисковой запрос</TableTh>
                        <TableTh class="text-left">Доступно вопросов</TableTh>
                        <TableTh class="text-left"></TableTh>
                    </tr>
                </template>

                <tr v-for="question in questions" :key="question" class="main-table__tr">
                    <td>
                        <div>{{ question.product?.name }}</div>
                        <div class="text-xs text-gray-400">
                            ID{{ question.id }} от
                            {{ dayjs(question.created_at).format('D/M/YYYY h:m') }}
                        </div>

                        <template v-if="true">
                            <div>
                                Process:
                                <div class="flex gap-1 flex-wrap leading-none text-gray-300">
                                    <span
                                        v-for="item in question.question_reactions"
                                        :key="item.task_id"
                                    >
                                        {{ item.task_id }},
                                    </span>
                                </div>
                            </div>
                        </template>
                    </td>
                    <td>
                        <div class="pr-6">
                            {{ question.product_id }}
                        </div>
                    </td>
                    <td>
                        <div class="w-[234px]">
                            <div>{{ question.progress }} из {{ question.total }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="pl-6 flex justify-end">
                            <LabelText v-if="question.status == 1" theme="info"
                                >В процессе</LabelText
                            >
                            <LabelText v-else theme="success">Завершено</LabelText>
                        </div>
                    </td>
                </tr>
            </AppTable>

            <!-- <div
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
            </div> -->
        </div>

        <div v-else class="panel flex flex-col grow">
            <EmptyState class="grow growCenter">
                <div class="flex flex-col">
                    <div class="header-5 mb-1.5">Доступных вопросов пока нет</div>
                    <div class="paragraph-3 center">Чтобы добавить вопрос,</div>
                    <div class="paragraph-3 center">заберите товар из ПВЗ</div>
                </div>
            </EmptyState>
        </div>

        <!-- <div class="py-12">
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
        </div> -->
    </AuthenticatedLayout>
</template>

<style lang="scss" scoped>
.growCenter {
    display: flex;
    align-items: center;
    justify-content: center;
}
.center {
    text-align: center;
}
</style>
