<script setup lang="ts">
import TitleView from "@/components/TitleView.vue";
import { CreateItem } from "@/modules/home/application/useCase/CreateItem";
import { GetItem } from "@/modules/home/application/useCase/GetItem";
import { GetLogs } from "@/modules/home/application/useCase/GetLogs";
import { pokemon } from "@/modules/home/domain/models/Pokemon";
import RepositoryDjangoGet from "@/modules/home/infrastructure/repositories/RepositoryDjangoGet";
import RepositorySymfony from "@/modules/home/infrastructure/repositories/RepositorySymfony";
import RepositorySymfonyGet from "@/modules/home/infrastructure/repositories/RepositorySymfonyGet";
import PokemonForm from "@/pages/test/PokemonForm.vue";
import { ref } from "vue";

const requestStatus = ref(""); // Estado de la petición (success, error, loading)
const responseData = ref([]); // Datos sincronizados
const reponseDjango = ref();

const handleFormSubmit = async (formData: pokemon) => {
  try {
    responseData.value = [];
    requestStatus.value = "loading";
    const useCase = new CreateItem(new RepositorySymfony());
    const response = await useCase.execute(formData);
    if (response.status == 200) {
      requestStatus.value = "success";

      const useCaseGetItem = new GetItem(new RepositorySymfonyGet());
      const responseGetItem = await useCaseGetItem.execute();
      responseData.value = [responseGetItem.data];

      const useCaseLog = new GetLogs(new RepositoryDjangoGet());
      const responseGetlog = await useCaseLog.execute();
      reponseDjango.value = responseGetlog;
    }
  } catch (error) {
    requestStatus.value = "error";
    console.error("Error al crear item:", error);
  }
};
</script>
<template>
  <div class="bg-green page-container">
    <TitleView title="Symfony CQRS" />

    <div class="content text-gray-300 text-xs sm:text-base pt-8 w-full px-4">
      <p class="p-4">
        Vamos a lanzar una petición al backend Symfony y probar la creación de un ítem en
        la base de datos de escritura MySQL. <br />
        Esto lanzará un evento RabbitMQ, y nuestro backend Rust estará pendiente para
        consumirlo y sincronizarlo con la base de datos de lectura MongoDB.<br />
        Por ultimo llamaremos al backend Django para que nos traiga el log del Backend
        Rust.
      </p>
      <div class="p-4 space-y-6">
        <div class="container-form-pokemon">
          <PokemonForm @submit="handleFormSubmit" />

          <div class="response-container">
            <div class="bg-black text-white p-4 rounded border border-gray-100 mb-4">
              <p>
                <span class="text-green-400">$ curl </span>
                <span class="text-blue-400">http://symfony/api/sync</span>
              </p>
              <p v-if="requestStatus === 'loading'" class="text-yellow-400">
                Enviando petición...
              </p>
              <p v-else-if="requestStatus === 'success'" class="text-green-400">
                Petición exitosa: Datos sincronizados.
              </p>
              <p v-else-if="requestStatus === 'error'" class="text-red-400">
                Error en la petición.
              </p>
            </div>

            <!-- Tablas y contenido adicional -->
            <div v-if="responseData.length" class="text-blue-100 p-4 mb-8">
              <h2 class="text-lg font-bold mb-4 text-green-400">Datos en MongoDB</h2>
              <h4 class="mb-1">Collection Pokemon_basic</h4>
              <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-200">
                  <thead>
                    <tr>
                      <th>id</th>
                      <th>nombre</th>
                      <th>peso</th>
                      <th>altura</th>
                      <th>ataque</th>
                      <th>defensa</th>
                      <th>velocidad</th>
                    </tr>
                  </thead>
                  <tbody class="text-blue-300 text-center bg-gray-800">
                    <tr v-for="data in responseData" :key="data.id">
                      <td class="px-4 py-2" data-label="id">{{ data.numeroPokedex }}</td>
                      <td class="px-4 py-2" data-label="nombre">{{ data.nombre }}</td>
                      <td class="px-4 py-2" data-label="peso">{{ data.peso }}</td>
                      <td class="px-4 py-2" data-label="altura">{{ data.altura }}</td>
                      <td class="px-4 py-2" data-label="ataque">{{ data.ataque }}</td>
                      <td class="px-4 py-2" data-label="defensa">{{ data.defensa }}</td>
                      <td class="px-4 py-2" data-label="velocidad">{{ data.velocidad }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div v-if="responseData.length" class="text-blue-100 p-4">
                <h2 class="text-lg font-bold mb-4 text-blue-400">Datos en MySQL</h2>
                <h4 class="mb-1">Tabla Pokemon</h4>
                <div class="overflow-x-auto">
                  <table class="w-full border-collapse border border-gray-200">
                    <thead class="">
                      <tr>
                        <th class="">id</th>
                        <th class="">nombre</th>
                        <th class="">peso</th>
                        <th class="">altura</th>
                      </tr>
                    </thead>
                    <tbody class="text-blue-300 text-center bg-gray-800">
                      <tr v-for="data in responseData" :key="data.id">
                        <td class="px-4 py-2" data-label="id">{{ data.numeroPokedex }}</td>
                        <td class="px-4 py-2" data-label="nombre">{{ data.nombre }}</td>
                        <td class="px-4 py-2" data-label="peso">{{ data.peso }}</td>
                        <td class="px-4 py-2" data-label="altura">{{ data.altura }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div v-if="responseData.length" class="text-blue-100 p-4">
                <h4 class="mb-1">Tabla Estadisticas</h4>
                <div class="overflow-x-auto">
                  <table class="w-full border-collapse border border-gray-200">
                    <thead class="">
                      <tr>
                        <th class="">id</th>
                        <th class="">ataque</th>
                        <th class="">defensa</th>
                        <th class="">velocidad</th>
                      </tr>
                    </thead>
                    <tbody class="text-blue-300 text-center bg-gray-800">
                      <tr v-for="data in responseData" :key="data.id">
                        <td class="px-4 py-2" data-label="id">{{ data.numeroPokedex }}</td>
                        <td class="px-4 py-2" data-label="nombre">{{ data.ataque }}</td>
                        <td class="px-4 py-2" data-label="defensa">{{ data.defensa }}</td>
                        <td class="px-4 py-2" data-label="velocidad">{{ data.velocidad }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- end tables  -->
            </div>
          </div>
          <!-- Log  -->
          <div v-if="responseData.length"
            class="p-4 mb-4 log-container">
            <h2 class="text-lg font-bold mb-4 text-purple-400">Log Rust</h2>
            <p class="text-gray-400">
              {{ reponseDjango }}
            </p>
          </div>
          <!-- Log  -->
        </div>
      </div>

    </div>
  </div>
</template>

<style scoped>
.container-form-pokemon {
  display: grid;
  grid-template-columns: minmax(300px, 0.8fr) minmax(200px, 1fr);
  gap: 1rem;
}

.response-container {
  width: 100%;
}

.tables-container {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

@media (max-width: 768px) {
  .container-form-pokemon {
    grid-template-columns: 1fr;
  }

  .content {
    padding: 0.5rem;
  }

  table {
    font-size: 0.8rem;
  }

  .overflow-x-auto {
    overflow-x: auto;
  }

  .log-container,
  table {
    max-width: 90vw;
    overflow-x: auto;
  }

  thead {
    display: none;
  }

  tr {
    display: flex;
    flex-direction: column;
    margin-bottom: 1rem;
    border: 1px solid #ddd;
    border-radius: 8px;
  }

  td {
    display: flex;
    justify-content: space-between;
    padding: 8px;
    border-bottom: 1px solid #ccc;
  }

  td:last-child {
    border-bottom: none;
  }

  td::before {
    content: attr(data-label);
    font-weight: bold;
    text-transform: capitalize;
  }
}
</style>
