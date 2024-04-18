<script setup>
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import AppButton from '@/Components/AppButton.vue'
import { reactive, ref } from 'vue'
import AppIcon from '@/Components/AppIcon.vue'

const props = defineProps({
    tarrifs: {
        type: Array,
        required: true,
    },
})

const sections = ref(['purchase'])
const currentSection = ref ('purchase')

const setSection = (section) => {
    currentSection.value = section
}

const tarrifsItems = reactive(
    props.tarrifs.reduce((acc, curVal) => {
        // !sections.value.includes(curVal.task_type) ? setSection([...sections.value, curVal.task_type]) :console.log(curVal.task_type)
        
        return [
            ...acc,
            {
                ...curVal,
                collapsed: true,
            },
        ]
    }, [])
)

const actualSection = reactive(
    tarrifsItems.reduce((acc, curVal) => {
        if (acc.includes(curVal.task_type)) {
    return acc; // если значение уже есть, то просто возвращаем аккумулятор
  }
  return [...acc, curVal.task_type];

}, [])
)

</script>
<template>
    <Head title="Tarrifs" />

    <AuthenticatedLayout>
        <template #header>Тарифы</template>

        <div>{{ actualSection[0] }}</div>

        <div class="panel mb-6">
            <div class="flex gap-1.5">
                <AppButton
                    theme="normal"
                    :class="{ btn_selected: null == currentSection }"
                    @click="setSection('')"
                >
                    Доступные
                </AppButton>
                <AppButton
                    theme="normal"
                    :class="{ btn_selected: 'processing' == currentSection }"
                    @click="setSection('processing')"
                >
                    В работе
                </AppButton>
                <AppButton
                    theme="normal"
                    :class="{ btn_selected: 'done' == currentSection }"
                    @click="setSection('done')"
                >
                    Опубликованные
                </AppButton>
            </div>
        </div>    

        <div v-for="faq in tarrifsItems" class="panel panel_p-lg mb-6">
            <div
                class="cursor-pointer flex justify-between items-center select-none"
                @click="faq.collapsed = !faq.collapsed"
            >
                <span>{{ faq.type }}</span>
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
