<script setup>

import { watch } from "vue";

const props = defineProps(["tarrif", "keyID"]);

watch(props, () => {
  console.log(props)
});


</script>
<template>
    <div class="tarrife-card">
        <div>{{  props.keyID }}</div>
        <h6>{{ props.tarrif.type }}</h6>
        <p>{{ props.tarrif.task_type }}</p>
    </div>

</template>

<style scoped lang="scss">
.tarrife-card{
    min-width: 254px;
    min-height: 260px;
    padding: 24px;
    background-color: #FFFFFF;
    border: 0.5px solid #E7EAF0;
    border-radius: 16px;

    flex-grow: 1; 
}


</style>