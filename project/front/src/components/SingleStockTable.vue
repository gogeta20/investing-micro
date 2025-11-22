<template>
  <div class="single-stock-table-container">
    <div v-if="loading" class="loading-container">
      <p>Cargando datos históricos...</p>
    </div>

    <div v-else-if="error" class="error-container">
      <p class="error-message">{{ error }}</p>
      <button @click="loadStockHistory" class="retry-button">Reintentar</button>
    </div>

    <div v-else>
      <div v-if="stockInfo" class="stock-info-header">
        <h2 class="stock-symbol">{{ stockInfo.symbol }}</h2>
        <p class="stock-period">Período: {{ stockInfo.period }}</p>
        <p class="stock-count" v-if="stockInfo.data">
          Total de registros: {{ stockInfo.data.length }}
        </p>
      </div>

      <DataTable
        :value="historyData"
        tableStyle="min-width: 60rem"
        paginator
        :rows="20"
        :rowsPerPageOptions="[10, 20, 50, 100]"
        class="p-datatable-sm"
        sortField="date"
        :sortOrder="-1">
        <Column field="date" header="Fecha" sortable>
          <template #body="slotProps">
            <span class="date-value">{{ formatDate(slotProps.data.date) }}</span>
          </template>
        </Column>
        <Column field="open" header="Apertura" sortable>
          <template #body="slotProps">
            <span class="price-value">${{ formatPrice(slotProps.data.open) }}</span>
          </template>
        </Column>
        <Column field="high" header="Máximo" sortable>
          <template #body="slotProps">
            <span class="price-value price-high">${{ formatPrice(slotProps.data.high) }}</span>
          </template>
        </Column>
        <Column field="low" header="Mínimo" sortable>
          <template #body="slotProps">
            <span class="price-value price-low">${{ formatPrice(slotProps.data.low) }}</span>
          </template>
        </Column>
        <Column field="close" header="Cierre" sortable>
          <template #body="slotProps">
            <span class="price-value price-close">${{ formatPrice(slotProps.data.close) }}</span>
          </template>
        </Column>
        <Column field="price" header="Precio" sortable>
          <template #body="slotProps">
            <span class="price-value">${{ formatPrice(slotProps.data.price) }}</span>
          </template>
        </Column>
        <Column field="volume" header="Volumen" sortable>
          <template #body="slotProps">
            <span class="volume-value">{{ formatVolume(slotProps.data.volume) }}</span>
          </template>
        </Column>
      </DataTable>
    </div>
  </div>
</template>

<script setup lang="ts">
import HttpClientDjango from "@/core/http/HttpClientDjango";
import Column from "primevue/column";
import DataTable from "primevue/datatable";
import { onMounted, ref, watch } from "vue";

interface StockHistoryItem {
  date: string;
  open: number;
  high: number;
  low: number;
  close: number;
  volume: number;
  price: number;
}

interface StockHistoryResponse {
  symbol: string;
  period: string;
  data: StockHistoryItem[];
}

const props = defineProps<{
  symbol: string;
}>();

const historyData = ref<StockHistoryItem[]>([]);
const stockInfo = ref<StockHistoryResponse | null>(null);
const loading = ref<boolean>(false);
const error = ref<string | null>(null);

const loadStockHistory = async () => {
  loading.value = true;
  error.value = null;

  try {
    const response = await HttpClientDjango.get<StockHistoryResponse>(
      `/api/stock/${props.symbol}/history`,
      { period: "1mo" }
    );

    if (response.data.data && Array.isArray(response.data.data)) {
      stockInfo.value = response.data;
      historyData.value = response.data.data;
    } else {
      error.value = "Formato de respuesta inválido";
      historyData.value = [];
    }
  } catch (err: any) {
    console.error("Error cargando datos históricos:", err);
    error.value =
      err.response?.data?.error ||
      err.response?.data?.message ||
      err.message ||
      "Error al cargar los datos históricos";
    historyData.value = [];
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
    return date.toLocaleDateString("es-ES", {
      year: "numeric",
      month: "2-digit",
      day: "2-digit",
    });
  } catch (e) {
    return "N/A";
  }
};

const formatVolume = (volume: number | undefined): string => {
  if (!volume) return "0";
  return volume.toLocaleString("es-ES");
};

onMounted(() => {
  loadStockHistory();
});

watch(
  () => props.symbol,
  () => {
    loadStockHistory();
  }
);
</script>

<style scoped lang="scss">
.single-stock-table-container {
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

.stock-info-header {
  margin-bottom: 1.5rem;
  padding: 1rem;
  background-color: var(--tokyo-bg-tertiary);
  border-radius: var(--border-radius);
  border-left: 4px solid var(--tokyo-blue);
}

.stock-symbol {
  font-size: 1.75rem;
  font-weight: 600;
  color: var(--tokyo-fg);
  margin-bottom: 0.5rem;
}

.stock-period {
  color: var(--tokyo-fg-secondary);
  font-size: 0.95rem;
  margin-bottom: 0.25rem;
}

.stock-count {
  color: var(--tokyo-fg-secondary);
  font-size: 0.9rem;
}

.date-value {
  color: var(--tokyo-fg);
  font-weight: 500;
}

.price-value {
  color: var(--tokyo-fg);
  font-weight: 500;

  &.price-high {
    color: var(--tokyo-green);
  }

  &.price-low {
    color: var(--tokyo-red);
  }

  &.price-close {
    color: var(--tokyo-blue);
  }
}

.volume-value {
  color: var(--tokyo-fg-secondary);
  font-size: 0.9rem;
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
