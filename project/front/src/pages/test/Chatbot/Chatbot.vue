<script setup lang="ts">
import { GetIntent } from '@/modules/home/application/useCase/DetectIntent/GetIntent';
import { PostConversation } from '@/modules/home/application/useCase/DetectIntent/PostConversation';
import { iconversationState } from '@/modules/home/domain/models/IConversationState';
import RepositoryDjangoGet from '@/modules/home/infrastructure/repositories/RepositoryDjangoGet';
import RepositoryRustPost from '@/modules/home/infrastructure/repositories/RepositoryRustPost';
import { v4 as uuidv4 } from 'uuid';
import { nextTick, ref } from 'vue';

const messages = ref([{ text: '¡Hola! ¿En qué puedo ayudarte?', from: 'bot' }]);
const newMessage = ref('hola queria reservar una mesa');
const chatBody = ref(null);

const conversationState = ref<iconversationState>({
  uuid: uuidv4(),
  intent: null,
  personas: null,
  hora: null,
  mesa: null,
  respuesta_usuario: ""
});

// 👉 Función para mantener scroll siempre abajo
function scrollToBottom() {
  nextTick(() => {
    chatBody.value.scrollTop = chatBody.value.scrollHeight;
  });
}

// 👉 Función para resetear laonversación
function resetConversation() {
  conversationState.value = {
    uuid: null,
    intent: null,
    personas: null,
    hora: null,
    mesa: null,
    respuesta_usuario: null
  };
}

// 👉 Función para actualizar el estado de la conversación
async function updateConversationState(userMessage: string): Promise<string | null> {
  if (!conversationState.value.intent) {
    const intentResponse = await new GetIntent(new RepositoryDjangoGet()).execute(userMessage);
    conversationState.value.intent = intentResponse.data.intent;
    return intentResponse.data.intent;
  }

  if (!conversationState.value.personas) {
    conversationState.value.personas = userMessage;
    conversationState.value.intent = 'numero_comensales';
    conversationState.value.respuesta_usuario = userMessage;
    return userMessage;
  }

  if (!conversationState.value.hora) {
    conversationState.value.hora = userMessage;
    conversationState.value.intent = 'hora_reserva';
    conversationState.value.respuesta_usuario = userMessage;
    return userMessage;
  }

  return null; // Conversación completa o sin cambios
}

// 👉 Función principal para enviar mensajes
async function sendMessage() {
  if (!newMessage.value.trim()) return;

  const userMessage = newMessage.value.trim();
  messages.value.push({ text: userMessage, from: 'user' });
  const currentIntent = await updateConversationState(userMessage);

  newMessage.value = '';
  scrollToBottom();

  try {
    if (currentIntent) {
      const responseIntentUseCase = new PostConversation(new RepositoryRustPost());
      const rustResponse = await responseIntentUseCase.execute(conversationState.value);
      const botResponse = rustResponse.data.at(0) || "No tengo respuesta disponible 😅";

      messages.value.push({ text: botResponse, from: 'bot' });
      scrollToBottom();

      const isFinished = botResponse.includes("reserva realizada") || botResponse.includes("te esperamos");
      if (isFinished) resetConversation();
    }
  } catch (error) {
    console.error('Error en la conversación:', error);
    messages.value.push({ text: 'Ups, algo falló 😢', from: 'bot' });
    scrollToBottom();
  }
}
</script>

<template>
  <div class="page-container">
    <div class="chat-wrapper">
      <div class="chat-header">ChatBot</div>

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
