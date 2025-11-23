import HttpClientDjango from "@/core/http/HttpClientDjango";
import { UtilHelper } from "@/core/utilities/UtilHelper";

export interface PortfolioStock {
  id: number;
  symbol: string;
}

export interface Portfolio {
  id: number;
  name?: string;
  stocks_count?: number;
  created_at?: string;
  stocks?: PortfolioStock[];
}

export interface PortfoliosListResponse {
  data: Portfolio[];
}

async function InMemory(): Promise<PortfoliosListResponse> {
  await UtilHelper.wait(500);
  // Mock response
  return {
    data: [
      {
        id: 1,
        name: "Portafolio Principal",
        stocks_count: 5,
        created_at: new Date().toISOString(),
        stocks: [
          { id: 1, symbol: "AAPL" },
          { id: 2, symbol: "MSFT" },
          { id: 3, symbol: "GOOGL" },
          { id: 4, symbol: "AMZN" },
          { id: 5, symbol: "TSLA" },
        ],
      },
    ],
  };
}

async function Api(): Promise<PortfoliosListResponse> {
  const response = await HttpClientDjango.get<PortfoliosListResponse>(
    "/api/portfolio/list"
  );
  return response.data;
}

async function GetPortfoliosListUseCase(): Promise<PortfoliosListResponse> {
  return UtilHelper.checkEnvironment() ? await InMemory() : await Api();
}

export { GetPortfoliosListUseCase };
