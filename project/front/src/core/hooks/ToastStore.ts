import { defineStore } from 'pinia';
import { ref } from 'vue';

export type ToastType = 'success' | 'error' | 'warning' | 'info';

export interface Toast {
  id: string;
  message: string;
  type: ToastType;
  duration?: number; // Milisegundos (default: 3000)
  isVisible: boolean;
}

export const useMeToast = defineStore('useMeToast', () => {
  const toasts = ref<Toast[]>([]);

  const addToast = ({ message, type = 'info', duration = 3000 }: { message: string; type?: ToastType; duration?: number }) => {
    const id = `${Date.now()}-${Math.random()}`;
    toasts.value.push({ id, message, type, duration, isVisible: true });
    setTimeout(() => {
      hideToast(id);
    }, duration);
  };

  const hideToast = (id: string) => {
    const toast = toasts.value.find(t => t.id === id);
    if (toast) {
      toast.isVisible = false;
      setTimeout(() => {
        removeToast(id);
      }, 500); // Tiempo suficiente para completar la animación
    }
  };

  const removeToast = (id: string) => {
    toasts.value = toasts.value.filter(toast => toast.id !== id);
  };

  return {
    toasts,
    addToast,
    hideToast,
    removeToast,
  };
});
