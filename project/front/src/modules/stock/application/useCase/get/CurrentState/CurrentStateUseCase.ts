import HttpClientDjango from "@/core/http/HttpClientDjango";
import { UtilHelper } from "@/core/utilities/UtilHelper";
import Mock from "@/modules/stock/application/useCase/get/CurrentState/mock.json";

async function InMemory(): Promise<any> {
  await UtilHelper.wait(500);
  return Mock.data;
}

async function Api(): Promise<StockCurrentResponse> {
  const response = await HttpClientDjango.get<StockCurrentResponse<StockCurrent[]>>(
    "/api/stock/current/state"
  );
  return response.data;
}

async function CurrentStateUseCase(): Promise<StockCurrentResponse> {
  return UtilHelper.checkEnvironment() ? await InMemory() : await Api();
}

export { CurrentStateUseCase };
