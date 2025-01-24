<script setup lang="ts">
const emit = defineEmits(["menu-toggle"]);
function onMenuToggle(event: Event) {
  event.preventDefault();
  emit("menu-toggle", event);
  event.stopPropagation();
}
</script>

<template>
  <header :class="['fixed top-0 left-0 w-full z-50 transition-all duration-300']">
    <div class="container mx-auto px-4 flex items-center justify-between py-8">
      <router-link to="/" class="text-white text-2xl">MV</router-link>

      <button class="text-white focus:outline-none" @click="onMenuToggle">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
          class="w-6 h-6"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M4 6h16M4 12h16m-7 6h7"
          />
        </svg>
      </button>
    </div>
  </header>
</template>

<style scoped lang="scss">
.bg-transparent {
  backdrop-filter: blur(10px);
}
</style>
