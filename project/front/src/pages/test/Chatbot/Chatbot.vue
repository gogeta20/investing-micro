<script setup lang="ts">
import { nextTick, ref } from 'vue';

const messages = ref([
  { text: '¡Hola! ¿En qué puedo ayudarte?', from: 'bot' }
])
const newMessage = ref('')
const chatBody = ref(null);
console.log("Entorno Vite:", import.meta.env.VITE_MICRO_ENV)
function sendMessage() {
  if (!newMessage.value.trim()) return

  messages.value.push({ text: newMessage.value, from: 'user' })
  newMessage.value = ''

  // Scroll al fondo automáticamente
  nextTick(() => {
    chatBody.value.scrollTop = chatBody.value.scrollHeight
  })

  // Simular respuesta del bot
  setTimeout(() => {
    messages.value.push({ text: 'Esto es una respuesta automática 😊', from: 'bot' })
    nextTick(() => {
      chatBody.value.scrollTop = chatBody.value.scrollHeight
    })
  }, 800)
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
