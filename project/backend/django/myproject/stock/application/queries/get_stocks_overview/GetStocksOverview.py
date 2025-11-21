from datetime import datetime
from myproject.stock.application.queries.get_current_stocks.GetCurrentStocks import GetCurrentStocks
from myproject.stock.application.queries.get_current_stocks.GetCurrentStocksQuery import GetCurrentStocksQuery
from myproject.stock.application.queries.get_stock_history.GetStockHistory import GetStockHistory
from myproject.stock.application.queries.get_stock_history.GetStockHistoryQuery import GetStockHistoryQuery
from myproject.stock.application.queries.get_stocks_overview.GetStocksOverviewQuery import GetStocksOverviewQuery
from myproject.core.infrastructure.repository.mysql.mysql_service import MySQLService


class GetStocksOverview:
    def __init__(self, mysql_service: MySQLService):
        self.mysql_service = mysql_service
        self.current_use_case = GetCurrentStocks(mysql_service)
        self.history_use_case = GetStockHistory(mysql_service)

    def execute(self, query: GetStocksOverviewQuery):
        current_query = GetCurrentStocksQuery(portfolio_id=query.portfolio_id)
        current_data = self.current_use_case.execute(current_query)

        print(f"[DEBUG] GetStocksOverview - portfolio_id: {query.portfolio_id}")
        print(
            f"[DEBUG] GetStocksOverview - current_data keys: {current_data.keys() if isinstance(current_data, dict) else 'not a dict'}")
        print(f"[DEBUG] GetStocksOverview - current_data: {current_data}")

        if "error" in current_data:
            return {"error": current_data["error"]}

        overview = []
        for stock in current_data.get("data", []):
            if "error" in stock:
                continue

            if "symbol" not in stock or "price" not in stock:
                continue

            try:
                history_query = GetStockHistoryQuery(symbol=stock["symbol"])
                last_snapshot = self.history_use_case.execute(history_query)

                last_snapshot_data = None
                if last_snapshot and len(last_snapshot) > 0:
                    today = datetime.utcnow().date()

                    for snapshot in reversed(last_snapshot):
                        recorded_at = snapshot["recorded_at"]

                        if isinstance(recorded_at, datetime):
                            snapshot_date = recorded_at.date()
                        else:
                            snapshot_date = datetime.strptime(recorded_at, "%Y-%m-%d %H:%M:%S").date()

                        if snapshot_date < today:
                            last_snapshot_data = snapshot
                            break

                    if last_snapshot_data is None and len(last_snapshot) > 0:
                        last_snapshot_data = last_snapshot[-1]

                overview.append({
                    "portfolio_id": query.portfolio_id,
                    "symbol": stock["symbol"],
                    "name": stock.get("name", "Unknown"),
                    "current": {
                        "price": stock["price"],
                        "recorded_at": stock["recorded_at"]
                    },
                    "last_snapshot": last_snapshot_data
                })
            except Exception as e:
                print(f"[ERROR] GetStocksOverview failed for {stock.get('symbol', 'unknown')}: {str(e)}")
                continue

        return overview
