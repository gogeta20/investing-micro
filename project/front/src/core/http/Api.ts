import { rootConfig } from "@/core/config/config";
import { StatusCode } from "@/core/http/statusCode";
import { useToastStore } from "@/core/stores/toast";
import type { AxiosRequestConfig, AxiosResponse } from "axios";
import axios from "axios";
// import { userStore } from "@/core/stores/userStore";

const headers = {
  Accept: "application/json",
  "Content-Type": "application/json; charset=utf-8",
  "X-Requested-With": "XMLHttpRequest",
};

const CSRF = "X-CSRF-TOKEN";

const injectToken = (config: AxiosRequestConfig): AxiosRequestConfig | any => {
  // const usuarioStore = userStore();
  // const TOKEN = usuarioStore.data.token;
  const TOKEN = "";

  if (!config.headers) {
    config.headers = {};
  }

  config.headers["Accept-Language"] = 'es';

  if (TOKEN) {
    config.headers.Authorization = `Bearer ${TOKEN}`;
    if (config.method === "post") {
      config.headers[CSRF] = `${TOKEN}`;
    }
  }

  return config;
};

export const axiosConfig: AxiosRequestConfig = {
  baseURL: rootConfig.apiURL,
  headers,
};

class Api {
  private readonly apiClient = axios.create(axiosConfig);

  constructor() {
    this.apiClient.interceptors.request.use(
      injectToken,
      async (error) => Promise.reject(error)
    );

    this.apiClient.interceptors.response.use(
      async (response) => {
        Api.handleStates(response);
        return response;
      },
      async (error) => {
        const { response } = error;
        return Api.handleError(response);
      }
    );
  }

  async request<T, R = AxiosResponse<T>>(config: AxiosRequestConfig): Promise<R> {
    return this.apiClient.request(config);
  }

  async get<T, R = AxiosResponse<T>>(url: string, config?: AxiosRequestConfig): Promise<R> {
    return this.apiClient.get<T, R>(url, config);
  }

  async post<T, R = AxiosResponse<T>>(url: string, data?: T, config?: AxiosRequestConfig): Promise<R> {
    return this.apiClient.post<T, R>(url, data, config);
  }

  async put<T, R = AxiosResponse<T>>(url: string, data?: T, config?: AxiosRequestConfig): Promise<R> {
    return this.apiClient.put<T, R>(url, data, config);
  }

  async delete<T, R = AxiosResponse<T>>(url: string, config?: AxiosRequestConfig): Promise<R> {
    return this.apiClient.delete<T, R>(url, config);
  }

  private static handleStates(response: AxiosResponse) {
    const { message } = response.data || {};
    const { status } = response;
    const toastStore = useToastStore();

    switch (status) {
      case StatusCode.Accept:
        toastStore.onShowToast({
          title: "",
          message: message,
          type: "info",
          code: status,
        });
        break;
      case StatusCode.Ok:
        // Agregar lógica si es necesario
        break;
      case StatusCode.NoContent:
        // No hacer nada
        break;
      default:
        toastStore.onShowToast({
          title: "",
          message: message,
          type: "success",
          code: status,
        });
        break;
    }
  }

  private static handleError(error: any): Promise<never> {
    // const usuarioStore = userStore();
    const { status, data } = error || {};
    const toastStore = useToastStore();
    let messageErrorData = "Error Interno";

    if (data?.status !== 500) {
      messageErrorData = data.message;
    }

    switch (status) {
      case StatusCode.NotFound:
        toastStore.onShowToast({
          title: "Recurso no encontrado",
          message: data.message,
          type: "error",
          code: status,
        });
        break;
      case StatusCode.TooManyRequests:
        toastStore.onShowToast({
          title: "Error",
          message: "Demasiados intentos",
          type: "error",
          code: status,
        });
        break;
      case StatusCode.Unauthorized:
        // usuarioStore.data.isAuthenticated = false;
        toastStore.onShowToast({
          title: "Error",
          message: "Sesión Caducada",
          type: "error",
          code: status,
        });
        break;
      case StatusCode.InternalServerError:
        toastStore.onShowToast({
          title: messageErrorData,
          message: "",
          type: "error",
          code: status,
        });
        break;
      default:
        toastStore.onShowToast({
          title: "",
          message: data.message || messageErrorData,
          type: "error",
          code: status,
        });
        break;
    }
    return Promise.reject(error);
  }
}

export const api = new Api();
