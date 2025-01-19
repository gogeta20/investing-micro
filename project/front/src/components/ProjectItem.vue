<script setup lang="ts">
import { defineProps } from 'vue'
defineProps({
  img: {
    type: String,
    required: true,
  },
  href: {
    type: String
  },
  title: {
    type: String
  }
});
</script>
<template>
  <div class="border-4 rounded-md border-black bg-black">
    <img :src=img />
    <a :href=href target="_blank" class=" text-sm md:text-2xl text-white text-center">
      <p>{{ title }}</p>
    </a>
  </div>
</template>

<style scoped>
.line {
    height: 2px;
    width: 40px;
    background-color: #fff;
    float: left;
    margin: 14px 0px;
    padding: 0px;
}
.line.finished {
    height: 2px;
    width: 40px;
    background-color: #d100d1;
    float: left;
    margin: 14px 0px;
    padding: 0px;
}

.carousel div {
    width: 66%;
    margin: 40px;
}

embed {
    max-height: 500px;
}
</style>
