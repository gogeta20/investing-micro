import HttpClient from '@/core/http/HttpClient';
import { UtilHelper } from "@/core/utilities/UtilHelper";
import type { ResponseBasic, ResponseData } from '@/modules/home/domain/models/ResponseData';
import type { IRepository } from '@/modules/home/domain/repositories/IRepository';
import Mock from '@/modules/home/infrastructure/mock/basicResponse.json';
import BaseRepository from '@/modules/home/infrastructure/repositories/BaseRepository';

class RepositorySymfonyGet extends BaseRepository implements IRepository {

  async InMemory(): Promise<ResponseData> {
    await UtilHelper.wait(500);
    return Mock as ResponseData;
  }

  async Api(params) : Promise<ResponseData> {
    try {
      const responseBasic = await HttpClient.get<ResponseBasic>(this.path, params);
      const data = responseBasic.data as ResponseData;
      return data;
    } catch (error) {
      console.error('Error fetching home data:', error);
      throw error;
    }
  }

  async send(params): Promise<ResponseData>{
    return this.Api(params);
    // return UtilHelper.checkEnvironment() ? await this.InMemory() : await this.Api();
  }
}

export default RepositorySymfonyGet;
