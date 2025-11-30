<script setup lang="ts">
import SingleStockTable from "@/components/SingleStockTable.vue";
import { DeleteStockUseCase } from "@/modules/stock/application/useCase/delete/DeleteStock/DeleteStockUseCase";
import Button from "primevue/button";
import { useConfirm } from "primevue/useconfirm";
import { computed, ref } from "vue";
import { useRoute, useRouter } from "vue-router";

const route = useRoute();
const router = useRouter();
const confirm = useConfirm();
const symbol = computed(() => route.params.symbol as string);
const deleting = ref<boolean>(false);

const handleDeleteStock = () => {
  confirm.require({
    message: `¿Estás seguro de que deseas eliminar la acción ${symbol.value}? Esta acción no se puede deshacer.`,
    header: "Confirmar eliminación",
    icon: "pi pi-exclamation-triangle",
    acceptClass: "p-button-danger",
    accept: async () => {
      deleting.value = true;
      try {
        await DeleteStockUseCase({ symbol: symbol.value });
        // Redirigir a la lista de acciones después de eliminar
        router.push("/stocks");
      } catch (err: any) {
        console.error("Error eliminando acción:", err);
        const errorMessage =
          err.response?.data?.error ||
          err.response?.data?.message ||
          err.message ||
          "Error al eliminar la acción";
        alert(errorMessage);
      } finally {
        deleting.value = false;
      }
    },
  });
};
</script>

<template>
  <div class="single-stock-view">
    <div class="single-stock-container">
      <div class="header-section">
        <router-link to="/stocks" class="back-link">
          <i class="pi pi-arrow-left"></i> Volver a acciones
        </router-link>
        <div class="title-row">
          <div>
            <h1 class="stock-title">Historial de Acción</h1>
            <p class="stock-subtitle">Datos históricos de {{ symbol }}</p>
          </div>
          <Button
            label="Eliminar Acción"
            icon="pi pi-trash"
            class="p-button-danger delete-button"
            :loading="deleting"
            @click="handleDeleteStock"
          />
        </div>
      </div>
      <SingleStockTable :symbol="symbol" />
    </div>
  </div>
</template>

<style scoped lang="scss">
.single-stock-view {
  min-height: calc(100vh - var(--header-height));
  padding: 2rem;
  background-color: var(--tokyo-bg);
}

.single-stock-container {
  max-width: 1400px;
  margin: 0 auto;
}

.header-section {
  margin-bottom: 2rem;
}

.title-row {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 1rem;
  flex-wrap: wrap;
}

.delete-button {
  margin-top: 0.5rem;
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
</style>
