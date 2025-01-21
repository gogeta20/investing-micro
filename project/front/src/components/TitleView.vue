<!-- @format -->

<script setup lang="ts">
import { defineProps } from "vue";
defineProps({
  title: {
    type: String,
    required: true,
  },
});
</script>
<template>
  <div class="page-title">
    <p class="kode-style text-4xl md:text-6xl text-center text-white">{{ title }}</p>
  </div>
</template>

<style scoped></style>
