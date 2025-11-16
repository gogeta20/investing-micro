<template>
  <div class="analysis-view">
    <div class="analysis-container">
      <h1 class="analysis-title">Análisis Diario</h1>
      <p class="analysis-subtitle">Comparativa de hoy vs ayer por acción</p>

      <div v-if="loading" class="loading-container">
        <p>Cargando análisis...</p>
      </div>

      <div v-else-if="error" class="error-container">
        <p class="error-message">{{ error }}</p>
        <button @click="loadData" class="retry-button">Reintentar</button>
      </div>

      <DataTable
        v-else
        :value="rows"
        tableStyle="min-width: 70rem"
        paginator
        :rows="10"
        :rowsPerPageOptions="[10, 20, 50]"
        class="p-datatable-sm"
      >
        <Column field="name" header="Name" sortable />
        <Column field="symbol" header="Symbol" sortable />
        <Column field="price_today" header="Precio Hoy" sortable>
          <template #body="slotProps">
            ${{ formatPrice(slotProps.data.price_today) }}
          </template>
        </Column>
        <Column field="price_yesterday" header="Precio Ayer" sortable>
          <template #body="slotProps">
            ${{ formatPrice(slotProps.data.price_yesterday) }}
          </template>
        </Column>
        <Column field="change_percent" header="Variación" sortable>
          <template #body="slotProps">
            <span :class="getVariationClass(slotProps.data.change_percent)">
              {{ formatPercent(slotProps.data.change_percent) }}
            </span>
          </template>
        </Column>
        <Column field="trend" header="Tendencia" sortable>
          <template #body="slotProps">
            <span :class="['trend-badge', slotProps.data.trend === 'up' ? 'up' : 'down']">
              {{ slotProps.data.trend === 'up' ? 'Sube' : 'Baja' }}
            </span>
          </template>
        </Column>
        <Column field="today_date" header="Fecha Hoy" sortable />
        <Column field="yesterday_date" header="Fecha Ayer" sortable />
      </DataTable>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import HttpClientDjango from "@/core/http/HttpClientDjango";

type Trend = "up" | "down";

interface AnalysisRow {
  stock_id: number;
  name: string;
  symbol: string;
  price_today: string;       // viene como string
  price_yesterday: string;   // viene como string
  change_percent: string;    // viene como string, ej: "-0.20"
  trend: Trend;
  today_date: string;        // YYYY-MM-DD
  yesterday_date: string;    // YYYY-MM-DD
}

interface AnalysisResponse {
  data: AnalysisRow[];
}

const rows = ref<AnalysisRow[]>([]);
const loading = ref<boolean>(false);
const error = ref<string | null>(null);

const loadData = async () => {
  loading.value = true;
  error.value = null;
  try {
    const response = await HttpClientDjango.get<AnalysisResponse>("/api/analysis/daily");
    if (Array.isArray(response.data?.data)) {
      rows.value = response.data.data;
    } else {
      error.value = "Formato de respuesta inválido";
      rows.value = [];
    }
  } catch (err: any) {
    console.error("Error cargando análisis:", err);
    error.value =
      err.response?.data?.error ||
      err.response?.data?.data?.error ||
      err.message ||
      "Error al cargar el análisis";
    rows.value = [];
  } finally {
    loading.value = false;
  }
};

const formatPrice = (value: string | number | undefined): string => {
  if (value === undefined || value === null) return "0.00";
  const num = typeof value === "string" ? parseFloat(value) : value;
  if (isNaN(num)) return "0.00";
  return num.toFixed(2);
};

const formatPercent = (value: string | number | undefined): string => {
  if (value === undefined || value === null) return "0.00%";
  const num = typeof value === "string" ? parseFloat(value) : value;
  if (isNaN(num)) return "0.00%";
  const sign = num >= 0 ? "+" : "";
  return `${sign}${num.toFixed(2)}%`;
};

const getVariationClass = (value: string | number | undefined): string => {
  const num = typeof value === "string" ? parseFloat(value) : (value ?? 0);
  return num >= 0 ? "positive" : "negative";
};

onMounted(() => {
  loadData();
});
</script>

<style scoped lang="scss">
.analysis-view {
  min-height: calc(100vh - var(--header-height));
  padding: 2rem;
  background-color: var(--tokyo-bg);
}

.analysis-container {
  max-width: 1400px;
  margin: 0 auto;
}

.analysis-title {
  font-size: 2rem;
  font-weight: 600;
  color: var(--tokyo-fg);
  margin-bottom: 0.5rem;
}

.analysis-subtitle {
  color: var(--tokyo-fg-secondary);
  margin-bottom: 1.5rem;
  font-size: 0.95rem;
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

.positive {
  color: var(--tokyo-green);
  font-weight: 600;
}
.negative {
  color: var(--tokyo-red);
  font-weight: 600;
}

.trend-badge {
  font-size: 0.85rem;
  font-weight: 500;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  display: inline-block;
}
.trend-badge.up {
  color: var(--tokyo-green);
  background-color: rgba(158, 206, 106, 0.15);
}
.trend-badge.down {
  color: var(--tokyo-red);
  background-color: rgba(247, 118, 142, 0.15);
}

/* PrimeVue DataTable */
:deep(.p-datatable) {
  background-color: var(--tokyo-bg-secondary);
  color: var(--tokyo-fg);
  border: 1px solid var(--tokyo-bg-tertiary);
  border-radius: var(--border-radius);
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
</style>
