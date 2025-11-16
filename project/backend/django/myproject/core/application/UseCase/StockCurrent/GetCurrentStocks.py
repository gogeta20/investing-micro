import yfinance as yf
from datetime import datetime

from myproject.core.application.UseCase.StockCurrent import GetCurrentStocksQuery
from myproject.core.infrastructure.services.market.market_status_service import MarketStatusService


class GetCurrentStocks:
    def __init__(self, mysql_service):
        self.mysql_service = mysql_service
        self.market_service = MarketStatusService()

    def execute(self, query: GetCurrentStocksQuery):
        # ---------------------------------------------
        # 0. Verificar estado del mercado
        # ---------------------------------------------
        market_open = self.market_service.is_market_open()

        # 1. Recuperar símbolos desde DB
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

        # 2. Consultar precios en yfinance
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

                # 3. SOLO GUARDAR SNAPSHOT SI EL MERCADO ESTA ABIERTO
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
