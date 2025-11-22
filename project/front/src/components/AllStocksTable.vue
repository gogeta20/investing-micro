<template>
  <div class="all-stocks-table-container">
    <div v-if="loading" class="loading-container">
      <p>Cargando datos de acciones...</p>
    </div>

    <div v-else-if="error" class="error-container">
      <p class="error-message">{{ error }}</p>
      <button @click="loadStocks" class="retry-button">Reintentar</button>
    </div>

    <div v-else>
      <div v-if="updatedAt" class="updated-info">
        <p>Última actualización: {{ formatDate(updatedAt) }}</p>
      </div>

      <DataTable :value="stocks" tableStyle="min-width: 60rem" paginator :rows="20"
        :rowsPerPageOptions="[10, 20, 50, 100]" class="p-datatable-sm">
        <Column field="name" header="Nombre" sortable>
          <template #body="slotProps">
            <span class="stock-name">{{ slotProps.data.name }}</span>
          </template>
        </Column>
        <Column field="symbol" header="Símbolo" sortable></Column>
        <Column field="price" header="Precio Actual" sortable>
          <template #body="slotProps">
            <span v-if="slotProps.data.error" class="error-badge">
              Error
            </span>
            <span v-else>
              ${{ formatPrice(slotProps.data.price) }}
            </span>
          </template>
        </Column>
        <Column field="recorded_at" header="Última Consulta" sortable>
          <template #body="slotProps">
            <span v-if="slotProps.data.error" class="no-data">
              N/A
            </span>
            <span v-else>
              {{ formatDate(slotProps.data.recorded_at) }}
            </span>
          </template>
        </Column>
        <Column header="Estado">
          <template #body="slotProps">
            <span v-if="slotProps.data.error" class="error-badge">
              {{ slotProps.data.error }}
            </span>
            <span v-else class="success-badge">
              OK
            </span>
          </template>
        </Column>
        <Column header="Acciones">
          <template #body="slotProps">
            <button v-if="!slotProps.data.error" @click="openValuationModal(slotProps.data.symbol)"
              class="valuation-button" title="Ver valoración">
              Valoración
            </button>
            <span v-else class="no-data">-</span>
          </template>
        </Column>
      </DataTable>
    </div>

    <!-- Modal de Valoración -->
    <StockValuationModal :symbol="selectedSymbol" v-model:visible="valuationModalVisible" />
  </div>
</template>

<script setup lang="ts">
import StockValuationModal from "@/components/StockValuationModal.vue";
import HttpClientDjango from "@/core/http/HttpClientDjango";
import Column from "primevue/column";
import DataTable from "primevue/datatable";
import { onMounted, ref } from "vue";

interface StockCurrent {
  symbol: string;
  name: string;
  price?: number;
  recorded_at?: string;
  error?: string;
}

interface CurrentStocksResponse {
  portfolio_id: number | null;
  updated_at: string;
  data: StockCurrent[];
}

const stocks = ref<StockCurrent[]>([]);
const loading = ref<boolean>(false);
const error = ref<string | null>(null);
const updatedAt = ref<string | null>(null);
const valuationModalVisible = ref<boolean>(false);
const selectedSymbol = ref<string>("");

const loadStocks = async () => {
  loading.value = true;
  error.value = null;

  try {
    const response = await HttpClientDjango.get<CurrentStocksResponse>(
      "/api/stock/current/state"
    );
    if (Array.isArray(response.data.data)) {
      stocks.value = response.data.data;
      updatedAt.value = response.data.updated_at;
    } else if (response.data.data && typeof response.data.data === "object" && "error" in response.data.data) {
      error.value = (response.data.data as any).error;
      stocks.value = [];
    } else {
      error.value = "Formato de respuesta inválido";
      stocks.value = [];
    }
  } catch (err: any) {
    console.error("Error cargando acciones:", err);
    error.value =
      err.response?.data?.error ||
      err.response?.data?.data?.error ||
      err.message ||
      "Error al cargar los datos de acciones";
    stocks.value = [];
  } finally {
    loading.value = false;
  }
};

const formatPrice = (price: number | undefined): string => {
  if (!price) return "0.00";
  return price.toFixed(2);
};

const formatDate = (dateString: string | undefined): string => {
  if (!dateString) return "N/A";
  try {
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return "N/A";
    return date.toLocaleString("es-ES", {
      year: "numeric",
      month: "2-digit",
      day: "2-digit",
      hour: "2-digit",
      minute: "2-digit",
    });
  } catch (e) {
    return "N/A";
  }
};

const openValuationModal = (symbol: string) => {
  selectedSymbol.value = symbol;
  valuationModalVisible.value = true;
};

onMounted(() => {
  loadStocks();
});
</script>

<style scoped lang="scss">
.all-stocks-table-container {
  padding: 1rem;
}

.loading-container,
.error-container {
  text-align: center;
  padding: 2rem;
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

  &:active {
    background-color: var(--primary-active);
  }
}

.updated-info {
  margin-bottom: 1rem;
  padding: 0.75rem;
  background-color: var(--tokyo-bg-tertiary);
  border-radius: var(--border-radius);
  color: var(--tokyo-fg-secondary);
  font-size: 0.9rem;
}

.stock-name {
  font-weight: 500;
  color: var(--tokyo-fg);
}

.no-data {
  color: var(--tokyo-fg-dim);
  font-style: italic;
}

.error-badge {
  color: var(--tokyo-red);
  font-size: 0.85rem;
  font-weight: 500;
  padding: 0.25rem 0.5rem;
  background-color: rgba(247, 118, 142, 0.15);
  border-radius: 4px;
  display: inline-block;
}

.success-badge {
  color: var(--tokyo-green);
  font-size: 0.85rem;
  font-weight: 500;
  padding: 0.25rem 0.5rem;
  background-color: rgba(158, 206, 106, 0.15);
  border-radius: 4px;
  display: inline-block;
}

.valuation-button {
  padding: 0.4rem 0.8rem;
  background-color: var(--tokyo-blue);
  color: var(--tokyo-bg);
  border: none;
  border-radius: var(--border-radius);
  cursor: pointer;
  font-weight: 500;
  font-size: 0.85rem;
  transition: background-color 0.2s ease;

  &:hover {
    background-color: var(--primary-hover);
  }

  &:active {
    background-color: var(--primary-active);
  }
}

// Estilos para PrimeVue DataTable con tema Tokyo Night
:deep(.p-datatable) {
  background-color: var(--tokyo-bg-secondary);
  color: var(--tokyo-fg);
  border: 1px solid var(--tokyo-bg-tertiary);
  border-radius: var(--border-radius);
}

:deep(.p-datatable-header) {
  background-color: var(--tokyo-bg-secondary);
  color: var(--tokyo-fg);
  border-bottom: 1px solid var(--tokyo-bg-tertiary);
}

:deep(.p-datatable-thead > tr > th) {
  background-color: var(--tokyo-bg-tertiary);
  color: var(--tokyo-fg);
  border-bottom: 1px solid var(--tokyo-bg-tertiary);
  font-weight: 600;
}

:deep(.p-datatable-tbody > tr) {
  background-color: var(--tokyo-bg-secondary);
  color: var(--tokyo-fg);
  border-bottom: 1px solid var(--tokyo-bg-tertiary);

  &:hover {
    background-color: var(--tokyo-bg-tertiary);
  }
}

:deep(.p-datatable-tbody > tr > td) {
  border-bottom: 1px solid var(--tokyo-bg-tertiary);
  color: var(--tokyo-fg);
}

:deep(.p-paginator) {
  background-color: var(--tokyo-bg-secondary);
  color: var(--tokyo-fg);
  border-top: 1px solid var(--tokyo-bg-tertiary);
  border-radius: 0 0 var(--border-radius) var(--border-radius);
}

:deep(.p-paginator .p-paginator-page) {
  color: var(--tokyo-fg);

  &.p-highlight {
    background-color: var(--tokyo-blue);
    color: var(--tokyo-bg);
  }

  &:hover {
    background-color: var(--tokyo-bg-tertiary);
  }
}

:deep(.p-paginator .p-paginator-prev),
:deep(.p-paginator .p-paginator-next) {
  color: var(--tokyo-fg);

  &:hover {
    background-color: var(--tokyo-bg-tertiary);
  }
}
</style>
