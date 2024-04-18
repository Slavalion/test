<script setup>
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import AppButton from '@/Components/AppButton.vue'
import { reactive } from 'vue'
import AppIcon from '@/Components/AppIcon.vue'

const props = defineProps({
    tarrifs: {
        type: Array,
        required: true,
    },
})

const faqItems = reactive(
    props.tarrifs.reduce((acc, curVal) => {
        console.log(curVal);
        return [
            ...acc,
            {
                ...curVal,
                collapsed: true,
            },
        ]
    }, [])
)
</script>
<template>
    <Head title="Tarrifs" />

    <AuthenticatedLayout>
        <template #header> Tarrifs </template>

        <div v-for="faq in faqItems" class="panel panel_p-lg mb-6">
            <div
                class="cursor-pointer flex justify-between items-center select-none"
                @click="faq.collapsed = !faq.collapsed"
            >
                <span>{{ faq.title }}</span>
                <div :class="{ 'rotate-180': !faq.collapsed }" class="transition-all">
                    <AppIcon icon="chevron-down" />
                </div>
            </div>
            <Transition>
                <div v-show="!faq.collapsed" class="pt-6">
                    <div v-html="faq.video" class="flex justify-center"></div>
                    <div v-if="faq.content" class="pt-6">
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
