export interface StockCurrent {
  symbol: string;
  name: string;
  price?: number;
  recorded_at?: string;
  error?: string;
}

export interface StockCurrentResponse<T = StockCurrent[]> {
  data: T;
  portfolio_id: number | null;
  updated_at: string;
}
