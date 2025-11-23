<script setup lang="ts">
import StocksTable from "@/components/StocksTable.vue";
import { useRoute } from "vue-router";
import { computed } from "vue";

const route = useRoute();

// Obtener portfolio_id de los parámetros de la ruta
const portfolioId = computed(() => {
  const id = route.params.id;
  if (id) {
    const parsedId = typeof id === "string" ? parseInt(id, 10) : Array.isArray(id) ? parseInt(id[0], 10) : id;
    if (!isNaN(parsedId)) {
      return parsedId;
    }
  }
  return 1; // Fallback a 1 si no hay ID válido
});
</script>

<template>
  <div class="portfolio-detail-view">
    <div class="portfolio-detail-container">
      <div class="header-section">
        <router-link to="/portfolio" class="back-link">
          <i class="pi pi-arrow-left"></i> Volver a portafolios
        </router-link>
        <h1 class="portfolio-title">Portafolio #{{ portfolioId }}</h1>
        <p class="portfolio-subtitle">
          Estado actual de todas las acciones en este portafolio
        </p>
      </div>
      <StocksTable :portfolio-id="portfolioId" />
    </div>
  </div>
</template>

<style scoped lang="scss">
.portfolio-detail-view {
  min-height: calc(100vh - var(--header-height));
  padding: 2rem;
  background-color: var(--tokyo-bg);
}

.portfolio-detail-container {
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

.portfolio-title {
  font-size: 2rem;
  font-weight: 600;
  color: var(--tokyo-fg);
  margin-bottom: 0.5rem;
}

.portfolio-subtitle {
  color: var(--tokyo-fg-secondary);
  margin-bottom: 2rem;
  font-size: 0.95rem;
}
</style>
