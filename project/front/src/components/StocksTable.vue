<!-- <template>
  <div>
    <DataTable :value="stocks" tableStyle="min-width: 60rem">
      <Column field="symbol" header="Symbol"></Column>
      <Column field="current.price" header="Precio actual"></Column>
      <Column field="current.recorded_at" header="Última consulta"></Column>
      <Column field="last_snapshot.price" header="Último snapshot"></Column>
      <Column field="last_snapshot.recorded_at" header="Fecha snapshot"></Column>
    </DataTable>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'

const stocks = ref<any[]>([])

onMounted(async () => {
  try {
    const response = await axios.get('/api/stocks/overview')
    stocks.value = response.data.data
  } catch (error) {
    console.error("Error cargando overview:", error)
  }
})
</script> -->
