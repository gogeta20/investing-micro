import yfinance as yf
from datetime import datetime

from myproject.core.application.UseCase.StockCurrent import GetCurrentStocksQuery

class GetCurrentStocks:
    def __init__(self, mysql_service):
        self.mysql_service = mysql_service

    def execute(self, query: GetCurrentStocksQuery):
        # 1. Recuperar los símbolos desde DB
        if query.portfolio_id:
            sql = """
                SELECT s.symbol
                FROM portfolio_stocks ps
                JOIN stocks s ON ps.stock_id = s.id
                WHERE ps.portfolio_id = %s
            """
            symbols = [row["symbol"] for row in self.mysql_service.execute_query_params(sql, (query.portfolio_id,))]
        else:
            sql = "SELECT symbol FROM stocks"
            symbols = [row["symbol"] for row in self.mysql_service.execute_query(sql)]

        if not symbols:
            return {"error": "No se encontraron acciones"}

        results = []
        now = datetime.utcnow().strftime("%Y-%m-%d %H:%M:%S")

        # 2. Consultar precios en yfinance
        for symbol in symbols:
            try:
                ticker = yf.Ticker(symbol)
                price = ticker.history(period="1d")["Close"].iloc[-1]
                results.append({"symbol": symbol, "price": float(price), "recorded_at": now})

                # 3. Guardar snapshot en DB
                insert_sql = """
                    INSERT INTO stock_prices (uid, stock_id, price, recorded_at)
                    VALUES (UUID(),
                            (SELECT id FROM stocks WHERE symbol = %s),
                            %s, %s)
                """
                self.mysql_service.execute_query_params(insert_sql, (symbol, price, now))
            except Exception as e:
                results.append({"symbol": symbol, "error": str(e)})

        return {
            "portfolio_id": query.portfolio_id,
            "updated_at": now,
            "data": results
        }
