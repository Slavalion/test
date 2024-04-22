<script setup>
import { ref, watch } from 'vue'
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
import { set } from '@vueuse/core'

const props = defineProps({
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
const actualQuestions = ref(props.questions)

const currentSection = ref('available')

const actualSection = ['available', 'published']

const setSection = (section) => {
    currentSection.value = section
}

watch(searchVendorCode, () => {
    if (searchVendorCode.value.length > 0) {
        actualQuestions.value = props.questions.filter(
            (question) =>
                question.product_id.substring(0, searchVendorCode.value.length) ===
                searchVendorCode.value
        )
        console.log(props.questions)
        console.log(searchVendorCode.value)
    } else {
        actualQuestions.value = props.questions
    }
})
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

                <tr v-for="question in actualQuestions" :key="question" class="main-table__tr">
                    <td>
                        <div>Описание товара</div>

                        <!-- <div class="text-xs text-gray-400">
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
                        </template> -->
                    </td>
                    <td>
                        <div class="pr-6">
                            <span class="blueText">{{ question.product_id }}</span>
                        </div>
                    </td>
                    <td>
                        <!-- <div class="w-[234px]">
                            <div>{{ question.progress }} из {{ question.total }}</div>
                        </div> -->
                        <p>456</p>
                    </td>
                    <td>
                        <p>{{ question.gender === 0 ? 'М' : 'Ж' }}</p>
                    </td>
                    <td>
                        <p>XL</p>
                    </td>
                    <td>
                        <p>Кроссовки</p>
                    </td>
                    <td>
                        <p>10</p>
                    </td>
                    <td>
                        <div class="pl-6 flex justify-end">
                            <a
                                class="ml-auto"
                                :href="'/purchase/download?status=' + currentSection"
                                target="_blank"
                                download
                            >
                                <AppButton>Добавить вопрос</AppButton>
                            </a>
                        </div>
                    </td>
                </tr>
            </AppTable>
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
.blueText {
    color: #1665ff;
}
thead th:nth-child(1) {
    width: 22.14%;
}
thead th:nth-child(2) {
    width: 8.86%;
}
thead th:nth-child(3) {
    width: 5.17%;
}
thead th:nth-child(4) {
    width: 5.17%;
}
thead th:nth-child(5) {
    width: 5.17%;
}
thead th:nth-child(6) {
    width: 21.49%;
}
thead th:nth-child(7) {
    width: 13.1%;
}
thead th:nth-child(8) {
    width: 18%;
}
</style>
