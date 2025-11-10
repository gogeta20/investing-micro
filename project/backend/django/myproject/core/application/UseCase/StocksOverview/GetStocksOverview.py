from myproject.core.application.UseCase.StockCurrent.GetCurrentStocks import GetCurrentStocks
from myproject.core.application.UseCase.StockCurrent.GetCurrentStocksQuery import GetCurrentStocksQuery
from myproject.core.application.UseCase.StockHistory.GetStockHistory import GetStockHistory
from myproject.core.application.UseCase.StockHistory.GetStockHistoryQuery import GetStockHistoryQuery
from myproject.core.application.UseCase.StocksOverview.GetStocksOverviewQuery import GetStocksOverviewQuery
from myproject.core.infrastructure.repository.mysql.mysql_service import MySQLService

class GetStocksOverview:
    def __init__(self, mysql_service: MySQLService):
        self.mysql_service = mysql_service
        self.current_use_case = GetCurrentStocks(mysql_service)
        self.history_use_case = GetStockHistory(mysql_service)

    def execute(self, query: GetStocksOverviewQuery):
        # 1. Traer current (esto ya guarda snapshot)
        current_query = GetCurrentStocksQuery(portfolio_id=query.portfolio_id)
        current_data = self.current_use_case.execute(current_query)

        # 2. Para cada acción traer su último snapshot en histórico
        overview = []
        for stock in current_data["data"]:
            history_query = GetStockHistoryQuery(symbol=stock["symbol"])
            last_snapshot = self.history_use_case.execute(history_query)

            overview.append({
                "portfolio_id": query.portfolio_id,
                "symbol": stock["symbol"],
                "current": {
                    "price": stock["price"],
                    "recorded_at": stock["recorded_at"]
                },
                "last_snapshot": last_snapshot[-1] if last_snapshot else None
            })

        return overview
