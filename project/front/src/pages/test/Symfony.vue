<!-- @format -->

<script setup lang="ts">
import { ref } from "vue";
import TitleView from "@/components/TitleView.vue";
import { CreateItem } from "@/modules/home/application/useCase/CreateItem";
import RepositorySymfony from "@/modules/home/infrastructure/repositories/RepositorySymfony";
import PokemonForm from "@/pages/test/PokemonForm.vue";
import { pokemon } from "@/modules/home/domain/models/Pokemon";

const requestStatus = ref(""); // Estado de la petición (success, error, loading)
const responseData = ref([]); // Datos sincronizados

const handleFormSubmit = (formData: pokemon) => {
  try {
    requestStatus.value = "loading";
    const useCase = new CreateItem(new RepositorySymfony());
    useCase.execute(formData)
      .then(() => {
        responseData.value = [{ id: "ok", name: "mau", status: "success" }];
        requestStatus.value = "success";
      })
      .catch((error) => {
        requestStatus.value = "error";
        console.error("Error al crear item:", error);
      })
  } catch (error) {
    requestStatus.value = "error";
    console.error("Error al crear item:", error);
  }
};

</script>
<template>
  <div class="bg-green page-container">
    <TitleView title="SYMFONY CQRS" />

    <div class="content text-gray-300 text-xs sm:text-base pt-8 w-6/6">
      <p>
        Vamos a lanzar una peticion al backend symfony y testear la creacion de un item en
        la base de datos de escritura
      </p>
      <p>
        Esto lanzara un evento RabbitMQ, nuestro backend Rust estara pendiente para
        consumirlos y lo sincronizara con la base de datos de lectura MongoDB
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

            <div v-if="responseData.length"
              class="bg-gray-800 text-blue-100 border border-gray-100 p-4 rounded text-center">
              <h2 class="text-lg font-bold mb-4">Datos sincronizados</h2>
              <table class="w-full border-collapse border border-gray-200">
                <thead class="border border-gray-100">
                  <tr>
                    <th class="">ID</th>
                    <th class="">Nombre</th>
                    <th class="">Estado</th>
                  </tr>
                </thead>
                <tbody class="text-blue-300 border border-gray-100">
                  <tr v-for="data in responseData" :key="data.id">
                    <td class="px-4 py-2">{{ data.id }}</td>
                    <td class="px-4 py-2">{{ data.name }}</td>
                    <td class="px-4 py-2">{{ data.status }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

          </div>

        </div>

      </div>
    </div>

  </div>
</template>

<style scoped>
.container-form-pokemon{
  display: grid;
  grid-template-columns: minmax(300px, 2fr) minmax(200px, 1fr); /* Form más ancho que el Postman */
  gap: 1rem; /* Espaciado entre columnas */
  /* height: 100vh;  */
}
</style>
