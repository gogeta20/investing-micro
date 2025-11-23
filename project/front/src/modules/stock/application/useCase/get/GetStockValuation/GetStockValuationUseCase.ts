import HttpClientDjango from "@/core/http/HttpClientDjango";
import { UtilHelper } from "@/core/utilities/UtilHelper";

export interface ValuationInputs {
  fcf: number;
  growth: number;
  discount_rate: number;
  terminal_rate: number;
}

export interface Valuation {
  method: string;
  intrinsic_value: number;
  price_at_valuation: number;
  discount_percent: number;
  upside_percent: number;
  value_gap: number;
  status: string;
  grade: string;
  inputs: ValuationInputs;
  created_at: string;
}

export interface ValuationData {
  symbol: string;
  name: string;
  sector: string;
  currency: string;
  valuation: Valuation;
}

export interface ValuationResponse {
  data: ValuationData;
}

async function InMemory(symbol: string): Promise<ValuationResponse> {
  await UtilHelper.wait(500);
  // Mock response
  return {
    data: {
      symbol,
      name: "Mock Company",
      sector: "Technology",
      currency: "USD",
      valuation: {
        method: "DCF",
        intrinsic_value: 100,
        price_at_valuation: 80,
        discount_percent: 20,
        upside_percent: 25,
        value_gap: 20,
        status: "undervalued",
        grade: "A",
        inputs: {
          fcf: 1000000,
          growth: 0.1,
          discount_rate: 0.12,
          terminal_rate: 0.03,
        },
        created_at: new Date().toISOString(),
      },
    },
  };
}

async function Api(symbol: string): Promise<ValuationResponse> {
  const response = await HttpClientDjango.get<ValuationResponse>(
    `/api/stock/${symbol}/valuation`
  );
  return response.data;
}

async function GetStockValuationUseCase(symbol: string): Promise<ValuationResponse> {
  return UtilHelper.checkEnvironment() ? await InMemory(symbol) : await Api(symbol);
}

export { GetStockValuationUseCase };
