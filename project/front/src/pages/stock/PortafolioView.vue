<script setup lang="ts">
import PortfoliosList from "@/components/PortfoliosList.vue";

// Ya no necesitamos manejar la vista de detalle aquí, se hace en PortfolioDetailView
</script>

<template>
  <div class="portfolio-view">
    <div class="portfolio-container">
      <div class="list-view">
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

.list-view {
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
