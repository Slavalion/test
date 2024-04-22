<script setup>
import { ref, watch } from 'vue'
import AppButton from '@/Components/AppButton.vue'
import EmptyState from '@/Components/EmptyState.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import AppTable from '@/Components/AppTable.vue'
import { addQuestion } from '@/modals'
import AddQuestion from '@/Modals/AddQuestion.vue'
import TableTh from '@/Components/Table/TableTh.vue'

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
            <template v-if="actualQuestions.length > 0">
                <AppTable>
                    <template #head>
                        <tr>
                            <TableTh class="text-left">Товар</TableTh>
                            <TableTh class="text-left">Артикул</TableTh>
                            <TableTh class="text-left">Кол-во</TableTh>
                            <TableTh class="text-left">Пол</TableTh>
                            <TableTh class="text-left">Размер</TableTh>
                            <TableTh class="text-left">Поисковый запрос</TableTh>
                            <TableTh class="text-left">Доступно вопросов</TableTh>
                            <TableTh class="text-left"></TableTh>
                        </tr>
                    </template>

                    <tr v-for="question in actualQuestions" :key="question" class="main-table__tr">
                        <td>
                            <div>Описание товара</div>
                        </td>
                        <td>
                            <div class="pr-6">
                                <span class="blueText">{{ question.product_id }}</span>
                            </div>
                        </td>
                        <td>
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
                                <AppButton @click="addQuestion.open(question.id)">
                                    Добавить вопрос
                                </AppButton>
                            </div>
                        </td>
                    </tr>
                </AppTable>
            </template>
            <template v-else>
                <EmptyState class="grow growCenter">
                    <div class="flex flex-col">
                        <div class="header-5 mb-1.5">
                            нет данных удовлетворяющих условиям поиска
                        </div>
                    </div>
                </EmptyState>
            </template>
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
        <div>
            <AddQuestion />
        </div>
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
