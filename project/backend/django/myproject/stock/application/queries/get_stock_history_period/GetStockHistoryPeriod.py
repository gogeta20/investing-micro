import yfinance as yf
from myproject.stock.application.queries.get_stock_history_period.GetStockHistoryPeriodQuery import GetStockHistoryPeriodQuery


class GetStockHistoryPeriod:
    def __init__(self):
        pass

    def execute(self, query: GetStockHistoryPeriodQuery):
        try:
            ticker = yf.Ticker(query.symbol)
            history = ticker.history(period=query.period)

            # Convertir el DataFrame a formato JSON
            # Resetear el índice para incluir la fecha como columna
            history_reset = history.reset_index()

            # Convertir a diccionario y luego a JSON serializable
            result = []
            for _, row in history_reset.iterrows():
                result.append({
                    "date": row["Date"].strftime("%Y-%m-%d") if hasattr(row["Date"], "strftime") else str(row["Date"]),
                    "open": float(row["Open"]) if "Open" in row else None,
                    "high": float(row["High"]) if "High" in row else None,
                    "low": float(row["Low"]) if "Low" in row else None,
                    "close": float(row["Close"]) if "Close" in row else None,
                    "volume": int(row["Volume"]) if "Volume" in row else None,
                })

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
