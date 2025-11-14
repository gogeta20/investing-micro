<script setup lang="ts">
// import ToastView from '@/components/ToastView.vue';
</script>

<template>
  <router-view />
  <!--    <ToastView />-->
</template>

<style lang="scss">
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  background-color: var(--tokyo-bg);
  color: var(--tokyo-fg);
  font-family: var(--font-family);
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

#app {
  min-height: 100vh;
  background-color: var(--tokyo-bg);
}
</style>
