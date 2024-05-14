<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed, ref, watch, onMounted } from 'vue'
import device from 'vue3-device-detector'
import { useWindowSize } from '@vueuse/core'
import { purchaseGenerator, purchaseImport, purchaseSlide } from '@/modals'

import ApplicationLogo from '@/Components/ApplicationLogo.vue'

import AppButton from '@/Components/AppButton.vue'
import ProfileCard from '@/Components/Partials/ProfileCard.vue'
import SidebarNav from '@/Components/Partials/SidebarNav.vue'

import FillUpBalanceModal from '@/Modals/FillUpBalance.vue'
import PurchaseGenerator from '@/Modals/PurchaseGenerator.vue'
import PurchaseImport from '@/Modals/PurchaseImport.vue'
import PurchaseSlide from '@/Modals/PurchaseSlide.vue'
import ProgressBar from '@/Components/PurchaseProgressBar.vue'
import AppDropdown from '@/Components/AppDropdown.vue'
import AppIcon from '@/Components/AppIcon.vue'
import { globalSettings } from '@/Store/globalSettings'

const sidebarCollapsed = ref(localStorage.getItem('sidebar-collapsed') == 'true')
const sidebarCollapsedMobile = ref(localStorage.getItem('sidebar-collapsed-mobile') == 'true')
const isSmallScreen = ref(false)
const { width } = useWindowSize()
const page = usePage()
const totalDeliveres = page.props.data.allDeliveres
const finishedDeliveres = page.props.data.finishedDeliveres

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

const toggleSidebarMobile = () => {
    sidebarCollapsedMobile.value = !sidebarCollapsedMobile.value
    localStorage.setItem('sidebar-collapsed-mobile', sidebarCollapsed.value)
}

globalSettings.value = {
    ...globalSettings.value,
    ...usePage().props.settings,
}

window.Echo.channel('tasks').listen('TaskProgressUpdate', (e) => {
    // const task = e.task
    // let localTask = state.tasks.find((el) => {
    //     return el.id == task.id
    // })
    console.log(e.task)
    // localTask.progress = task.progress
})

watch(width, () => {
    isSmallScreen.value = !(device().isDesktop && width.value > 390)
    if (width.value < 390 && document.getElementsByTagName('jdiv').length > 0) {
        document.getElementsByTagName('jdiv')[0].style.display = 'none'
    }
})

onMounted(() => {
    isSmallScreen.value = !(device().isDesktop && width.value > 390)
    if (!(device().isDesktop && width > 390)) {
        sidebarCollapsedMobile.value = true
        localStorage.setItem('sidebar-collapsed-mobile', true)
    }
})
</script>

<template>
    <div
        v-if="isSmallScreen"
        :class="'wrapper-mobile ' + usePage().component.toLowerCase() + '-mobile'"
    >
        <div class="wrapper-mobile__desk">
            <div class="wrapper-mobile__desk-topmenu">
                <AppButton icon="menu" theme="outline" @click="toggleSidebarMobile"></AppButton>
                <div class="wrapper-mobile__logo">
                    <img src="/images/LogoColor.svg" alt="MPB.top" height="44" />
                </div>
                <AppButton
                    v-if="purchaseSlide.show"
                    icon="close"
                    @click="purchaseSlide.close()"
                ></AppButton>
                <AppButton v-else icon="plus-circle" @click="purchaseSlide.open()"></AppButton>
            </div>
            <!-- Page Content -->
            <main class="wrapper-mobile__desk-body" v-if="!sidebarCollapsedMobile">
                <div class="wrapper-mobile__sidebar">
                    <ProfileCard />

                    <SidebarNav />
                </div>
            </main>

            <main class="wrapper-mobile__desk-body" v-else>
                <!-- Page Heading -->
                <div v-if="globalSettings.maintenance_mode" class="mobile-maintenance-mode">
                    <div class="flex items-center gap-6">
                        <AppIcon icon="alert-triangle" width="30" height="30" />
                        <div class="mobile-maintenance-mode__infotext">
                            <h6>Режим обслуживания</h6>
                            <p>На данный момент можно только пополнить кошелек</p>
                        </div>
                    </div>
                </div>

                <header v-if="$slots.header">
                    <h1 class="main__headline flex items-center wrapper-mobile__header">
                        <slot name="header" />
                    </h1>
                </header>

                <slot></slot>
            </main>
        </div>
        <PurchaseSlide />
        <FillUpBalanceModal />
    </div>

    <div
        v-else
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
            <div class="purchase-group__progress">
                <p>Завершено выкупов: {{ finishedDeliveres }} из {{ totalDeliveres }}</p>
                <ProgressBar :progress="Math.round((finishedDeliveres * 100) / totalDeliveres)" />
            </div>

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

            <slot :isSmallScreen="isSmallScreen"></slot>
        </main>

        <!-- Modals -->
        <FillUpBalanceModal />
        <PurchaseSlide />
        <PurchaseGenerator />
        <PurchaseImport />
    </div>
</template>
