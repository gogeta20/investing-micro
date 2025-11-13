<script setup lang="ts">
const emit = defineEmits(["menu-toggle"]);
function onMenuToggle(event: Event) {
  event.preventDefault();
  emit("menu-toggle", event);
  event.stopPropagation();
}
</script>

<template>
  <header class="header">
    <div class="header-container">
      <router-link to="/" class="header-logo">MV</router-link>
      <button class="header-menu-button" @click="onMenuToggle" aria-label="Toggle menu">
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
.header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: var(--header-height);
  background-color: var(--tokyo-bg-secondary);
  border-bottom: 1px solid var(--tokyo-bg-tertiary);
  z-index: 50;
  backdrop-filter: blur(10px);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.header-container {
  max-width: 100%;
  margin: 0 auto;
  padding: 0 1rem;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.header-logo {
  color: var(--tokyo-fg);
  font-size: 1.5rem;
  font-weight: 600;
  text-decoration: none;
  transition: color 0.2s ease;

  &:hover {
    color: var(--tokyo-blue);
  }
}

.header-menu-button {
  color: var(--tokyo-fg);
  background: transparent;
  border: none;
  cursor: pointer;
  padding: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: color 0.2s ease;
  outline: none;

  &:hover {
    color: var(--tokyo-blue);
  }

  &:focus {
    outline: 2px solid var(--tokyo-blue);
    outline-offset: 2px;
    border-radius: 4px;
  }
}
</style>
