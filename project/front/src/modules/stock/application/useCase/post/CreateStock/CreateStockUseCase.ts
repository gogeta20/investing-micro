import HttpClientDjango from "@/core/http/HttpClientDjango";
import { UtilHelper } from "@/core/utilities/UtilHelper";

export interface CreateStockRequest {
  symbol: string;
  name: string;
  sector?: string;
  currency?: string;
}

export interface CreateStockResponse {
  uid: string;
  symbol: string;
  name: string;
  sector?: string;
  currency: string;
  created_at: string;
}

async function InMemory(payload: CreateStockRequest): Promise<CreateStockResponse> {
  await UtilHelper.wait(1000);

  // Mock response basado en la estructura del backend
  return {
    uid: crypto.randomUUID(),
    symbol: payload.symbol.toUpperCase(),
    name: payload.name,
    sector: payload.sector || undefined,
    currency: payload.currency || "USD",
    created_at: new Date().toISOString(),
  };
}

async function Api(payload: CreateStockRequest): Promise<CreateStockResponse> {
  const response = await HttpClientDjango.post<CreateStockResponse>(
    "/api/stock/create",
    payload
  );
  return response.data;
}

async function CreateStockUseCase(
  payload: CreateStockRequest
): Promise<CreateStockResponse> {
  return UtilHelper.checkEnvironment()
    ? await InMemory(payload)
    : await Api(payload);
}

export { CreateStockUseCase };
