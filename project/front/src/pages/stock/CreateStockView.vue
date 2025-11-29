<script setup lang="ts">
import { ref } from "vue";
import { useRouter } from "vue-router";
import {
  SearchStockBySymbolUseCase,
  type SearchStockBySymbolResponse,
} from "@/modules/stock/application/useCase/get/SearchStockBySymbol/SearchStockBySymbolUseCase";
import {
  CreateStockUseCase,
  type CreateStockRequest,
} from "@/modules/stock/application/useCase/post/CreateStock/CreateStockUseCase";

const router = useRouter();

// Estados de búsqueda
const symbolInput = ref<string>("");
const searchLoading = ref<boolean>(false);
const searchError = ref<string | null>(null);
const stockData = ref<SearchStockBySymbolResponse | null>(null);

// Estados de creación
const createLoading = ref<boolean>(false);
const createError = ref<string | null>(null);
const createSuccess = ref<boolean>(false);

const searchStock = async () => {
  if (!symbolInput.value.trim()) {
    searchError.value = "Por favor ingresa un símbolo";
    return;
  }

  searchLoading.value = true;
  searchError.value = null;
  stockData.value = null;

  try {
    const response = await SearchStockBySymbolUseCase({
      symbol: symbolInput.value.trim().toUpperCase(),
    });
    stockData.value = response;
  } catch (err: any) {
    console.error("Error buscando stock:", err);
    searchError.value =
      err.response?.data?.error ||
      err.response?.data?.message ||
      err.message ||
      "Error al buscar la acción. Verifica que el símbolo sea correcto.";
    stockData.value = null;
  } finally {
    searchLoading.value = false;
  }
};

const createStock = async () => {
  if (!stockData.value) {
    createError.value = "No hay datos de acción para crear";
    return;
  }

  createLoading.value = true;
  createError.value = null;
  createSuccess.value = false;

  try {
    const payload: CreateStockRequest = {
      symbol: stockData.value.symbol,
      name: stockData.value.name,
      sector: stockData.value.sector,
      currency: stockData.value.currency || "USD",
    };

    await CreateStockUseCase(payload);
    createSuccess.value = true;

    // Limpiar formulario después de éxito
    setTimeout(() => {
      symbolInput.value = "";
      stockData.value = null;
      createSuccess.value = false;
    }, 2000);
  } catch (err: any) {
    console.error("Error creando stock:", err);
    createError.value =
      err.response?.data?.error ||
      err.response?.data?.message ||
      err.message ||
      "Error al crear la acción. Puede que ya exista en el sistema.";
  } finally {
    createLoading.value = false;
  }
};

const handleKeyPress = (event: KeyboardEvent) => {
  if (event.key === "Enter" && !searchLoading.value) {
    searchStock();
  }
};

const resetForm = () => {
  symbolInput.value = "";
  stockData.value = null;
  searchError.value = null;
  createError.value = null;
  createSuccess.value = false;
};
</script>

<template>
  <div class="create-stock-view">
    <div class="create-stock-container">
      <div class="header-section">
        <router-link to="/stocks" class="back-link">
          <i class="pi pi-arrow-left"></i> Volver a acciones
        </router-link>
        <h1 class="stock-title">Agregar Nueva Acción</h1>
        <p class="stock-subtitle">
          Busca una acción por su símbolo y agrégala a tu seguimiento
        </p>
      </div>

      <!-- Sección de búsqueda -->
      <div class="search-section">
        <div class="search-container">
          <div class="search-input-group">
            <label for="symbol-input" class="search-label">
              Símbolo de la Acción
            </label>
            <div class="input-wrapper">
              <input
                id="symbol-input"
                v-model="symbolInput"
                type="text"
                class="search-input"
                placeholder="Ej: AAPL, MSFT, GOOGL"
                :disabled="searchLoading || createLoading"
                @keypress="handleKeyPress"
                maxlength="10"
              />
              <button
                @click="searchStock"
                :disabled="searchLoading || createLoading || !symbolInput.trim()"
                class="search-button"
              >
                <span v-if="searchLoading">
                  <i class="pi pi-spin pi-spinner"></i>
                </span>
                <span v-else>
                  <i class="pi pi-search"></i> Buscar
                </span>
              </button>
            </div>
            <p class="input-hint">
              Ingresa el símbolo de la acción (ej: AAPL para Apple)
            </p>
          </div>
        </div>

        <!-- Mensaje de error de búsqueda -->
        <div v-if="searchError" class="error-message-container">
          <p class="error-message-text">
            <i class="pi pi-exclamation-triangle"></i> {{ searchError }}
          </p>
        </div>
      </div>

      <!-- Sección de información de la acción encontrada -->
      <div v-if="stockData && !searchError" class="stock-info-section">
        <div class="stock-card">
          <div class="stock-card-header">
            <h3 class="stock-card-title">Información de la Acción</h3>
            <button @click="resetForm" class="reset-button" title="Buscar otra acción">
              <i class="pi pi-times"></i>
            </button>
          </div>
          <div class="stock-card-content">
            <div class="stock-info-item">
              <span class="stock-info-label">Símbolo:</span>
              <span class="stock-info-value">{{ stockData.symbol }}</span>
            </div>
            <div class="stock-info-item">
              <span class="stock-info-label">Nombre:</span>
              <span class="stock-info-value">{{ stockData.name }}</span>
            </div>
            <div v-if="stockData.sector" class="stock-info-item">
              <span class="stock-info-label">Sector:</span>
              <span class="stock-info-value">{{ stockData.sector }}</span>
            </div>
            <div class="stock-info-item">
              <span class="stock-info-label">Moneda:</span>
              <span class="stock-info-value">{{ stockData.currency || "USD" }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Mensaje de error de creación -->
      <div v-if="createError" class="error-message-container">
        <p class="error-message-text">
          <i class="pi pi-exclamation-triangle"></i> {{ createError }}
        </p>
      </div>

      <!-- Mensaje de éxito -->
      <div v-if="createSuccess" class="success-message-container">
        <p class="success-message-text">
          <i class="pi pi-check-circle"></i> Acción agregada exitosamente
        </p>
      </div>

      <!-- Botón de agregar -->
      <div v-if="stockData && !searchError" class="actions-section">
        <button
          @click="createStock"
          :disabled="createLoading || createSuccess"
          class="create-button"
        >
          <span v-if="createLoading">
            <i class="pi pi-spin pi-spinner"></i> Agregando...
          </span>
          <span v-else-if="createSuccess">
            <i class="pi pi-check"></i> Agregada
          </span>
          <span v-else>
            <i class="pi pi-plus"></i> Agregar Acción
          </span>
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
.create-stock-view {
  min-height: calc(100vh - var(--header-height));
  padding: 2rem;
  background-color: var(--tokyo-bg);
}

.create-stock-container {
  max-width: 800px;
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

.search-section {
  margin-bottom: 2rem;
}

.search-container {
  padding: 1.5rem;
  background-color: var(--tokyo-bg-secondary);
  border-radius: var(--border-radius);
  border: 1px solid var(--tokyo-bg-tertiary);
}

.search-input-group {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.search-label {
  display: block;
  color: var(--tokyo-fg);
  font-weight: 500;
  font-size: 0.95rem;
}

.input-wrapper {
  display: flex;
  gap: 0.75rem;
  align-items: stretch;
}

.search-input {
  flex: 1;
  padding: 0.75rem;
  background-color: var(--tokyo-bg);
  color: var(--tokyo-fg);
  border: 1px solid var(--tokyo-bg-tertiary);
  border-radius: var(--border-radius);
  font-size: 1rem;
  text-transform: uppercase;
  transition: border-color 0.2s ease, box-shadow 0.2s ease;

  &:focus {
    outline: none;
    border-color: var(--tokyo-blue);
    box-shadow: 0 0 0 0.2rem rgba(122, 162, 247, 0.2);
  }

  &:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }

  &::placeholder {
    color: var(--tokyo-fg-dim);
    text-transform: none;
  }
}

.search-button {
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
  white-space: nowrap;

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

.input-hint {
  color: var(--tokyo-fg-dim);
  font-size: 0.85rem;
  margin: 0;
}

.stock-info-section {
  margin-bottom: 2rem;
}

.stock-card {
  padding: 1.5rem;
  background-color: var(--tokyo-bg-secondary);
  border-radius: var(--border-radius);
  border: 1px solid var(--tokyo-bg-tertiary);
}

.stock-card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid var(--tokyo-bg-tertiary);
}

.stock-card-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: var(--tokyo-fg);
  margin: 0;
}

.reset-button {
  padding: 0.5rem;
  background-color: transparent;
  color: var(--tokyo-fg-secondary);
  border: 1px solid var(--tokyo-bg-tertiary);
  border-radius: var(--border-radius);
  cursor: pointer;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 2rem;
  height: 2rem;

  &:hover {
    background-color: var(--tokyo-bg-tertiary);
    color: var(--tokyo-fg);
    border-color: var(--tokyo-bg-tertiary);
  }

  i {
    font-size: 0.9rem;
  }
}

.stock-card-content {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.stock-info-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem;
  background-color: var(--tokyo-bg);
  border-radius: var(--border-radius);
  border: 1px solid var(--tokyo-bg-tertiary);
}

.stock-info-label {
  color: var(--tokyo-fg-secondary);
  font-weight: 500;
  font-size: 0.9rem;
}

.stock-info-value {
  color: var(--tokyo-fg);
  font-weight: 600;
  font-size: 0.95rem;
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
  background-color: var(--tokyo-green);
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
    background-color: var(--tokyo-green);
    opacity: 0.9;
  }

  &:active:not(:disabled) {
    opacity: 0.8;
  }

  &:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }

  i {
    font-size: 0.9rem;
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
