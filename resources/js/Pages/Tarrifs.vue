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

        <div class="panel mb-6">
            <div class="flex flex-row gap-1.5" >
                <AppButton
                    v-for="section in actualSection"
                    theme="normal"
                    :class="{ btn_selected: section == currentSection }"
                    @click="setSection(section)"
                >
                    {{ section}}
                </AppButton>
            </div>
        </div>    

        <div  class="container flex flex-row">
            <div class="tarifCard" v-for="tarrif in tarrifsItems.filter(tarrif => tarrif.task_type === currentSection)">
                <h6>{{ tarrif.type }}</h6>
                <p>{{ tarrif.task_type }}</p>
            </div>
            
            
            <!-- <divcursor-pointer flex justify-between i
                class="tems-center select-none"
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
            </Transition> -->
        </div>
     </AuthenticatedLayout>
</template>
<style lang="scss" scoped>
/* we will explain what these classes do next! */
.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}

.container{
    width: 100%;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.tarifCard{
    width: 30%;
}
</style>
