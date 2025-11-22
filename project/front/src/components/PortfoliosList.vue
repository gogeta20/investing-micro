<template>
  <div class="portfolios-list-container">
    <div v-if="loading" class="loading-container">
      <p>Cargando portafolios...</p>
    </div>

    <div v-else-if="error" class="error-container">
      <p class="error-message">{{ error }}</p>
      <button @click="loadPortfolios" class="retry-button">Reintentar</button>
    </div>

    <div v-else-if="portfolios.length === 0" class="empty-container">
      <p class="empty-message">No hay portafolios creados</p>
      <router-link to="/portfolio/create" class="create-link">
        <i class="pi pi-plus"></i> Crear tu primer portafolio
      </router-link>
    </div>

    <div v-else class="portfolios-grid">
      <div
        v-for="portfolio in portfolios"
        :key="portfolio.id"
        class="portfolio-card"
        @click="viewPortfolio(portfolio.id)"
      >
        <div class="portfolio-card-header">
          <h3 class="portfolio-card-title">
            {{ portfolio.name || `Portafolio #${portfolio.id}` }}
          </h3>
          <span class="portfolio-card-id">ID: {{ portfolio.id }}</span>
        </div>
        <div class="portfolio-card-body">
          <div class="portfolio-stats">
            <div class="stat-item">
              <span class="stat-label">Acciones</span>
              <span class="stat-value">{{ portfolio.stocks_count || 0 }}</span>
            </div>
            <div class="stat-item">
              <span class="stat-label">Creado</span>
              <span class="stat-value">{{ formatDate(portfolio.created_at) }}</span>
            </div>
          </div>
          <div class="portfolio-stocks-preview" v-if="portfolio.stocks && portfolio.stocks.length > 0">
            <span
              v-for="(stock, index) in portfolio.stocks.slice(0, 3)"
              :key="stock.symbol"
              class="stock-badge"
            >
              {{ stock.symbol }}
            </span>
            <span v-if="portfolio.stocks.length > 3" class="stock-badge more">
              +{{ portfolio.stocks.length - 3 }} más
            </span>
          </div>
        </div>
        <div class="portfolio-card-footer">
          <button @click.stop="viewPortfolio(portfolio.id)" class="view-button">
            Ver detalles
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useRouter } from "vue-router";
import { onMounted, ref } from "vue";
import HttpClientDjango from "@/core/http/HttpClientDjango";

interface PortfolioStock {
  id: number;
  symbol: string;
}

interface Portfolio {
  id: number;
  name?: string;
  stocks_count?: number;
  created_at?: string;
  stocks?: PortfolioStock[];
}

interface PortfoliosResponse {
  data: Portfolio[];
}

const router = useRouter();
const portfolios = ref<Portfolio[]>([]);
const loading = ref<boolean>(false);
const error = ref<string | null>(null);

const loadPortfolios = async () => {
  loading.value = true;
  error.value = null;

  try {
    // MOCK: Simular respuesta del backend
    console.log("MOCK GET /api/portfolio/list");

    // Simular delay de red
    await new Promise((resolve) => setTimeout(resolve, 500));

    // Mock response con un portafolio fake
    const mockPortfolios: Portfolio[] = [
      {
        id: 1,
        name: "Portafolio Principal",
        stocks_count: 5,
        created_at: new Date().toISOString(),
        stocks: [
          { id: 1, symbol: "AAPL" },
          { id: 2, symbol: "MSFT" },
          { id: 3, symbol: "GOOGL" },
          { id: 4, symbol: "AMZN" },
          { id: 5, symbol: "TSLA" },
        ],
      },
    ];

    portfolios.value = mockPortfolios;

    // TODO: Cuando el endpoint esté listo, descomentar esto:
    /*
    const response = await HttpClientDjango.get<PortfoliosResponse>(
      "/api/portfolio/list"
    );

    if (Array.isArray(response.data.data)) {
      portfolios.value = response.data.data;
    } else {
      error.value = "Formato de respuesta inválido";
      portfolios.value = [];
    }
    */
  } catch (err: any) {
    console.error("Error cargando portafolios:", err);
    error.value =
      err.response?.data?.error ||
      err.response?.data?.message ||
      err.message ||
      "Error al cargar los portafolios";
    portfolios.value = [];
  } finally {
    loading.value = false;
  }
};

const formatDate = (dateString: string | undefined): string => {
  if (!dateString) return "N/A";
  try {
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return "N/A";
    return date.toLocaleDateString("es-ES", {
      year: "numeric",
      month: "2-digit",
      day: "2-digit",
    });
  } catch (e) {
    return "N/A";
  }
};

const viewPortfolio = (portfolioId: number) => {
  // Por ahora redirigir a /portfolio con el ID como query param
  // O podrías tener una ruta /portfolio/:id
  router.push({ path: "/portfolio", query: { id: portfolioId } });
};

onMounted(() => {
  loadPortfolios();
});

// Exponer método para refrescar desde el padre
defineExpose({
  loadPortfolios,
});
</script>

<style scoped lang="scss">
.portfolios-list-container {
  padding: 1rem;
}

.loading-container,
.error-container,
.empty-container {
  text-align: center;
  padding: 3rem 2rem;
  color: var(--tokyo-fg);
}

.error-message {
  color: var(--tokyo-red);
  margin-bottom: 1rem;
}

.retry-button {
  padding: 0.5rem 1rem;
  background-color: var(--tokyo-blue);
  color: var(--tokyo-bg);
  border: none;
  border-radius: var(--border-radius);
  cursor: pointer;
  font-weight: 500;
  transition: background-color 0.2s ease;

  &:hover {
    background-color: var(--primary-hover);
  }
}

.empty-message {
  color: var(--tokyo-fg-secondary);
  font-size: 1.1rem;
  margin-bottom: 1rem;
}

.create-link {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  background-color: var(--tokyo-blue);
  color: var(--tokyo-bg);
  text-decoration: none;
  border-radius: var(--border-radius);
  font-weight: 500;
  transition: background-color 0.2s ease;

  &:hover {
    background-color: var(--primary-hover);
  }

  i {
    font-size: 0.9rem;
  }
}

.portfolios-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 1.5rem;
}

.portfolio-card {
  background-color: var(--tokyo-bg-secondary);
  border: 1px solid var(--tokyo-bg-tertiary);
  border-radius: var(--border-radius);
  padding: 1.5rem;
  cursor: pointer;
  transition: all 0.2s ease;
  display: flex;
  flex-direction: column;
  gap: 1rem;

  &:hover {
    border-color: var(--tokyo-blue);
    box-shadow: 0 4px 12px rgba(122, 162, 247, 0.15);
    transform: translateY(-2px);
  }
}

.portfolio-card-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 1rem;
}

.portfolio-card-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: var(--tokyo-fg);
  margin: 0;
  flex: 1;
}

.portfolio-card-id {
  font-size: 0.85rem;
  color: var(--tokyo-fg-dim);
  background-color: var(--tokyo-bg-tertiary);
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
}

.portfolio-card-body {
  flex: 1;
}

.portfolio-stats {
  display: flex;
  gap: 1.5rem;
  margin-bottom: 1rem;
}

.stat-item {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.stat-label {
  font-size: 0.85rem;
  color: var(--tokyo-fg-secondary);
}

.stat-value {
  font-size: 1.1rem;
  font-weight: 600;
  color: var(--tokyo-fg);
}

.portfolio-stocks-preview {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-top: 1rem;
}

.stock-badge {
  display: inline-block;
  padding: 0.35rem 0.75rem;
  background-color: var(--tokyo-bg-tertiary);
  color: var(--tokyo-fg);
  border-radius: 4px;
  font-size: 0.85rem;
  font-weight: 500;

  &.more {
    background-color: rgba(122, 162, 247, 0.2);
    color: var(--tokyo-blue);
  }
}

.portfolio-card-footer {
  display: flex;
  justify-content: flex-end;
  padding-top: 1rem;
  border-top: 1px solid var(--tokyo-bg-tertiary);
}

.view-button {
  padding: 0.5rem 1rem;
  background-color: var(--tokyo-blue);
  color: var(--tokyo-bg);
  border: none;
  border-radius: var(--border-radius);
  cursor: pointer;
  font-weight: 500;
  font-size: 0.9rem;
  transition: background-color 0.2s ease;

  &:hover {
    background-color: var(--primary-hover);
  }
}
</style>
