import HttpClientDjango from "@/core/http/HttpClientDjango";
import { UtilHelper } from "@/core/utilities/UtilHelper";

export interface StockHistoryItem {
  date: string;
  open: number;
  high: number;
  low: number;
  close: number;
  volume: number;
  price: number;
}

export interface StockHistoryResponse {
  symbol: string;
  period: string;
  data: StockHistoryItem[];
}

export interface GetStockHistoryParams {
  symbol: string;
  period?: string;
}

async function InMemory(params: GetStockHistoryParams): Promise<StockHistoryResponse> {
  await UtilHelper.wait(500);
  // Mock response
  return {
    symbol: params.symbol,
    period: params.period || "1mo",
    data: [],
  };
}

async function Api(params: GetStockHistoryParams): Promise<StockHistoryResponse> {
  const response = await HttpClientDjango.get<StockHistoryResponse>(
    `/api/stock/${params.symbol}/history`,
    { period: params.period || "1mo" }
  );
  return response.data;
}

async function GetStockHistoryUseCase(
  params: GetStockHistoryParams
): Promise<StockHistoryResponse> {
  return UtilHelper.checkEnvironment() ? await InMemory(params) : await Api(params);
}

export { GetStockHistoryUseCase };
