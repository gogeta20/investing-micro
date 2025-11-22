<script setup lang="ts">
import IconView from "@/components/IconView.vue";
</script>

<template>
  <div class="flex h-screen sticky top-0">
    <div class="container m-auto p-5">
      <!-- <ParticlesView /> -->
      <p class="font-light h-20 text-white text-sm tracking-long">
        SOFTWARE DEVELOPER
      </p>
      <p class="kode-style text-4xl md:text-6xl h-28 text-white font-semibold">
        Mauricio Vargas
      </p>

      <div class="w-0 grid gap-16 grid-cols-4 text-white text-3xl">

        <IconView icon="teenyicons:linkedin-outline" link="https://www.linkedin.com/in/mauricio-vargas-18336a1b8/" />

        <IconView icon="akar-icons:github-outline-fill" link="https://github.com/gogeta20" />

        <IconView icon="ci:mail" link="mailto:codigomau@gmail.com" />

        <!-- <IconView icon="ant-design:calendar-outlined" link="https://calendly.com/szymonb/30min" /> -->
      </div>
    </div>
  </div>
</template>

<style scoped></style>
