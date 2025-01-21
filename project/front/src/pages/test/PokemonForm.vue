<script setup lang="ts">
import { pokemon } from "@/modules/home/domain/models/Pokemon";
import { ref } from "vue";
const emit = defineEmits(["submit"]);

function handleSubmit() {
  emit("submit", { ...pokemonForm.value });
}

// Datos del formulario
const pokemonForm = ref<pokemon>({
  numeroPokedex: 0,
  nombre: "",
  ataque: 0,
  defensa: 0,
  velocidad: 0,
  hp: 0,
  especial: 0,
});
</script>

<template>
  <!-- bg-gray-800 text-blue-100 border border-gray-100 -->
  <form @submit.prevent="handleSubmit" class="space-y-4 bg-gray-800 border border-gray-100 p-6 rounded shadow-md">
    <h2 class="text-xl font-bold mb-4">Crear Item</h2>

    <div>
      <label for="numeroPokedex" class="block text-sm font-medium">Número Pokedex</label>
      <input type="number" id="numeroPokedex" v-model="pokemonForm.numeroPokedex"
        class="mt-1 block w-full border rounded p-2" required />
    </div>

    <div>
      <label for="nombre" class="block text-sm font-medium">Nombre</label>
      <input type="text" id="nombre" v-model="pokemonForm.nombre" class="mt-1 block w-full border rounded p-2"
        required />
    </div>

    <div>
      <label for="ataque" class="block text-sm font-medium">Ataque</label>
      <input type="number" id="ataque" v-model="pokemonForm.ataque" class="mt-1 block w-full border rounded p-2"
        required />
    </div>

    <div>
      <label for="defensa" class="block text-sm font-medium">Defensa</label>
      <input type="number" id="defensa" v-model="pokemonForm.defensa" class="mt-1 block w-full border rounded p-2"
        required />
    </div>

    <div>
      <label for="velocidad" class="block text-sm font-medium">Velocidad</label>
      <input type="number" id="velocidad" v-model="pokemonForm.velocidad" class="mt-1 block w-full border rounded p-2"
        required />
    </div>

    <div>
      <label for="hp" class="block text-sm font-medium">HP</label>
      <input type="number" id="hp" v-model="pokemonForm.hp" class="mt-1 block w-full border rounded p-2" required />
    </div>

    <div>
      <label for="especial" class="block text-sm font-medium">Especial</label>
      <input type="number" id="especial" v-model="pokemonForm.especial" class="mt-1 block w-full border rounded p-2"
        required />
    </div>

    <div>
      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Crear Pokémon
      </button>
    </div>
  </form>
</template>

<style scoped>
/* Agrega estilos personalizados si los necesitas */
</style>
