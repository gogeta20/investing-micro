<script setup lang="ts">
import { GetIntent } from '@/modules/home/application/useCase/DetectIntent/GetIntent';
import { GetResponseIntent } from '@/modules/home/application/useCase/DetectIntent/GetResponseIntent';
import RepositoryDjangoGet from '@/modules/home/infrastructure/repositories/RepositoryDjangoGet';
import RepositoryRustGet from '@/modules/home/infrastructure/repositories/RepositoryRustGet';
import { v4 as uuidv4 } from 'uuid';
import { nextTick, ref } from 'vue';


const messages = ref([{ text: '¡Hola! ¿En qué puedo ayudarte?', from: 'bot' }]);
const newMessage = ref('hola queria reservar una mesa');
const chatBody = ref(null);

const conversationState = ref({
  uuid: uuidv4(), // 👈 Aquí generas el UUID único por conversación
  intent: null,         // Intención detectada (ej: 'reservar_mesa')
  personas: null,       // Número de personas
  hora: null,           // Hora de la reserva
  mesa: null            // Mesa asignada (opcional)
})


async function sendMessage() {
  if (!newMessage.value.trim()) return;

  const userMessage = newMessage.value.trim();
  messages.value.push({ text: userMessage, from: 'user' });
  newMessage.value = '';

  nextTick(() => {
    chatBody.value.scrollTop = chatBody.value.scrollHeight;
  });

  try {
    // 👇 Actualizamos flujo según el avance de la conversación
    let send_intent : string | null = null;
    if (!conversationState.value.intent) {
      const detectIntentUseCase = new GetIntent(new RepositoryDjangoGet());
      const intentResponse = await detectIntentUseCase.execute(userMessage);
      conversationState.value.intent = intentResponse.data.intent;
      send_intent = intentResponse.data.intent;

    } else if (!conversationState.value.personas) {
      conversationState.value.personas = parseInt(userMessage);
      send_intent = userMessage;
    } else if (!conversationState.value.hora) {
      // Paso 3️⃣ - Detectar la hora
      conversationState.value.hora = userMessage;
    }

    // Paso 4️⃣ - Enviar flujo actual a Rust siempre que tengamos intención
    if (send_intent) {
      const responseIntentUseCase = new GetResponseIntent(new RepositoryRustGet());
      const rustResponse = await responseIntentUseCase.execute(send_intent);

      console.log('🤖 Respuesta Rust:', rustResponse);

      messages.value.push({
        text: rustResponse.data.at(0) || "No tengo respuesta disponible 😅",
        from: 'bot'
      });

      // ✅ Si la conversación terminó, reseteamos
      if (rustResponse.data.at(0)) {
        if (rustResponse.data.at(0).includes("reserva realizada") || rustResponse.data.at(0).includes("te esperamos")) {
          resetConversation();
        }
      }
      send_intent = null
    }

    nextTick(() => {
      chatBody.value.scrollTop = chatBody.value.scrollHeight;
    });
  } catch (error) {
    console.error('Error en la conversación:', error);
    messages.value.push({ text: 'Ups, algo falló 😢', from: 'bot' });
  }
}

function resetConversation() {
  conversationState.value = {
    uuid: null,
    intent: null,
    personas: null,
    hora: null,
    mesa: null
  }
}

// async function sendMessage() {
//   if (!newMessage.value.trim()) return;

//   const userMessage = newMessage.value.trim();
//   messages.value.push({ text: userMessage, from: 'user' });
//   newMessage.value = '';

//   nextTick(() => {
//     chatBody.value.scrollTop = chatBody.value.scrollHeight;
//   });

//   try {
//     // 1️⃣ Paso: detectar intención con Django
//     const detectIntentUseCase = new GetIntent(new RepositoryDjangoGet());
//     const intentResponse = await detectIntentUseCase.execute(userMessage);
//     const detectedIntent = intentResponse.data.intent;

//     console.log('🎯 Intención detectada:', detectedIntent);

//     // 2️⃣ Paso: obtener respuesta con Rust
//     const responseIntentUseCase = new GetResponseIntent(new RepositoryRustGet());
//     const rustResponse = await responseIntentUseCase.execute(detectedIntent);

//     console.log('🤖 Respuesta Rust:', rustResponse);

//     // 3️⃣ Actualizar el chat con la respuesta real de Rust
//     messages.value.push({ text: rustResponse || "No tengo respuesta disponible 😅", from: 'bot' });
//     // messages.value.push({ text: rustResponse.data.response || "No tengo respuesta disponible 😅", from: 'bot' });

//     nextTick(() => {
//       chatBody.value.scrollTop = chatBody.value.scrollHeight;
//     });
//   } catch (error) {
//     console.error('Error en la conversación:', error);
//     messages.value.push({ text: 'Ups, algo falló 😢', from: 'bot' });
//   }
// }




// import { GetIntent } from '@/modules/home/application/useCase/DetectIntent/GetIntent';
// import { GetResponseIntent } from '@/modules/home/application/useCase/DetectIntent/GetResponseIntent';
// import RepositoryDjangoGet from '@/modules/home/infrastructure/repositories/RepositoryDjangoGet';
// import RepositoryRustGet from '@/modules/home/infrastructure/repositories/RepositoryRustGet';
// import { nextTick, ref } from 'vue';
// const messages = ref([
//   { text: '¡Hola! ¿En qué puedo ayudarte?', from: 'bot' }
// ])
// const newMessage = ref('')
// const chatBody = ref(null);
// console.log("Entorno Vite:", import.meta.env.VITE_MICRO_ENV)

// async function sendMessage() {
//   if (!newMessage.value.trim()) return

//   messages.value.push({ text: newMessage.value, from: 'user' })
//   newMessage.value = ''

//   // Scroll al fondo automáticamente
//   nextTick(() => {
//     chatBody.value.scrollTop = chatBody.value.scrollHeight
//   })

//   const phrase = 'hola, puedo reservar una mesa';
//   const useCase = new GetIntent(new RepositoryDjangoGet());
//   const response = await useCase.execute(phrase)
//   console.log(response);
//   const useCaseResponseIntent = new GetResponseIntent(new RepositoryRustGet());
//   const testPhrase = 'reservar_mesa';
//   const responseRI = await useCaseResponseIntent.execute(testPhrase)
//   console.log(responseRI);
//   // Simular respuesta del bot
//   setTimeout(() => {
//     messages.value.push({ text: 'Esto es una respuesta automática 😊', from: 'bot' })
//     nextTick(() => {
//       chatBody.value.scrollTop = chatBody.value.scrollHeight
//     })
//   }, 800)
// }
</script>

<template>
  <div class="page-container">
    <div class="chat-wrapper">
      <div class="chat-header">ChatBot</div>
      <div class="chat-header">{{ conversationState }}</div>
      <div class="chat-header">{{ messages }}</div>

      <div class="chat-body" ref="chatBody">
        <div v-for="(msg, index) in messages" :key="index" :class="['chat-message', msg.from]">
          {{ msg.text }}
        </div>
      </div>

      <div class="chat-input">
        <input v-model="newMessage" @keyup.enter="sendMessage" type="text" placeholder="Escribe un mensaje..." />
        <button @click="sendMessage">Enviar</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.chat-wrapper {
  max-width: 400px;
  height: 500px;
  margin: 40px auto;
  display: flex;
  flex-direction: column;
  border: 1px solid #ccc;
  border-radius: 12px;
  overflow: hidden;
  background-color: #f0f0f0;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.chat-header {
  background-color: #25d366;
  color: white;
  padding: 12px;
  font-weight: bold;
  text-align: center;
}

.chat-body {
  flex: 1;
  padding: 10px;
  overflow-y: auto;
  background-color: #e5ddd5;
}

.chat-message {
  padding: 10px;
  margin: 8px 0;
  max-width: 70%;
  border-radius: 8px;
  word-wrap: break-word;
}

.chat-message.bot {
  background-color: #ffffff;
  align-self: flex-start;
}

.chat-message.user {
  background-color: #dcf8c6;
  align-self: flex-end;
  margin-left: auto;
}

.chat-input {
  display: flex;
  border-top: 1px solid #ccc;
  background-color: #fff;
}

.chat-input input {
  flex: 1;
  border: none;
  padding: 10px;
  font-size: 1rem;
}

.chat-input button {
  border: none;
  background-color: #25d366;
  color: white;
  padding: 10px 16px;
  cursor: pointer;
}
</style>
