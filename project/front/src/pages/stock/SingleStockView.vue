<script setup lang="ts">
import SingleStockTable from "@/components/SingleStockTable.vue";
import { computed } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();
const symbol = computed(() => route.params.symbol as string);
</script>

<template>
  <div class="single-stock-view">
    <div class="single-stock-container">
      <div class="header-section">
        <router-link to="/stocks" class="back-link">
          <i class="pi pi-arrow-left"></i> Volver a acciones
        </router-link>
        <h1 class="stock-title">Historial de Acción</h1>
        <p class="stock-subtitle">Datos históricos de {{ symbol }}</p>
      </div>
      <SingleStockTable :symbol="symbol" />
    </div>
  </div>
</template>

<style scoped lang="scss">
.single-stock-view {
  min-height: calc(100vh - var(--header-height));
  padding: 2rem;
  background-color: var(--tokyo-bg);
}

.single-stock-container {
  max-width: 1400px;
  margin: 0 auto;
}

.header-section {
  margin-bottom: 2rem;
}

.back-link {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  color: var(--tokyo-blue);
  text-decoration: none;
  font-size: 0.95rem;
  margin-bottom: 1rem;
  transition: color 0.2s ease;

  &:hover {
    color: var(--tokyo-cyan);
    text-decoration: underline;
  }

  i {
    font-size: 0.9rem;
  }
}

.stock-title {
  font-size: 2rem;
  font-weight: 600;
  color: var(--tokyo-fg);
  margin-bottom: 0.5rem;
}

.stock-subtitle {
  color: var(--tokyo-fg-secondary);
  margin-bottom: 1rem;
  font-size: 0.95rem;
}
</style>
