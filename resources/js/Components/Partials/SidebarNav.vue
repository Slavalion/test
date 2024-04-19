<script setup>
import { usePage } from '@inertiajs/vue3'
import { watch } from 'vue'

import AppIcon from '@/Components/AppIcon.vue'
import NavLink from '@/Components/NavLink.vue'

import sidebarNav from '@/Data/sidebarNav'
import { onMounted } from 'vue'
import { onUnmounted } from 'vue'

const page = usePage()
const userId = page.props.auth.user.id

const sections = sidebarNav.sections.filter((el) => {
    if (!el.role) {
        return true
    }

    return el.role == page.props.auth.user.role
})

watch(
    () => page.props.data.deliveries,
    (newVal) => {
        sidebarNav.sections[0].items.deliveries.counter.value = newVal
    },
    {
        immediate: true,
    }
)

watch(
    () => page.props.data.availableReviews,
    (newVal) => {
        sidebarNav.sections[1].items.reviews.counter.value = newVal
    },
    {
        immediate: true,
    }
)

onMounted(() => {
    window.Echo.private('user.' + userId).listen('.deliveries.update.total', (e) => {
        sidebarNav.sections[0].items.deliveries.counter.value = e.deliveries
    })
})

onUnmounted(() => {
    window.Echo.private('user.' + userId).stopListening('.deliveries.update.total')
})

const checkCondition = (condition) => {
    if (condition == 'preference:use-livecargo') {
        return page.props.auth.user.preferences.use_livecargo
    }

    return true
}
</script>
<template>
    <nav class="menu flex flex-col">
        <template v-for="section in sections" :key="section">
            <div class="menu__section">
                {{ section.title }}
            </div>

            <template v-for="item in section.items" :key="item">
                <NavLink
                    v-if="checkCondition(item.condition)"
                    :href="route(item.route)"
                    :active="route().current(item.route)"
                    :counter="item.counter?.value"
                    status="warning"
                    class="menu__link"
                >
                    <div class="menu__link__title">
                        <AppIcon :icon="item.icon" />
                        <span>{{ item.title }}</span>
                    </div>
                    <div class="menu__link__signal">
                        <div v-if="Boolean(item.counter)" class="menu__link__counter">
                            <p>{{ item.counter }}</p>
                        </div>
                        <div v-if="item.route === 'wallets'" class="menu__link__alertOctagon">
                            &nbsp;
                        </div>
                        <div
                            v-if="item.route === 'purchase.list'"
                            class="menu__link__alertTriangle"
                        >
                            &nbsp;
                        </div>
                    </div>
                </NavLink>
            </template>
        </template>
    </nav>
</template>
<style lang="scss" scoped>
.menu__link,
.menu__link__title {
    display: flex;
    flex-direction: row;
    align-items: center;
}
.menu__link {
    justify-content: space-between;

    &__title {
        gap: 12px;
    }

    &__signal {
        height: 100%;
        min-width: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    &__counter {
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        background-color: #1665ff;

        p {
            color: white;
            font-size: 13px;
            line-height: 20px;
        }
    }

    &__alertOctagon,
    &__alertTriangle {
        width: 20px;
        height: 20px;
        background-size: contain;
    }

    &__alertOctagon {
        background-image: url('images/alert-octagon.svg');
    }

    &__alertTriangle {
        background-image: url('images/alert-triangle.svg');
    }
}
</style>
