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