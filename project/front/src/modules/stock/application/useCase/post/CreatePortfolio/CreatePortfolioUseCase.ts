import HttpClientDjango from "@/core/http/HttpClientDjango";
import { UtilHelper } from "@/core/utilities/UtilHelper";

export interface CreatePortfolioRequest {
  portfolio_id?: number;
  name?: string;
  stocks: Array<{
    id: number;
    symbol: string;
  }>;
}

export interface CreatePortfolioResponse {
  portfolio_id: number;
  message: string;
  stocks: Array<{
    id: number;
    symbol: string;
  }>;
}

async function InMemory(payload: CreatePortfolioRequest): Promise<CreatePortfolioResponse> {
  await UtilHelper.wait(1000);
  // Mock response
  return {
    portfolio_id: Math.floor(Math.random() * 1000) + 1,
    message: "Portafolio creado exitosamente",
    stocks: payload.stocks,
  };
}

async function Api(payload: CreatePortfolioRequest): Promise<CreatePortfolioResponse> {
  const response = await HttpClientDjango.post<CreatePortfolioResponse>(
    "/api/portfolio/create",
    payload
  );
  return response.data;
}

async function CreatePortfolioUseCase(
  payload: CreatePortfolioRequest
): Promise<CreatePortfolioResponse> {
  return UtilHelper.checkEnvironment()
    ? await InMemory(payload)
    : await Api(payload);
}

export { CreatePortfolioUseCase };
