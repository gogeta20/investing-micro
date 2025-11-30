import HttpClientDjango from "@/core/http/HttpClientDjango";
import { UtilHelper } from "@/core/utilities/UtilHelper";

export interface StockOverview {
  portfolio_id: number;
  symbol: string;
  name: string;
  current: {
    price: number;
    recorded_at: string;
  };
  last_snapshot: {
    price: number | string;
    recorded_at: string;
  } | null;
}

export interface StocksOverviewResponse {
  data: StockOverview[];
}

export interface GetStocksOverviewParams {
  portfolioId: number;
}

async function InMemory(params: GetStocksOverviewParams): Promise<StocksOverviewResponse> {
  await UtilHelper.wait(500);
  // Mock response
  return {
    data: [],
  };
}

async function Api(params: GetStocksOverviewParams): Promise<StocksOverviewResponse> {
  const response = await HttpClientDjango.get<StocksOverviewResponse>(
    `/api/stock/overview/list`,
    { portfolio_id: params.portfolioId }
  );
  return response.data;
}

async function GetStocksOverviewUseCase(
  params: GetStocksOverviewParams
): Promise<StocksOverviewResponse> {
  return UtilHelper.checkEnvironment() ? await InMemory(params) : await Api(params);
}

export { GetStocksOverviewUseCase };
