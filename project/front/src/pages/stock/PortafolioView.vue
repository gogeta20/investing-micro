<script setup lang="ts">
import PortfoliosList from "@/components/PortfoliosList.vue";
import StocksTable from "@/components/StocksTable.vue";
import { computed, ref } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();
const showStocksTable = ref<boolean>(false);
const selectedPortfolioId = ref<number | null>(null);

// Si hay un query param 'id', mostrar la tabla de acciones de ese portafolio
const portfolioId = computed(() => {
  const id = route.query.id;
  if (id) {
    // Manejar string o array de strings
    const idString = Array.isArray(id) ? id[0] : id;
    if (idString) {
      const parsedId = parseInt(idString, 10);
      if (!isNaN(parsedId)) {
        selectedPortfolioId.value = parsedId;
        showStocksTable.value = true;
        return parsedId;
      }
    }
  }
  showStocksTable.value = false;
  return null;
});

const handleBackToList = () => {
  showStocksTable.value = false;
  selectedPortfolioId.value = null;
};
</script>

<template>
  <div class="portfolio-view">
    <div class="portfolio-container">
      <div v-if="!showStocksTable" class="list-view">
        <div class="header-section">
          <h1 class="portfolio-title">Mis Portafolios</h1>
          <p class="portfolio-subtitle">
            Gestiona tus portafolios de inversión y visualiza su rendimiento
          </p>
          <router-link to="/portfolio/create" class="create-button-link">
            <i class="pi pi-plus"></i> Crear Nuevo Portafolio
          </router-link>
        </div>
        <PortfoliosList />
      </div>

      <div v-else class="detail-view">
        <div class="header-section">
          <button @click="handleBackToList" class="back-button">
            <i class="pi pi-arrow-left"></i> Volver a portafolios
          </button>
          <h1 class="portfolio-title">
            Portafolio #{{ selectedPortfolioId || "N/A" }}
          </h1>
          <p class="portfolio-subtitle">
            Estado actual de las acciones en este portafolio
          </p>
        </div>
        <StocksTable :portfolio-id="selectedPortfolioId || 1" />
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
.portfolio-view {
  min-height: calc(100vh - var(--header-height));
  padding: 2rem;
  background-color: var(--tokyo-bg);
}

.portfolio-container {
  max-width: 1400px;
  margin: 0 auto;
}

.header-section {
  margin-bottom: 2rem;
}

.back-button {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  background-color: transparent;
  color: var(--tokyo-blue);
  border: 1px solid var(--tokyo-bg-tertiary);
  border-radius: var(--border-radius);
  cursor: pointer;
  font-size: 0.95rem;
  margin-bottom: 1rem;
  transition: all 0.2s ease;

  &:hover {
    background-color: var(--tokyo-bg-tertiary);
    color: var(--tokyo-cyan);
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
  margin-bottom: 1.5rem;
  font-size: 0.95rem;
}

.create-button-link {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  background-color: var(--tokyo-blue);
  color: var(--tokyo-bg);
  text-decoration: none;
  border-radius: var(--border-radius);
  font-weight: 500;
  font-size: 1rem;
  transition: background-color 0.2s ease;
  margin-bottom: 1rem;

  &:hover {
    background-color: var(--primary-hover);
  }

  i {
    font-size: 0.9rem;
  }
}

.list-view,
.detail-view {
  animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
