import { api } from "@/core/http/Api";
// import HttpClientDjango from "@/core/http/HttpClientDjango";

import { UtilHelper } from "@/core/utilities/UtilHelper";
import Mock from "@/modules/stock/application/useCase/get/base/mock.json";

async function InMemory(): Promise<any> {
  await UtilHelper.wait(500);
  return Mock.data;
}

async function Api(): Promise<any> {
  const response = await api.get<any>(`axentesGrupos`);
  const { data } = response.data;
  return data;
}

async function baseUseCase(): Promise<any> {
  return UtilHelper.checkEnvironment() ? await InMemory() : await Api();
}

export { baseUseCase };
