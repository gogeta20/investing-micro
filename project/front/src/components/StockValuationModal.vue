<template>
  <Dialog :visible="props.visible" @update:visible="emit('update:visible', $event)" :header="modalTitle"
    :style="{ width: '50rem' }" :breakpoints="{ '960px': '75vw', '641px': '90vw' }" :modal="true" :closable="true"
    @hide="onClose">
    <div v-if="loading" class="loading-container">
      <p>Cargando información de valoración...</p>
    </div>

    <div v-else-if="error" class="error-container">
      <p class="error-message">{{ error }}</p>
      <button @click="loadValuation" class="retry-button">Reintentar</button>
    </div>

    <div v-else-if="valuationData" class="valuation-content">
      <!-- Información Básica -->
      <div class="section">
        <h3 class="section-title">Información Básica</h3>
        <div class="info-grid">
          <div class="info-item">
            <span class="info-label">Símbolo:</span>
            <span class="info-value">{{ valuationData.symbol }}</span>
          </div>
          <div class="info-item">
            <span class="info-label">Nombre:</span>
            <span class="info-value">{{ valuationData.name }}</span>
          </div>
          <div class="info-item">
            <span class="info-label">Sector:</span>
            <span class="info-value">{{ valuationData.sector }}</span>
          </div>
          <div class="info-item">
            <span class="info-label">Moneda:</span>
            <span class="info-value">{{ valuationData.currency }}</span>
          </div>
        </div>
      </div>

      <!-- Valoración -->
      <div class="section">
        <h3 class="section-title">Valoración</h3>
        <div class="valuation-grid">
          <div class="valuation-item">
            <span class="valuation-label">Método:</span>
            <span class="valuation-value">{{ formatMethod(valuationData.valuation.method) }}</span>
          </div>
          <div class="valuation-item">
            <span class="valuation-label">Valor Intrínseco:</span>
            <span class="valuation-value highlight">${{ formatPrice(valuationData.valuation.intrinsic_value) }}</span>
          </div>
          <div class="valuation-item">
            <span class="valuation-label">Precio Actual:</span>
            <span class="valuation-value">${{ formatPrice(valuationData.valuation.price_at_valuation) }}</span>
          </div>
          <div class="valuation-item">
            <span class="valuation-label">Descuento:</span>
            <span class="valuation-value" :class="getDiscountClass(valuationData.valuation.discount_percent)">
              {{ formatPercent(valuationData.valuation.discount_percent) }}
            </span>
          </div>
          <div class="valuation-item">
            <span class="valuation-label">Potencial de Subida:</span>
            <span class="valuation-value" :class="getUpsideClass(valuationData.valuation.upside_percent)">
              {{ formatPercent(valuationData.valuation.upside_percent) }}
            </span>
          </div>
          <div class="valuation-item">
            <span class="valuation-label">Brecha de Valor:</span>
            <span class="valuation-value highlight">${{ formatPrice(valuationData.valuation.value_gap) }}</span>
          </div>
          <div class="valuation-item">
            <span class="valuation-label">Estado:</span>
            <span class="valuation-value" :class="getStatusClass(valuationData.valuation.status)">
              {{ formatStatus(valuationData.valuation.status) }}
            </span>
          </div>
          <div class="valuation-item">
            <span class="valuation-label">Calificación:</span>
            <span class="valuation-value grade" :class="getGradeClass(valuationData.valuation.grade)">
              {{ valuationData.valuation.grade }}
            </span>
          </div>
        </div>
      </div>

      <!-- Inputs del Modelo -->
      <div class="section">
        <h3 class="section-title">Parámetros del Modelo</h3>
        <div class="inputs-grid">
          <div class="input-item">
            <span class="input-label">Flujo de Caja Libre (FCF):</span>
            <span class="input-value">${{ formatLargeNumber(valuationData.valuation.inputs.fcf) }}</span>
          </div>
          <div class="input-item">
            <span class="input-label">Tasa de Crecimiento:</span>
            <span class="input-value">{{ formatPercent(valuationData.valuation.inputs.growth) }}</span>
          </div>
          <div class="input-item">
            <span class="input-label">Tasa de Descuento:</span>
            <span class="input-value">{{ formatPercent(valuationData.valuation.inputs.discount_rate) }}</span>
          </div>
          <div class="input-item">
            <span class="input-label">Tasa Terminal:</span>
            <span class="input-value">{{ formatPercent(valuationData.valuation.inputs.terminal_rate) }}</span>
          </div>
        </div>
      </div>

      <!-- Fecha de Creación -->
      <div class="section-footer">
        <p class="created-at">Valoración calculada el: {{ formatDate(valuationData.valuation.created_at) }}</p>
      </div>
    </div>
  </Dialog>
</template>

<script setup lang="ts">
import HttpClientDjango from "@/core/http/HttpClientDjango";
import Dialog from "primevue/dialog";
import { computed, ref, watch } from "vue";

interface ValuationInputs {
  fcf: number;
  growth: number;
  discount_rate: number;
  terminal_rate: number;
}

interface Valuation {
  method: string;
  intrinsic_value: number;
  price_at_valuation: number;
  discount_percent: number;
  upside_percent: number;
  value_gap: number;
  status: string;
  grade: string;
  inputs: ValuationInputs;
  created_at: string;
}

interface ValuationData {
  symbol: string;
  name: string;
  sector: string;
  currency: string;
  valuation: Valuation;
}

interface ValuationResponse {
  data: ValuationData;
}

const props = defineProps<{
  symbol: string;
  visible: boolean;
}>();

const emit = defineEmits<{
  (e: "update:visible", value: boolean): void;
}>();

const loading = ref<boolean>(false);
const error = ref<string | null>(null);
const valuationData = ref<ValuationData | null>(null);

const modalTitle = computed(() => {
  if (valuationData.value) {
    return `Valoración - ${valuationData.value.name} (${valuationData.value.symbol})`;
  }
  return `Valoración - ${props.symbol}`;
});

const loadValuation = async () => {
  if (!props.symbol) return;

  loading.value = true;
  error.value = null;

  try {
    const response = await HttpClientDjango.get<ValuationResponse>(
      `/api/stocks/${props.symbol}/valuation`
    );

    if (response.data.data) {
      valuationData.value = response.data.data;
    } else {
      error.value = "No se encontraron datos de valoración";
    }
  } catch (err: any) {
    console.error("Error cargando valoración:", err);
    error.value =
      err.response?.data?.error ||
      err.message ||
      "Error al cargar los datos de valoración";
  } finally {
    loading.value = false;
  }
};

const onClose = () => {
  emit("update:visible", false);
};

// Formateadores
const formatPrice = (price: number): string => {
  return price.toFixed(2);
};

const formatPercent = (value: number): string => {
  const sign = value >= 0 ? "+" : "";
  return `${sign}${value.toFixed(2)}%`;
};

const formatLargeNumber = (value: number): string => {
  if (value >= 1e9) {
    return (value / 1e9).toFixed(2) + "B";
  } else if (value >= 1e6) {
    return (value / 1e6).toFixed(2) + "M";
  } else if (value >= 1e3) {
    return (value / 1e3).toFixed(2) + "K";
  }
  return value.toFixed(2);
};

const formatDate = (dateString: string): string => {
  try {
    const date = new Date(dateString);
    return date.toLocaleString("es-ES", {
      year: "numeric",
      month: "long",
      day: "numeric",
      hour: "2-digit",
      minute: "2-digit",
    });
  } catch (e) {
    return dateString;
  }
};

const formatMethod = (method: string): string => {
  const methods: Record<string, string> = {
    dcf: "DCF (Descuento de Flujo de Caja)",
    ddm: "DDM (Modelo de Descuento de Dividendos)",
    pe: "Múltiplo P/E",
    pbv: "Múltiplo P/BV",
  };
  return methods[method.toLowerCase()] || method.toUpperCase();
};

const formatStatus = (status: string): string => {
  const statusMap: Record<string, string> = {
    undervalued: "Infravalorada",
    overvalued: "Sobrevalorada",
    fair: "Valor Justo",
  };
  return statusMap[status.toLowerCase()] || status;
};

// Clases CSS dinámicas
const getStatusClass = (status: string): string => {
  const statusLower = status.toLowerCase();
  if (statusLower === "undervalued") return "status-undervalued";
  if (statusLower === "overvalued") return "status-overvalued";
  return "status-fair";
};

const getGradeClass = (grade: string): string => {
  return `grade-${grade.toLowerCase()}`;
};

const getDiscountClass = (discount: number): string => {
  return discount > 0 ? "positive" : "negative";
};

const getUpsideClass = (upside: number): string => {
  return upside > 0 ? "positive" : "negative";
};

// Watch para cargar datos cuando se abre el modal
watch(
  () => props.visible,
  (newValue) => {
    if (newValue && props.symbol) {
      loadValuation();
    }
  },
  { immediate: true }
);
</script>

<style scoped lang="scss">
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
}

.valuation-content {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  background-color: var(--tokyo-bg-secondary);
  padding: 0;
}

.section {
  border-bottom: 1px solid var(--tokyo-bg-tertiary);
  padding: 1rem;
  padding-bottom: 1.5rem;
  background-color: var(--tokyo-bg-secondary);
  border-radius: var(--border-radius);
  margin-bottom: 0.5rem;

  &:last-of-type {
    border-bottom: none;
    margin-bottom: 0;
  }
}

.section-title {
  font-size: 1.1rem;
  font-weight: 600;
  color: var(--tokyo-blue);
  margin-bottom: 1rem;
}

.info-grid,
.valuation-grid,
.inputs-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
  padding: 0.5rem 0;
}

.info-item,
.valuation-item,
.input-item {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
  padding: 0.75rem;
  background-color: var(--tokyo-bg-tertiary);
  border-radius: var(--border-radius);
  transition: background-color 0.2s ease;

  &:hover {
    background-color: rgba(122, 162, 247, 0.1);
  }
}

.info-label,
.valuation-label,
.input-label {
  font-size: 0.85rem;
  color: var(--tokyo-fg-secondary);
  font-weight: 500;
}

.info-value,
.valuation-value,
.input-value {
  font-size: 1rem;
  color: var(--tokyo-fg);
  font-weight: 500;
}

.highlight {
  font-size: 1.1rem;
  font-weight: 600;
  color: var(--tokyo-blue);
}

.positive {
  color: var(--tokyo-green);
  font-weight: 600;
}

.negative {
  color: var(--tokyo-red);
  font-weight: 600;
}

.status-undervalued {
  color: var(--tokyo-green);
  font-weight: 600;
}

.status-overvalued {
  color: var(--tokyo-red);
  font-weight: 600;
}

.status-fair {
  color: var(--tokyo-yellow);
  font-weight: 600;
}

.grade {
  font-size: 1.2rem;
  font-weight: 700;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  display: inline-block;
}

.grade-a,
.grade-b {
  background-color: rgba(158, 206, 106, 0.2);
  color: var(--tokyo-green);
}

.grade-c {
  background-color: rgba(224, 175, 104, 0.2);
  color: var(--tokyo-yellow);
}

.grade-d,
.grade-f {
  background-color: rgba(247, 118, 142, 0.2);
  color: var(--tokyo-red);
}

.section-footer {
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid var(--tokyo-bg-tertiary);
}

.created-at {
  font-size: 0.85rem;
  color: var(--tokyo-fg-dim);
  font-style: italic;
  text-align: center;
}

// Estilos para el Dialog de PrimeVue
:deep(.p-dialog) {
  background-color: var(--tokyo-bg-secondary);
  color: var(--tokyo-fg);
  border: 1px solid var(--tokyo-bg-tertiary);
  border-radius: var(--border-radius);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

:deep(.p-dialog-header) {
  background-color: var(--tokyo-bg-tertiary);
  color: var(--tokyo-fg);
  border-bottom: 1px solid var(--tokyo-bg-tertiary);
  padding: 1.25rem 1.5rem;
  border-radius: var(--border-radius) var(--border-radius) 0 0;
  font-weight: 600;
}

:deep(.p-dialog-content) {
  background-color: var(--tokyo-bg-secondary);
  color: var(--tokyo-fg);
  padding: 1.5rem;
  border-radius: 0 0 var(--border-radius) var(--border-radius);
}

:deep(.p-dialog-header-icon) {
  color: var(--tokyo-fg);
  transition: color 0.2s ease;

  &:hover {
    color: var(--tokyo-red);
    background-color: rgba(247, 118, 142, 0.1);
    border-radius: 4px;
  }
}

// Overlay del modal
:deep(.p-dialog-mask) {
  background-color: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(2px);
}
</style>
