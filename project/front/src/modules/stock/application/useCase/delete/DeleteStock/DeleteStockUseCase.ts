import HttpClientDjango from "@/core/http/HttpClientDjango";
import { UtilHelper } from "@/core/utilities/UtilHelper";

// Request: acepta id, symbol o uid (al menos uno requerido)
export interface DeleteStockRequest {
  id?: number;
  symbol?: string;
  uid?: string;
}

// Response: información del stock eliminado
export interface DeleteStockResponse {
  message: string;
  deleted_stock?: {
    id?: number;
    symbol?: string;
    uid?: string;
    name?: string;
  };
}

async function InMemory(payload: DeleteStockRequest): Promise<DeleteStockResponse> {
  await UtilHelper.wait(1000); // Simular delay de red

  return {
    message: "Acción eliminada exitosamente",
    deleted_stock: {
      id: payload.id,
      symbol: payload.symbol,
      uid: payload.uid,
      name: payload.symbol ? `${payload.symbol} Stock` : undefined,
    },
  };
}

async function Api(payload: DeleteStockRequest): Promise<DeleteStockResponse> {
  const response = await HttpClientDjango.delete<DeleteStockResponse>(
    "/api/stock/delete",
    payload
  );
  return response.data;
}

async function DeleteStockUseCase(
  payload: DeleteStockRequest
): Promise<DeleteStockResponse> {
  return UtilHelper.checkEnvironment()
    ? await InMemory(payload)
    : await Api(payload);
}

export { DeleteStockUseCase };
