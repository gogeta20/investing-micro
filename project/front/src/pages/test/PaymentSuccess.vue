<script setup lang="ts">
import { GetConfirmPaymentPaypal } from '@/modules/home/application/useCase/Payment/GetConfirmPaymentPaypal';
import RepositorySymfonyGet from '@/modules/home/infrastructure/repositories/RepositorySymfonyGet';
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();

onMounted(async () => {
  const token = route.query.token;
  // const tokenSend = token == null ? '' : token;
  // Aquí puedes hacer la llamada al backend con el token
  // para confirmar el pago si no se ha hecho todavía
  console.log('Confirmando pago con token:', token);
  const useCase = new GetConfirmPaymentPaypal(new RepositorySymfonyGet());
  const response = await useCase.execute(token);
  console.log(response)
});
</script>

<template>
  <div class="text-center p-8">
    <h1 class="text-2xl text-green-500 font-bold mb-4">✅ ¡Pago confirmado!</h1>
    <p>Gracias por tu compra. Te enviaremos un resumen a tu correo electrónico.</p>
  </div>
</template>
