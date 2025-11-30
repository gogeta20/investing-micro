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
            <router-link v-if="!slotProps.data.error" :to="`/stock/${slotProps.data.symbol}`" class="stock-name-link">
              {{ slotProps.data.name }}
            </router-link>
            <span v-else class="stock-name">{{ slotProps.data.name }}</span>
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
            <div v-if="!slotProps.data.error" class="actions-container">
              <Button
                icon="pi pi-ellipsis-v"
                class="p-button-text p-button-rounded actions-button"
                @click="toggleMenu($event, slotProps.data)"
                aria-haspopup="true"
                aria-controls="overlay_menu"
              />
            </div>
            <span v-else class="no-data">-</span>
          </template>
        </Column>
      </DataTable>
    </div>

    <!-- Modal de Valoración -->
    <StockValuationModal :symbol="selectedSymbol" v-model:visible="valuationModalVisible" />

    <!-- Menú dropdown (único para todas las filas) -->
    <Menu
      ref="menu"
      id="overlay_menu"
      :model="menuItems"
      :popup="true"
    />
  </div>
</template>

<script setup lang="ts">
import StockValuationModal from "@/components/StockValuationModal.vue";
import { DeleteStockUseCase } from "@/modules/stock/application/useCase/delete/DeleteStock/DeleteStockUseCase";
import { CurrentStateUseCase, type StockCurrent } from "@/modules/stock/application/useCase/get/CurrentState/CurrentStateUseCase";
import Button from "primevue/button";
import Column from "primevue/column";
import DataTable from "primevue/datatable";
import Menu from "primevue/menu";
import { useConfirm } from "primevue/useconfirm";
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";

const stocks = ref<StockCurrent[]>([]);
const loading = ref<boolean>(false);
const error = ref<string | null>(null);
const updatedAt = ref<string | null>(null);
const valuationModalVisible = ref<boolean>(false);
const selectedSymbol = ref<string>("");
const menu = ref<InstanceType<typeof Menu>>();
const confirm = useConfirm();
const router = useRouter();
const currentStock = ref<StockCurrent | null>(null);
const menuItems = ref<any[]>([]);

const loadStocks = async () => {
  loading.value = true;
  error.value = null;

  try {
    const response = await CurrentStateUseCase();

    if (Array.isArray(response.data)) {
      stocks.value = response.data;
      updatedAt.value = response.updated_at;
    } else if (response.data && typeof response.data === "object" && "error" in response.data) {
      error.value = (response.data as any).error;
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

const toggleMenu = (event: Event, stock: StockCurrent) => {
  // Guardar el stock actual antes de abrir el menú
  currentStock.value = stock;
  // Actualizar los items del menú con el stock actual
  menuItems.value = getMenuItems(stock);
  // Abrir el menú
  menu.value?.toggle(event);
};

const getMenuItems = (stock: StockCurrent) => {
  return [
    {
      label: "Ver",
      icon: "pi pi-eye",
      command: () => {
        if (currentStock.value) {
          router.push(`/stock/${currentStock.value.symbol}`);
        }
      },
    },
    {
      label: "Eliminar",
      icon: "pi pi-trash",
      command: () => {
        if (currentStock.value) {
          handleDeleteStock(currentStock.value);
        }
      },
    },
  ];
};

const handleDeleteStock = (stock: StockCurrent) => {
  confirm.require({
    message: `¿Estás seguro de que deseas eliminar la acción ${stock.symbol} (${stock.name})?`,
    header: "Confirmar eliminación",
    icon: "pi pi-exclamation-triangle",
    acceptClass: "p-button-danger",
    accept: async () => {
      try {
        const payload: { id?: number; symbol?: string } = {};
        if (stock.id) {
          payload.id = stock.id;
        } else {
          payload.symbol = stock.symbol;
        }

        await DeleteStockUseCase(payload);
        // Recargar la tabla después de eliminar
        await loadStocks();
      } catch (err: any) {
        console.error("Error eliminando acción:", err);
        error.value =
          err.response?.data?.error ||
          err.response?.data?.message ||
          err.message ||
          "Error al eliminar la acción";
      }
    },
  });
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

.stock-name-link {
  font-weight: 500;
  color: var(--tokyo-blue);
  text-decoration: none;
  transition: color 0.2s ease;

  &:hover {
    color: var(--tokyo-cyan);
    text-decoration: underline;
  }
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

.actions-container {
  display: flex;
  align-items: center;
  justify-content: center;
}

.actions-button {
  color: var(--tokyo-fg) !important;

  &:hover {
    background-color: var(--tokyo-bg-tertiary) !important;
  }
}

// Los estilos del menú dropdown están en assets/scss/primevue-overrides.scss
// para que tengan mayor especificidad sobre los estilos inyectados por PrimeVue

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
