<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3'

import { useAxios } from '@/Composables/useAxios'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import AppButton from '@/Components/AppButton.vue'
import CheckboxInput from '@/Components/Inputs/CheckboxInput.vue'
import TextInput from '@/Components/Inputs/TextInput.vue'
import { useDebounce } from '@/Composables/debounce'
import { ref, watch } from 'vue'

const props = defineProps({
    users: {
        type: Array,
        default: () => [],
    },
    paginator: {
        type: Array,
        default: () => [],
    },
})

const priceTypes = {
    purchase: 'В',
    review: 'О',
    pick_up: 'З',
    review_reaction: 'ОР',
    review_complaint: 'ОЖ',
    action_cart: 'ДВК',
    action_search: 'ПТ',
}

const api = useAxios()
const form = useForm({})

const searchQuery = ref('')

function loginAs(user) {
    form.post(route('admin.users.loginas', user.id))
}

const setPreference = (user) => {
    api.post(route('admin.users.set-preferences'), {
        user_id: user.id,
        use_livecargo: user.preferences.use_livecargo,
    }).then(() => {
        router.reload()
    })
}

const searchUser = () => {
    router.reload({
        data: {
            q: searchQuery.value,
            page: 1,
        },
    })
}

const debauncedSearchUser = useDebounce(searchUser, 300)

watch(searchQuery, debauncedSearchUser)
</script>

<template>
    <Head title="Search" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between w-full">
                <div>Пользователи</div>
                <form action="">
                    <TextInput v-model="searchQuery" placeholder="ID, Имя, Почта или tgusername" />
                </form>
            </div>
        </template>

        <div class="panel panel_p-lg">
            <div class="divide-y">
                <div
                    v-for="user in users"
                    :key="user.id"
                    class="flex items-center py-4 first:pt-0 last:pb-0 gap-6"
                >
                    <div>
                        <div class="leading-none pb-1">#{{ user.id }} {{ user.name }}</div>
                        <div class="text-xs leading-none">{{ user.email }}</div>
                    </div>

                    <div class="text-xs grid grid-cols-3 gap-1">
                        <div v-for="price in user.prices" :key="price" class="flex justify-center">
                            <span>{{ priceTypes[price.type] }}-</span>
                            <span>{{ price.value / 100 }}₽</span>
                        </div>
                    </div>

                    <div class="ml-auto">
                        <CheckboxInput
                            name="remember"
                            v-model:checked="user.preferences.use_livecargo"
                            @change="setPreference(user)"
                        >
                            Livecargo
                        </CheckboxInput>
                    </div>

                    <div class="flex gap-2">
                        <AppButton
                            :disabled="$page.props.auth.user.id == user.id"
                            @click="loginAs(user)"
                        >
                            Авторизоваться
                        </AppButton>

                        <Link :href="route('admin.users.show', { id: user.id })">
                            <AppButton icon="setting" theme="outline"> Настройки </AppButton>
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-6 flex gap-2 justify-center">
            <div v-for="link in paginator" :key="link">
                <Link :href="link.url">
                    <AppButton v-html="link.label" :theme="link.active ? '' : 'normal'"></AppButton>
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
