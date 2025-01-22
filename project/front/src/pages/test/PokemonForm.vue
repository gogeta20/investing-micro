<script setup lang="ts">
import { pokemon } from "@/modules/home/domain/models/Pokemon";
import { ref } from "vue";
import { reactive } from "vue";
const emit = defineEmits(["submit"]);

function handleSubmit() {
  emit("submit", { ...pokemonForm.value });
}

const pokemonForm = ref<pokemon>({
  nombre: "",
  ataque: Math.floor(Math.random() * 101),
  defensa: Math.floor(Math.random() * 101),
  velocidad: Math.floor(Math.random() * 101),
  especial: Math.floor(Math.random() * 101),
  peso: Math.floor(Math.random() * 101),
  altura: Math.floor(Math.random() * 101),
  ps: Math.floor(Math.random() * 101),
});

const list = reactive([
  {
    text: "Nombre",
    id: "nombre",
    type: "text",
  },
  {
    text: "Ataque",
    id: "ataque",
    type: "number",
  },
  {
    text: "Defensa",
    id: "defensa",
    type: "number",
  },
  {
    text: "Velocidad",
    id: "velocidad",
    type: "number",
  },
  // {
  //   text: "Especial",
  //   id: "especial",
  //   type: "number",
  // },
  {
    text: "Peso",
    id: "peso",
    type: "number",
  },
  {
    text: "Altura",
    id: "altura",
    type: "number",
  },
  // {
  //   text: "PS",
  //   id: "ps",
  //   type: "number",
  // },
]);
</script>

<template>
  <form @submit.prevent="handleSubmit" class="space-y-4 bg-gray-800 text-gray-800 border border-gray-100 p-6 rounded shadow-md">

    <h2 class="text-xl text-white font-bold mb-4">Crear Item</h2>

    <div v-for="item in list" >
      <label :for="item.id" class="block text-sm text-white font-medium">{{item.text}}</label>
      <input
        :type="item.type"
        :id="item.id"
        v-model="pokemonForm[item.id]"
        class="mt-1 block w-full border rounded p-2"
        placeholder="Edita este campo"
        required />
    </div>

    <div>
      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Enviar Petición
      </button>
    </div>
  </form>
</template>

<style scoped>
/* Agrega estilos personalizados si los necesitas */
</style>
