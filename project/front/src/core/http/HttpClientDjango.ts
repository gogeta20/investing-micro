import axios, { type AxiosInstance, type AxiosRequestConfig, type AxiosResponse } from 'axios';
const baseURL = import.meta.env.VITE_BASE_URL_DJANGO;

class HttpClientDjango {
  private instanceDjango: AxiosInstance;
  // private instanceBoot: AxiosInstance;

  constructor() {
    this.instanceDjango = axios.create({
      baseURL,
      timeout: 10000,
    });

    this.instanceDjango.interceptors.request.use(
      (config: any) => {
        return config;
      },
      (error) => {
        return Promise.reject(error);
      }
    );

    this.instanceDjango.interceptors.response.use(
      (response: AxiosResponse) => {
        return response;
      },
      (error) => {
        return Promise.reject(error);
      }
    );
  }

  public get<T>(url: string, params?: any): Promise<AxiosResponse<T>> {
    return this.instanceDjango.get(url, { params });
  }

  public post<T>(url: string, data?: any): Promise<AxiosResponse<T>> {
    return this.instanceDjango.post(url, data);
  }

  public put<T>(url: string, data?: any): Promise<AxiosResponse<T>> {
    return this.instanceDjango.put(url, data);
  }

  public delete<T>(url: string): Promise<AxiosResponse<T>> {
    return this.instanceDjango.delete(url);
  }
}

export default new HttpClientDjango();
