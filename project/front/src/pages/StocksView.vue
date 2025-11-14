<script setup lang="ts">
import AllStocksTable from "@/components/AllStocksTable.vue";
import StocksTable from "@/components/StocksTable.vue";
</script>

<template>
  <div class="stocks-view">
    <div class="stocks-container">
      <h1 class="stocks-title">Todas las Acciones</h1>
      <p class="stocks-subtitle">Estado actual de todas las acciones en la base de datos</p>
      <AllStocksTable />
    </div>

    <div class="stocks-container">
      <h1 class="stocks-title">Tabla de Acciones</h1>
      <p class="stocks-subtitle">Vista general de las acciones en tu portafolio</p>
      <StocksTable :portfolio-id="1" />
    </div>
  </div>
</template>

<style scoped lang="scss">
.stocks-view {
  min-height: calc(100vh - var(--header-height));
  padding: 2rem;
  background-color: var(--tokyo-bg);
}

.stocks-container {
  max-width: 1400px;
  margin: 0 auto;
}

.stocks-title {
  font-size: 2rem;
  font-weight: 600;
  color: var(--tokyo-fg);
  margin-bottom: 0.5rem;
}

.stocks-subtitle {
  color: var(--tokyo-fg-secondary);
  margin-bottom: 2rem;
  font-size: 0.95rem;
}
</style>
