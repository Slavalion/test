<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

import { purchaseGenerator, purchaseImport, purchaseSlide } from '@/modals'

import ApplicationLogo from '@/Components/ApplicationLogo.vue'

import AppButton from '@/Components/AppButton.vue'
import ProfileCard from '@/Components/Partials/ProfileCard.vue'
import SidebarNav from '@/Components/Partials/SidebarNav.vue'

import FillUpBalanceModal from '@/Modals/FillUpBalance.vue'
import PurchaseGenerator from '@/Modals/PurchaseGenerator.vue'
import PurchaseImport from '@/Modals/PurchaseImport.vue'
import PurchaseSlide from '@/Modals/PurchaseSlide.vue'

import AppDropdown from '@/Components/AppDropdown.vue'
import AppIcon from '@/Components/AppIcon.vue'
import { globalSettings } from '@/Store/globalSettings'

const sidebarCollapsed = ref(localStorage.getItem('sidebar-collapsed') == 'true')

const collapseIcon = computed(() => {
    return sidebarCollapsed.value ? 'chevrons-right' : 'chevrons-left'
})

const logoViewBox = computed(() => {
    return sidebarCollapsed.value ? '0 0 36 36' : '0 0 156 36'
})

const toggleSidebar = () => {
    sidebarCollapsed.value = !sidebarCollapsed.value
    localStorage.setItem('sidebar-collapsed', sidebarCollapsed.value)
}

globalSettings.value = {
    ...globalSettings.value,
    ...usePage().props.settings,
}
</script>

<template>
    <div
        class="wrapper min-h-screen z-50"
        :class="{
            sidebar_collapsed: sidebarCollapsed,
            'in-maintenance-mode': globalSettings.maintenance_mode,
        }"
    >
        <div v-if="globalSettings.maintenance_mode" class="maintenance-mode">
            <div class="flex items-center gap-6">
                <AppIcon icon="alert-triangle" width="30" height="30" />
                <div class="leading-tight">
                    <div>Режим обслуживания</div>
                    <div>На данный момент можно пополнить только кошелек</div>
                </div>
            </div>
        </div>
        <div class="sidebar fixed inset-y-0 flex flex-col">
            <div class="logo-wrapper">
                <Link :href="route('purchase.list')" class="flex items-center relative">
                    <ApplicationLogo :viewBox="logoViewBox" height="36" />
                    <div
                        v-if="globalSettings.demo_mode"
                        class="absolute top-[7px] left-[177px] rotate-[0deg] text-xs bg-red-600 font-bold scale-90 text-white p-1 px-2 rounded-lg"
                    >
                        DEMO
                    </div>
                </Link>
            </div>

            <div class="mt-6 mb-3">
                <ProfileCard />
            </div>

            <SidebarNav />

            <div class="sidebar-collapse">
                <AppButton theme="normal" :icon="collapseIcon" @click="toggleSidebar" />
            </div>
        </div>

        <div class="topbar fixed inset-x-0">
            <AppButton class="ml-auto" icon="plus-circle" @click="purchaseSlide.open()">
                Создать выкуп
            </AppButton>

            <AppDropdown width="64">
                <template #trigger>
                    <AppButton icon="layers">Массовый выкуп</AppButton>
                </template>
                <template #content>
                    <ul class="app-list">
                        <li class="app-list__item" @click="purchaseGenerator.open()">
                            <AppIcon icon="loader" />
                            <span>Сгенерировать автоматически</span>
                        </li>
                        <li class="app-list__item" @click="purchaseImport.open()">
                            <AppIcon icon="file-download" />
                            <span>Импортировать из Excel</span>
                        </li>
                    </ul>
                </template>
            </AppDropdown>
        </div>

        <!-- Page Content -->
        <main class="main">
            <!-- Page Heading -->
            <header v-if="$slots.header">
                <h1 class="main__headline flex items-center">
                    <slot name="header" />
                </h1>
            </header>

            <slot></slot>
        </main>

        <!-- Modals -->
        <FillUpBalanceModal />
        <PurchaseSlide />
        <PurchaseGenerator />
        <PurchaseImport />
    </div>
</template>
