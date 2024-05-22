<script setup>
import { Head, usePage } from '@inertiajs/vue3'
import { ref, shallowRef, watch } from 'vue'

import {
    profileDelete,
    profileInformation,
    profilePassword,
    profileTelegramPayment,
} from '@/modals'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import UpdateProfileInformationModal from './Modals/UpdateProfileInformationModal.vue'

import AppButton from '@/Components/AppButton.vue'
import AppIcon from '@/Components/AppIcon.vue'
import DeleteUserModal from './Modals/DeleteUserModal.vue'
import UpdatePasswordModal from './Modals/UpdatePasswordModal.vue'
import UpdateTelegramPaymentModal from './Modals/UpdateTelegramPaymentModal.vue'

import CheckboxInput from '@/Components/Inputs/CheckboxInput.vue'
import { useTelegramAuth } from '@/Composables/telegramAuth'
import { useAxios } from '@/Composables/useAxios'

const props = defineProps({
    notifications: {
        type: Object,
        required: true,
    },
})

const user = usePage().props.auth.user
const apiKey = shallowRef(user.api_key)
const refCode = shallowRef(user.ref_code)
const api = useAxios()

const telegram = ref()
useTelegramAuth(telegram, route('telegram.connect'))

const generateApiKey = () => {
    api.post(route('profile.generate-api-key'), { user_id: user.id }).then((resp) => {
        apiKey.value = resp.data.api_key
    })
}

const generateRefCode = () => {
    api.post(route('profile.generate-ref-code')).then((resp) => {
        refCode.value = resp.data.ref_code
    })
}

const notificationList = ref({
    'wallet.all': false,
    'purchase.all': false,
    ...props.notifications,
})

watch(
    notificationList,
    () => {
        api.post(route('profile.notifications.update'), {
            notifications: notificationList.value,
        }).then((resp) => {
            console.log(resp)
        })
    },
    { deep: true }
)
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header> Профиль </template>

        <div class="space-y-4">
            <div class="panel panel_p-lg">
                <div class="profile-block">
                    <div class="profile-block__icon">
                        <AppIcon icon="user" />
                    </div>
                    <div class="profile-block__info">
                        <div class="profile-block__title">{{ user.email }}</div>
                        <div class="profile-block__desc">
                            Вы можете изменить свое имя и E-mail. После подтверждения вы сможете
                            входить под новой почтой.
                        </div>
                    </div>
                    <div class="profile-block__action">
                        <AppButton theme="outline" @click="profileInformation.open()">
                            Изменить информацию
                        </AppButton>
                    </div>
                </div>

                <UpdateProfileInformationModal />
            </div>

            <div class="panel panel_p-lg">
                <div class="profile-block">
                    <div class="profile-block__icon">
                        <AppIcon icon="lock" />
                    </div>
                    <div class="profile-block__info">
                        <div class="profile-block__title">Поменять пароль</div>
                        <div class="profile-block__desc">Поменять данные для входа.</div>
                    </div>
                    <div class="profile-block__action">
                        <AppButton theme="outline" @click="profilePassword.open()">
                            Поменять пароль
                        </AppButton>
                    </div>
                </div>

                <UpdatePasswordModal />
            </div>

            <div class="panel panel_p-lg">
                <div class="profile-block">
                    <div class="profile-block__icon">
                        <AppIcon icon="user" />
                    </div>
                    <div class="profile-block__info">
                        <div class="profile-block__title">Подключить телеграм</div>
                        <div class="profile-block__desc">
                            Подключить телеграм аккаунт для получения оповещений
                        </div>
                    </div>
                    <div class="profile-block__action">
                        <div v-show="user.telegram_id == 0" class="flex justify-end">
                            <div ref="telegram" id="tg-auth-widget" style=""></div>
                        </div>
                        <div v-show="user.telegram_id != 0">
                            Ваш Chat ID: {{ user.telegram_id }}
                        </div>
                    </div>
                </div>
                <div class="p-4 pl-10" v-if="user.telegram_id != 0">
                    <div class="mb-2">Оповещения</div>

                    <CheckboxInput
                        :value="notificationList['wallet.all']"
                        v-model:checked="notificationList['wallet.all']"
                    >
                        По кошелькам
                    </CheckboxInput>

                    <CheckboxInput
                        :value="notificationList['purchase.all']"
                        v-model:checked="notificationList['purchase.all']"
                    >
                        По выкупам
                    </CheckboxInput>
                </div>
            </div>

            <div class="panel panel_p-lg">
                <div class="profile-block">
                    <div class="profile-block__icon">
                        <AppIcon icon="users" />
                    </div>
                    <div class="profile-block__info">
                        <div class="profile-block__title">Телеграм для оплаты</div>
                        <div class="profile-block__desc">
                            Добавить данные для оплаты через телеграм
                        </div>
                    </div>
                    <div class="profile-block__action">
                        <AppButton theme="outline" @click="profileTelegramPayment.open()">
                            Изменить данные
                        </AppButton>
                    </div>
                </div>

                <UpdateTelegramPaymentModal />
            </div>

            <div class="panel panel_p-lg">
                <div class="profile-block">
                    <div class="profile-block__icon">
                        <AppIcon icon="setting" />
                    </div>
                    <div class="profile-block__info">
                        <div class="profile-block__title">Апи ключ</div>
                        <div class="profile-block__desc">Ключ для использования апи эндпоинтов</div>
                    </div>
                    <div class="profile-block__action flex gap-4 items-center">
                        <template v-if="apiKey">
                            <div class="p-1 px-2 bg-slate-200 rounded-md">{{ apiKey }}</div>
                            <AppButton theme="outline" icon="refresh" @click="generateApiKey" />
                        </template>
                        <AppButton v-else theme="outline" icon="refresh" @click="generateApiKey">
                            Сгенерировать
                        </AppButton>
                    </div>
                </div>
            </div>

            <div class="panel panel_p-lg">
                <div class="profile-block">
                    <div class="profile-block__icon">
                        <AppIcon icon="users" />
                    </div>
                    <div class="profile-block__info">
                        <div class="profile-block__title">Реферральная программа</div>
                        <div class="profile-block__desc">
                            Пригласи пользователя и получи 5% от всех его операций за следующие 3
                            месяца
                        </div>
                    </div>
                    <div class="profile-block__action flex gap-4 items-center">
                        <template v-if="refCode">
                            <div class="p-1 px-2 bg-slate-200 rounded-md">
                                https://app.mpb.top/login?ref={{ refCode }}
                            </div>
                        </template>
                        <AppButton v-else theme="outline" icon="refresh" @click="generateRefCode">
                            Сгенерировать
                        </AppButton>
                    </div>
                </div>
            </div>

            <div class="panel panel_p-lg">
                <div class="profile-block profile-block_theme-danger">
                    <div class="profile-block__icon">
                        <AppIcon icon="delete" />
                    </div>
                    <div class="profile-block__info">
                        <div class="profile-block__title">Удалить аккаунт</div>
                        <div class="profile-block__desc">
                            Как только ваша учетная запись будет удалена, все ее ресурсы и данные
                            будут удалены безвозвратно, включая финансы. Прежде чем удалить свою
                            учетную запись, пожалуйста, проверьте всю информацию, которую вы хотите
                            сохранить.
                        </div>
                    </div>
                    <div class="profile-block__action">
                        <AppButton theme="oldanger" @click="profileDelete.open()">
                            Удалить аккаунт
                        </AppButton>
                    </div>
                </div>
                <DeleteUserModal />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
