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
      const responseGetItem = await useCaseGetItem.execute()
      responseData.value = [responseGetItem.data]

      const useCaseLog = new GetLogs(new RepositoryDjangoGet());
      const responseGetlog = await useCaseLog.execute()
      reponseDjango.value = responseGetlog
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

    <div class="content text-gray-300 text-xs sm:text-base pt-8 w-6/6">
      <p class="p-4">
        Vamos a lanzar una petición al backend Symfony y probar la creación de un ítem en la base de datos de escritura
        MySQL. <br>
        Esto lanzará un evento RabbitMQ, y nuestro backend Rust estará pendiente para consumirlo y sincronizarlo
        con la base de datos de lectura MongoDB.<br>
        Por ultimo llamaremos al backend Django para que nos traiga el log del Backend Rust.
      </p>
      <div class="p-4 space-y-6">

        <div class="container-form-pokemon">
          <PokemonForm @submit="handleFormSubmit" />

          <div>
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

            <div v-if="responseData.length" class="text-blue-100 p-4 mb-8">
              <h2 class="text-lg font-bold mb-4 text-green-400">Datos en MongoDB</h2>
              <h4 class="mb-1">Collection Pokemon_basic</h4>
              <table class="w-full border-collapse border border-gray-200">
                <thead class="">
                  <tr>
                    <th class="">id</th>
                    <th class="">nombre</th>
                    <th class="">peso</th>
                    <th class="">altura</th>
                    <th class="">ataque</th>
                    <th class="">defensa</th>
                    <th class="">velocidad</th>
                  </tr>
                </thead>
                <tbody class="text-blue-300 text-center bg-gray-800">
                  <tr v-for="data in responseData" :key="data.id">
                    <td class="px-4 py-2 ">{{ data.numeroPokedex }}</td>
                    <td class="px-4 py-2">{{ data.nombre }}</td>
                    <td class="px-4 py-2">{{ data.peso }}</td>
                    <td class="px-4 py-2">{{ data.altura }}</td>
                    <td class="px-4 py-2">{{ data.ataque }}</td>
                    <td class="px-4 py-2">{{ data.defensa }}</td>
                    <td class="px-4 py-2">{{ data.velocidad }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div v-if="responseData.length" class="text-blue-100 p-4">
              <h2 class="text-lg font-bold mb-4 text-blue-400">Datos en MySQL</h2>
              <h4 class="mb-1">Tabla Pokemon</h4>
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
                    <td class="px-4 py-2">{{ data.numeroPokedex }}</td>
                    <td class="px-4 py-2">{{ data.nombre }}</td>
                    <td class="px-4 py-2">{{ data.peso }}</td>
                    <td class="px-4 py-2">{{ data.altura }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div v-if="responseData.length" class="text-blue-100 p-4">
              <h4 class="mb-1">Tabla Estadisticas</h4>
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
                    <td class="px-4 py-2">{{ data.numeroPokedex }}</td>
                    <td class="px-4 py-2">{{ data.ataque }}</td>
                    <td class="px-4 py-2">{{ data.defensa }}</td>
                    <td class="px-4 py-2">{{ data.velocidad }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

          </div>
        </div>
        <div v-if="responseData.length" class="bg-black text-white p-4 rounded border border-gray-100 mb-4">
          <h2 class="text-lg font-bold mb-4 text-purple-400">Log Rust</h2>
          <p class="text-gray-400">
            {{ reponseDjango }}
          </p>
        </div>

      </div>
    </div>

  </div>
</template>

<style scoped>
.container-form-pokemon{
  display: grid;
  grid-template-columns: minmax(300px, .8fr) minmax(200px, 1fr); /* Form más ancho que el Postman */
  gap: 1rem; /* Espaciado entre columnas */
  /* height: 100vh;  */
}
</style>
