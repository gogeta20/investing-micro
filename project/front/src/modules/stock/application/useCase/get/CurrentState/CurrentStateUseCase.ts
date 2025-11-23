import HttpClientDjango from "@/core/http/HttpClientDjango";
import { UtilHelper } from "@/core/utilities/UtilHelper";
import Mock from "@/modules/stock/application/useCase/get/CurrentState/mock.json";

// Tipos del dominio
export interface StockCurrent {
  symbol: string;
  name: string;
  price?: number;
  recorded_at?: string;
  error?: string;
  id?: number; // Para compatibilidad con CreatePortfolioTable
}

export interface StockCurrentResponse {
  data: StockCurrent[];
  portfolio_id: number | null;
  updated_at: string;
}

async function InMemory(): Promise<StockCurrentResponse> {
  await UtilHelper.wait(500);
  // El mock tiene la estructura completa, solo necesitamos asegurar el tipo
  return Mock as StockCurrentResponse;
}

async function Api(): Promise<StockCurrentResponse> {
  const response = await HttpClientDjango.get<StockCurrentResponse>(
    "/api/stock/current/state"
  );
  return response.data;
}

async function CurrentStateUseCase(): Promise<StockCurrentResponse> {
  return UtilHelper.checkEnvironment() ? await InMemory() : await Api();
}

export { CurrentStateUseCase };
