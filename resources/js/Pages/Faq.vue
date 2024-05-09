<script setup>
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import AppButton from '@/Components/AppButton.vue'
import { reactive } from 'vue'
import AppIcon from '@/Components/AppIcon.vue'
import device from 'vue3-device-detector'
import { useWindowSize } from '@vueuse/core'

const props = defineProps({
    faqs: {
        type: Array,
        required: true,
    },
})

const faqItems = reactive(
    props.faqs.reduce((acc, curVal) => {
        return [
            ...acc,
            {
                ...curVal,
                collapsed: true,
            },
        ]
    }, [])
)

const { width } = useWindowSize()
</script>
<template>
    <Head title="FAQ" />

    <AuthenticatedLayout>
        <template #header> FAQ </template>

        <div
            v-for="faq in faqItems"
            :class="device().isDesktop && width > 390 ? 'panel panel_p-lg mb-6' : 'mobile-faq-card'"
        >
            <div
                class="cursor-pointer flex justify-between items-center select-none"
                @click="faq.collapsed = !faq.collapsed"
                :class="device().isDesktop && width > 390 ? ' ' : 'mobile-faq-card__title'"
            >
                <span>{{ faq.title }}</span>
                <div :class="{ 'rotate-180': !faq.collapsed }" class="transition-all">
                    <AppIcon icon="chevron-down" />
                </div>
            </div>
            <Transition>
                <div
                    v-show="!faq.collapsed"
                    :class="device().isDesktop && width > 390 ? 'pt-6' : 'mobile-faq-card__body'"
                >
                    <div
                        v-html="faq.video"
                        :class="
                            device().isDesktop && width > 390
                                ? 'flex justify-center'
                                : 'mobile-faq-card__body-header'
                        "
                    ></div>
                    <div
                        v-if="faq.content"
                        :class="
                            device().isDesktop && width > 390
                                ? 'pt-6'
                                : 'mobile-faq-card__body-content'
                        "
                    >
                        {{ faq.content }}
                    </div>
                </div>
            </Transition>
        </div>
    </AuthenticatedLayout>
</template>
<style lang="scss">
/* we will explain what these classes do next! */
.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
