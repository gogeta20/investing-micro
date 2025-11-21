import yfinance as yf
from datetime import datetime
from myproject.stock.application.queries.get_current_stocks.GetCurrentStocksQuery import GetCurrentStocksQuery
from myproject.core.infrastructure.services.market.market_status_service import MarketStatusService


class GetCurrentStocks:
    def __init__(self, mysql_service):
        self.mysql_service = mysql_service
        self.market_service = MarketStatusService()

    def execute(self, query: GetCurrentStocksQuery):
        market_open = self.market_service.is_market_open()
        market_open = 1
        if query.portfolio_id:
            sql = """
                SELECT s.symbol, s.name
                FROM portfolio_stocks ps
                JOIN stocks s ON ps.stock_id = s.id
                WHERE ps.portfolio_id = %s
            """
            stocks_data = self.mysql_service.execute_query_params(sql, (query.portfolio_id,))
        else:
            sql = "SELECT symbol, name FROM stocks"
            stocks_data = self.mysql_service.execute_query(sql)

        if not stocks_data:
            return {"error": "No se encontraron acciones"}

        results = []
        now = datetime.utcnow().strftime("%Y-%m-%d %H:%M:%S")
        print(f"[DEBUG] stocks_data: {stocks_data}")

        for stock in stocks_data:
            symbol = stock["symbol"]
            name = stock["name"]

            try:
                ticker = yf.Ticker(symbol)
                price = ticker.history(period="1d")["Close"].iloc[-1]

                result_item = {
                    "symbol": symbol,
                    "name": name,
                    "price": float(price),
                    "recorded_at": now,
                    "market_open": market_open
                }

                results.append(result_item)

                if market_open:
                    insert_sql = """
                        INSERT INTO stock_prices (uid, stock_id, price, recorded_at)
                        VALUES (UUID(),
                                (SELECT id FROM stocks WHERE symbol = %s),
                                %s, %s)
                    """
                    self.mysql_service.execute_query_params(insert_sql, (symbol, price, now))

            except Exception as e:
                results.append({
                    "symbol": symbol,
                    "name": name,
                    "error": str(e),
                    "market_open": market_open
                })
        print(f"[DEBUG] market_open: {market_open}")
        return {
            "portfolio_id": query.portfolio_id,
            "updated_at": now,
            "market_open": market_open,
            "data": results
        }
