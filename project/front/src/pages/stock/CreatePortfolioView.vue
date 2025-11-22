<script setup lang="ts">
import CreatePortfolioTable from "@/components/CreatePortfolioTable.vue";
import { useRouter } from "vue-router";
import { ref, onMounted } from "vue";
import HttpClientDjango from "@/core/http/HttpClientDjango";

const router = useRouter();
const tableRef = ref<InstanceType<typeof CreatePortfolioTable> | null>(null);
const portfolioName = ref<string>("");
const loading = ref<boolean>(false);
const error = ref<string | null>(null);
const success = ref<boolean>(false);

interface StockSelection {
  id: number;
  symbol: string;
}

interface CreatePortfolioRequest {
  portfolio_id?: number;
  name?: string;
  stocks: Array<{
    id: number;
    symbol: string;
  }>;
}

interface CreatePortfolioResponse {
  portfolio_id: number;
  message: string;
  stocks: Array<{
    id: number;
    symbol: string;
  }>;
}

const createPortfolio = async () => {
  if (!tableRef.value) {
    error.value = "Error al acceder a las acciones seleccionadas";
    return;
  }

  const selectedStocks = tableRef.value.selectedStocks;

  if (!selectedStocks || selectedStocks.length === 0) {
    error.value = "Debes seleccionar al menos una acción";
    return;
  }

  loading.value = true;
  error.value = null;
  success.value = false;

  try {
    // Preparar datos para enviar
    const payload: CreatePortfolioRequest = {
      name: portfolioName.value || undefined,
      stocks: selectedStocks.map((stock) => ({
        id: stock.id,
        symbol: stock.symbol,
      })),
    };

    const response = await HttpClientDjango.post<CreatePortfolioResponse>(
      "/api/portfolio/create",
      payload
    );

    if (response.data.portfolio_id) {
      success.value = true;
      setTimeout(() => {
        router.push("/portfolio");
      }, 1500);
    } else {
      error.value = "Error al crear el portafolio";
    }
  } catch (err: any) {
    console.error("Error creando portafolio:", err);
    error.value =
      err.response?.data?.error ||
      err.response?.data?.message ||
      err.message ||
      "Error al crear el portafolio";
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  // Cualquier inicialización necesaria
});
</script>

<template>
  <div class="create-portfolio-view">
    <div class="create-portfolio-container">
      <div class="header-section">
        <router-link to="/portfolio" class="back-link">
          <i class="pi pi-arrow-left"></i> Volver a portafolios
        </router-link>
        <h1 class="portfolio-title">Crear Nuevo Portafolio</h1>
        <p class="portfolio-subtitle">Selecciona las acciones que deseas incluir en tu portafolio</p>
      </div>

      <div class="form-section">
        <div class="form-group">
          <label for="portfolio-name" class="form-label">
            Nombre del Portafolio (Opcional)
          </label>
          <input
            id="portfolio-name"
            v-model="portfolioName"
            type="text"
            class="form-input"
            placeholder="Ej: Portafolio Conservador, Tech Stocks, etc."
            maxlength="100"
          />
        </div>
      </div>

      <div class="table-section">
        <CreatePortfolioTable ref="tableRef" />
      </div>

      <div v-if="error" class="error-message-container">
        <p class="error-message-text">{{ error }}</p>
      </div>

      <div v-if="success" class="success-message-container">
        <p class="success-message-text">
          <i class="pi pi-check-circle"></i> Portafolio creado exitosamente. Redirigiendo...
        </p>
      </div>

      <div class="actions-section">
        <button
          @click="createPortfolio"
          :disabled="loading || success"
          class="create-button"
        >
          <span v-if="loading">
            <i class="pi pi-spin pi-spinner"></i> Creando...
          </span>
          <span v-else-if="success">
            <i class="pi pi-check"></i> Creado
          </span>
          <span v-else>
            <i class="pi pi-plus"></i> Crear Portafolio
          </span>
        </button>
        <router-link to="/portfolio" class="cancel-button">
          Cancelar
        </router-link>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
.create-portfolio-view {
  min-height: calc(100vh - var(--header-height));
  padding: 2rem;
  background-color: var(--tokyo-bg);
}

.create-portfolio-container {
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
  margin-bottom: 1rem;
  font-size: 0.95rem;
}

.form-section {
  margin-bottom: 2rem;
  padding: 1.5rem;
  background-color: var(--tokyo-bg-secondary);
  border-radius: var(--border-radius);
  border: 1px solid var(--tokyo-bg-tertiary);
}

.form-group {
  margin-bottom: 1rem;
}

.form-label {
  display: block;
  color: var(--tokyo-fg);
  font-weight: 500;
  margin-bottom: 0.5rem;
  font-size: 0.95rem;
}

.form-input {
  width: 100%;
  max-width: 500px;
  padding: 0.75rem;
  background-color: var(--tokyo-bg);
  color: var(--tokyo-fg);
  border: 1px solid var(--tokyo-bg-tertiary);
  border-radius: var(--border-radius);
  font-size: 0.95rem;
  transition: border-color 0.2s ease, box-shadow 0.2s ease;

  &:focus {
    outline: none;
    border-color: var(--tokyo-blue);
    box-shadow: 0 0 0 0.2rem rgba(122, 162, 247, 0.2);
  }

  &::placeholder {
    color: var(--tokyo-fg-dim);
  }
}

.table-section {
  margin-bottom: 2rem;
}

.error-message-container,
.success-message-container {
  margin-bottom: 1.5rem;
  padding: 1rem;
  border-radius: var(--border-radius);
  animation: fadeIn 0.3s ease;
}

.error-message-container {
  background-color: rgba(247, 118, 142, 0.15);
  border-left: 4px solid var(--tokyo-red);
}

.success-message-container {
  background-color: rgba(158, 206, 106, 0.15);
  border-left: 4px solid var(--tokyo-green);
}

.error-message-text,
.success-message-text {
  color: var(--tokyo-fg);
  margin: 0;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 500;

  i {
    font-size: 1.1rem;
  }
}

.error-message-text {
  color: var(--tokyo-red);
}

.success-message-text {
  color: var(--tokyo-green);
}

.actions-section {
  display: flex;
  gap: 1rem;
  align-items: center;
  justify-content: flex-start;
}

.create-button {
  padding: 0.75rem 1.5rem;
  background-color: var(--tokyo-blue);
  color: var(--tokyo-bg);
  border: none;
  border-radius: var(--border-radius);
  cursor: pointer;
  font-weight: 500;
  font-size: 1rem;
  transition: background-color 0.2s ease;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;

  &:hover:not(:disabled) {
    background-color: var(--primary-hover);
  }

  &:active:not(:disabled) {
    background-color: var(--primary-active);
  }

  &:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }

  i {
    font-size: 0.9rem;
  }
}

.cancel-button {
  padding: 0.75rem 1.5rem;
  background-color: transparent;
  color: var(--tokyo-fg-secondary);
  border: 1px solid var(--tokyo-bg-tertiary);
  border-radius: var(--border-radius);
  text-decoration: none;
  font-weight: 500;
  font-size: 1rem;
  transition: all 0.2s ease;
  display: inline-flex;
  align-items: center;

  &:hover {
    background-color: var(--tokyo-bg-tertiary);
    color: var(--tokyo-fg);
    border-color: var(--tokyo-bg-tertiary);
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
