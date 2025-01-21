import HttpClient from '@/core/http/HttpClient';
import { UtilHelper } from "@/core/utilities/UtilHelper";
import Mock from '@/modules/home/infrastructure/mock/basicResponse.json';
import type { IRepository } from '@/modules/home/domain/repositories/IRepository';
import type { ResponseBasic, ResponseData } from '@/modules/home/domain/models/ResponseData';
import BaseRepository from '@/modules/home/infrastructure/repositories/BaseRepository';

class RepositorySymfony extends BaseRepository implements IRepository {

  async InMemory(): Promise<ResponseData> {
    await UtilHelper.wait(500);
    return Mock as ResponseData;
  }

  async Api(params) : Promise<ResponseData> {
    try {
      const responseBasic = await HttpClient.post<ResponseBasic>(this.path, params);
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

export default RepositorySymfony;
