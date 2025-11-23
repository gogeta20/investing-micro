import HttpClientDjango from "@/core/http/HttpClientDjango";
import { UtilHelper } from "@/core/utilities/UtilHelper";

export type Trend = "up" | "down";

export interface AnalysisRow {
  stock_id?: number;
  symbol: string;
  name: string;
  price_today: string;
  price_yesterday: string;
  change_percent: string;
  trend: Trend;
  today_date: string;
  yesterday_date: string;
}

export interface AnalysisDailyResponse {
  data: AnalysisRow[];
}

async function InMemory(): Promise<AnalysisDailyResponse> {
  await UtilHelper.wait(500);
  // Mock response
  return {
    data: [],
  };
}

async function Api(): Promise<AnalysisDailyResponse> {
  const response = await HttpClientDjango.get<AnalysisDailyResponse>(
    "/api/stock/analysis/daily"
  );
  return response.data;
}

async function GetAnalysisDailyUseCase(): Promise<AnalysisDailyResponse> {
  return UtilHelper.checkEnvironment() ? await InMemory() : await Api();
}

export { GetAnalysisDailyUseCase };
