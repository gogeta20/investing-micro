<script setup lang="ts">
import TitleView from "@/components/TitleView.vue";
import { CreatePaymentPaypal } from "@/modules/home/application/useCase/Payment/CreatePaymentPaypal";
import { GetApprovePaymentPaypal } from '@/modules/home/application/useCase/Payment/GetApprovePaymentPaypal';
import RepositorySymfony from "@/modules/home/infrastructure/repositories/RepositorySymfony";
import RepositorySymfonyGet from '@/modules/home/infrastructure/repositories/RepositorySymfonyGet';
import { v4 as uuidv4 } from 'uuid';


const startPaymentTest = async (formData: any) => {
  try {
    const paymentId = uuidv4();
    const data = {
      "id": paymentId,
      "amount": 10,
      "currency": "EUR",
      "customer_email": "mauricio@gmail.com",
      "description": "algo",
    }
    const useCase = new CreatePaymentPaypal(new RepositorySymfony());
    await useCase.execute(data);

    await redirectToPayPal(paymentId);
  } catch (error) {
    console.error("Error al crear item:", error);
  }
};

const redirectToPayPal = async (paymentId: string) => {
  try {
    const useCase = new GetApprovePaymentPaypal(new RepositorySymfonyGet());
    const response = await useCase.execute(paymentId);
    console.log(response)
    if (response?.data?.approve_link) {
      console.log("Redirigiendo a PayPal:", response.data.approve_link);
      window.location.href = response.data.approve_link; // Redirige a PayPal
    } else {
      console.error("No se recibió una URL de aprobación válida");
    }
  } catch (error) {
    console.error("Error al obtener el estado del pago:", error);
  }
};

</script>

<template>
  <div class="bg-green page-container">
    <TitleView title="Test de Pagos en Línea" />

    <div class="content text-gray-300 text-xs sm:text-base pt-8 w-full px-4">
      <p class="p-4">
        En esta prueba vamos a realizar un pago utilizando PayPal Sandbox.
        Primero, generaremos una orden en el backend y luego redirigiremos al usuario
        a PayPal para completar la transacción. Mandaremos con el monto de 10 euros.
      </p>

      <div class="p-4 text-center">
        <button class="btn-primary" @click="startPaymentTest">
          🛒 Iniciar Pago con PayPal de 10 Euros
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.btn-primary {
  background-color: #0070ba;
  color: white;
  padding: 10px 20px;
  font-size: 16px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background 0.3s;
}

.btn-primary:hover {
  background-color: #005c9e;
}
</style>
