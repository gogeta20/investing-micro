import yfinance as yf
from myproject.stock.application.queries.get_stock_history_period.GetStockHistoryPeriodQuery import GetStockHistoryPeriodQuery
from myproject.core.infrastructure.repository.mysql.mysql_service import MySQLService


class GetStockHistoryPeriod:
    def __init__(self):
         self.mysql_service =  MySQLService()

    def execute(self, query: GetStockHistoryPeriodQuery):
        try:
            ticker = yf.Ticker(query.symbol)
            history = ticker.history(period=query.period)
            history_reset = history.reset_index()

            result = []
            for _, row in history_reset.iterrows():
                symbol = query.symbol
                price = float(row["Close"]) if "Close" in row else None
                open_price = float(row["Open"]) if "Open" in row else None  # Renombrado: 'open' es palabra reservada
                high = float(row["High"]) if "High" in row else None
                low = float(row["Low"]) if "Low" in row else None
                close = float(row["Close"]) if "Close" in row else None
                volume = int(row["Volume"]) if "Volume" in row else None
                recorded_at = row["Date"].strftime("%Y-%m-%d") if hasattr(row["Date"], "strftime") else str(row["Date"])

                result.append({
                    "date": recorded_at,
                    "open": open_price,
                    "high": high,
                    "low": low,
                    "close": close,
                    "volume": volume,
                    "price": price,
                })

                insert_sql = """
                    INSERT INTO stock_prices (uid, stock_id, price, open, high, low, close, volume, recorded_at)
                    VALUES (UUID(), (SELECT id FROM stocks WHERE symbol = %s), %s, %s, %s, %s, %s, %s, %s)
                """
                # Orden: symbol, price, open, high, low, close, volume, recorded_at
                self.mysql_service.execute_query_params(insert_sql, (symbol, price, open_price, high, low, close, volume, recorded_at))

            return {
                "symbol": query.symbol,
                "period": query.period,
                "data": result
            }
        except Exception as e:
            return {
                "symbol": query.symbol,
                "period": query.period,
                "error": str(e)
            }
