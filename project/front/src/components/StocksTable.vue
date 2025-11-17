<template>
    <div class="stocks-table-container">
        <div v-if="loading" class="loading-container">
            <p>Cargando datos de acciones...</p>
        </div>

        <div v-else-if="error" class="error-container">
            <p class="error-message">{{ error }}</p>
            <button @click="loadStocks" class="retry-button">Reintentar</button>
        </div>

        <DataTable
            v-else
            :value="stocks"
            tableStyle="min-width: 80rem"
            paginator
            :rows="10"
            :rowsPerPageOptions="[10, 20, 50]"
            class="p-datatable-sm"
        >
            <Column field="name" header="Nombre" sortable>
                <template #body="slotProps">
                    <span class="stock-name">{{ slotProps.data.name }}</span>
                </template>
            </Column>
            <Column field="symbol" header="Símbolo" sortable></Column>
            <Column
                field="current.price"
                header="Precio Actual"
                sortable
            >
                <template #body="slotProps">
                    ${{ formatPrice(slotProps.data.current?.price) }}
                </template>
            </Column>
            <Column
                field="current.recorded_at"
                header="Última Consulta"
                sortable
            >
                <template #body="slotProps">
                    {{ formatDate(slotProps.data.current?.recorded_at) }}
                </template>
            </Column>
            <Column
                field="last_snapshot.price"
                header="Último Snapshot"
                sortable
            >
                <template #body="slotProps">
                    <span v-if="slotProps.data.last_snapshot">
                        ${{ formatPrice(slotProps.data.last_snapshot.price) }}
                    </span>
                    <span v-else class="no-data">N/A</span>
                </template>
            </Column>
            <Column
                field="last_snapshot.recorded_at"
                header="Fecha Snapshot"
                sortable
            >
                <template #body="slotProps">
                    <span v-if="slotProps.data.last_snapshot">
                        {{
                            formatDate(slotProps.data.last_snapshot.recorded_at)
                        }}
                    </span>
                    <span v-else class="no-data">N/A</span>
                </template>
            </Column>
            <Column header="Variación" sortable>
                <template #body="slotProps">
                    <span
                        v-if="
                            slotProps.data.last_snapshot &&
                            slotProps.data.current?.price
                        "
                        :class="
                            getVariationClass(
                                slotProps.data.current.price,
                                slotProps.data.last_snapshot.price
                            )
                        "
                    >
                        {{
                            formatVariation(
                                slotProps.data.current.price,
                                slotProps.data.last_snapshot.price
                            )
                        }}
                    </span>
                    <span v-else class="no-data">N/A</span>
                </template>
            </Column>
            <Column header="Acciones">
                <template #body="slotProps">
                    <button
                        @click="openValuationModal(slotProps.data.symbol)"
                        class="valuation-button"
                        title="Ver valoración"
                    >
                        Valoración
                    </button>
                </template>
            </Column>
        </DataTable>

        <!-- Modal de Valoración -->
        <StockValuationModal
            :symbol="selectedSymbol"
            v-model:visible="valuationModalVisible"
        />
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import HttpClientDjango from "@/core/http/HttpClientDjango";
import StockValuationModal from "@/components/StockValuationModal.vue";

interface StockOverview {
    portfolio_id: number;
    symbol: string;
    name: string;
    current: {
        price: number;
        recorded_at: string;
    };
    last_snapshot: {
        price: number | string;
        recorded_at: string;
    } | null;
}

interface StocksResponse {
    data: StockOverview[];
}

const stocks = ref<StockOverview[]>([]);
const loading = ref<boolean>(false);
const error = ref<string | null>(null);
const valuationModalVisible = ref<boolean>(false);
const selectedSymbol = ref<string>("");

// Por defecto portfolio_id = 1, puede ser pasado como prop
const props = defineProps<{
    portfolioId?: number;
}>();

const loadStocks = async () => {
    loading.value = true;
    error.value = null;

    try {
        const portfolioId = props.portfolioId || 1;
        const response = await HttpClientDjango.get<StocksResponse>(
            `/api/stocks/overview/list?portfolio_id=${portfolioId}`
        );

        // Verificar si la respuesta tiene un error
        if (response.data.data && typeof response.data.data === "object" && "error" in response.data.data) {
            error.value = (response.data.data as any).error;
            stocks.value = [];
            return;
        }

        // Verificar que sea un array
        if (Array.isArray(response.data.data)) {
            stocks.value = response.data.data;
        } else {
            error.value = "Formato de respuesta inválido";
            stocks.value = [];
        }
    } catch (err: any) {
        console.error("Error cargando overview:", err);
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

const formatPrice = (price: number | string | undefined): string => {
    if (!price) return "0.00";
    const numPrice = typeof price === "string" ? parseFloat(price) : price;
    if (isNaN(numPrice)) return "0.00";
    return numPrice.toFixed(2);
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

const formatVariation = (currentPrice: number, lastPrice: number | string): string => {
    const numLastPrice = typeof lastPrice === "string" ? parseFloat(lastPrice) : lastPrice;
    if (isNaN(numLastPrice) || numLastPrice === 0) return "0.00%";
    const variation = ((currentPrice - numLastPrice) / numLastPrice) * 100;
    const sign = variation >= 0 ? "+" : "";
    return `${sign}${variation.toFixed(2)}%`;
};

const getVariationClass = (currentPrice: number, lastPrice: number | string): string => {
    const numLastPrice = typeof lastPrice === "string" ? parseFloat(lastPrice) : lastPrice;
    if (isNaN(numLastPrice) || numLastPrice === 0) return "";
    const variation = ((currentPrice - numLastPrice) / numLastPrice) * 100;
    return variation >= 0 ? "positive" : "negative";
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
.stocks-table-container {
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

.no-data {
    color: var(--tokyo-fg-dim);
    font-style: italic;
}

.stock-name {
    font-weight: 500;
    color: var(--tokyo-fg);
}

.positive {
    color: var(--tokyo-green);
    font-weight: 600;
}

.negative {
    color: var(--tokyo-red);
    font-weight: 600;
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
