interface StockCurrent {
  symbol: string;
  name: string;
  price?: number;
  recorded_at?: string;
  error?: string;
}

interface StockCurrentResponse <T = []>{
  data: StockCurrent[];
  portfolio_id: number | null;
  updated_at: string;
}
