<script setup lang="ts">
</script>

<template>
  <header>
    <router-view />
  </header>
</template>

<style lang="scss">
body {
  background-color: var(--color-bg);
  color: var(--color-text);
}

.dark {
  --color-bg: #1a1b26;
  --color-text: #c0caf5;
}
</style>
