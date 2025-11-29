import HttpClientDjango from "@/core/http/HttpClientDjango";
import { UtilHelper } from "@/core/utilities/UtilHelper";

export interface SearchStockBySymbolResponse {
  symbol: string;
  name: string;
  sector?: string;
  currency?: string;
}

export interface SearchStockBySymbolParams {
  symbol: string;
}

async function InMemory(params: SearchStockBySymbolParams): Promise<SearchStockBySymbolResponse> {
  await UtilHelper.wait(500);

  // Mock response basado en la estructura del backend
  return {
    symbol: params.symbol.toUpperCase(),
    name: "Mock Company Inc.",
    sector: "Technology",
    currency: "USD",
  };
}

async function Api(params: SearchStockBySymbolParams): Promise<SearchStockBySymbolResponse> {
  const response = await HttpClientDjango.get<SearchStockBySymbolResponse>(
    `/api/stock/search/${params.symbol}`
  );
  return response.data;
}

async function SearchStockBySymbolUseCase(
  params: SearchStockBySymbolParams
): Promise<SearchStockBySymbolResponse> {
  return UtilHelper.checkEnvironment() ? await InMemory(params) : await Api(params);
}

export { SearchStockBySymbolUseCase };
