import HttpClient from '@/core/http/HttpClient';
import { UtilHelper } from "@/core/utilities/UtilHelper";
import Mock from './mock.json';
import type { SymfonyRepository } from '@/modules/home/domain/repositories/SymfonyRepository';

// Implementación del repositorio para el dominio "Symfony" usando Axios
class HttpSymfonyRepository implements SymfonyRepository {

  async InMemory(): Promise<any> {
    await UtilHelper.wait(500);
    return Mock.data;
  }

  async Api() {
    try {
      const response = await HttpClient.get('/check');
      console.log(response)
      return response.data;
    } catch (error) {
      console.error('Error fetching home data:', error);
      throw error;
    }
  }

  async fetchData() {
    // return UtilHelper.checkEnvironment() ? await this.InMemory() : await this.Api();
    return  await this.Api();
  }
}

export default HttpSymfonyRepository;
