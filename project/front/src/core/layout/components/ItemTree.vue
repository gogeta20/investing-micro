<script setup lang="ts">
type Props = {
  icon?: string;
  nombre: string;
  to: string;
  iconClass?: string;
};

defineProps<Props>();
</script>

<template>
  <router-link :to="to" class="bh-base-router-link" :title="nombre" v-bind="$attrs">
    <span>{{ nombre }} </span>
  </router-link>
</template>

<style lang="scss">
.bh-base-router-link {
  color: inherit;
  transition: all 0.2s linear;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.router-link-exact-active {
  background-color: var(--purple-secondary)
}
</style>
