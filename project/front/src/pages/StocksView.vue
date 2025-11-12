<script setup lang="ts">
import StocksTable from "@/components/StocksTable.vue";
</script>

<template>
    <div class="stocks-view">
        <div class="container mx-auto p-6">
            <h1 class="text-3xl font-bold text-white mb-6">
                Tabla de Acciones
            </h1>
            <p class="text-gray-400 mb-4">
                Vista general de las acciones en tu portafolio
            </p>
            <StocksTable :portfolio-id="1" />
        </div>
    </div>
</template>

<style scoped>
.stocks-view {
    min-height: 100vh;
    padding: 2rem 0;
}

.container {
    max-width: 1200px;
}
</style>
