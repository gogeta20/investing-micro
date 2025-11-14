from datetime import datetime

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

        # Debug: ver qué está devolviendo GetCurrentStocks
        print(f"[DEBUG] GetStocksOverview - portfolio_id: {query.portfolio_id}")
        print(
            f"[DEBUG] GetStocksOverview - current_data keys: {current_data.keys() if isinstance(current_data, dict) else 'not a dict'}")
        print(f"[DEBUG] GetStocksOverview - current_data: {current_data}")

        # Validar que no haya error en la respuesta
        if "error" in current_data:
            return {"error": current_data["error"]}

        # 2. Para cada acción traer su último snapshot en histórico
        overview = []
        for stock in current_data.get("data", []):
            # Saltar stocks con error
            if "error" in stock:
                continue

            # Validar que tenga los campos necesarios
            if "symbol" not in stock or "price" not in stock:
                continue

            try:
                history_query = GetStockHistoryQuery(symbol=stock["symbol"])
                last_snapshot = self.history_use_case.execute(history_query)

                # Obtener el último snapshot que no sea del día actual
                last_snapshot_data = None
                if last_snapshot and len(last_snapshot) > 0:
                    # Filtrar snapshots que no sean del día actual
                    today = datetime.utcnow().date()

                    for snapshot in reversed(last_snapshot):
                        # Manejar tanto datetime como string
                        recorded_at = snapshot["recorded_at"]

                        # Si ya es datetime, obtener solo la fecha
                        if isinstance(recorded_at, datetime):
                            snapshot_date = recorded_at.date()
                        else:
                            # Si es string, parsearlo
                            snapshot_date = datetime.strptime(recorded_at, "%Y-%m-%d %H:%M:%S").date()

                        if snapshot_date < today:
                            last_snapshot_data = snapshot
                            break

                    # Si no hay snapshot anterior, usar el último disponible
                    if last_snapshot_data is None and len(last_snapshot) > 0:
                        last_snapshot_data = last_snapshot[-1]

                overview.append({
                    "portfolio_id": query.portfolio_id,
                    "symbol": stock["symbol"],
                    "name": stock.get("name", "Unknown"),  # ⬅️ Añadir esto
                    "current": {
                        "price": stock["price"],
                        "recorded_at": stock["recorded_at"]
                    },
                    "last_snapshot": last_snapshot_data
                })
            except Exception as e:
                # Continuar con el siguiente stock si hay error
                print(f"[ERROR] GetStocksOverview failed for {stock.get('symbol', 'unknown')}: {str(e)}")
                continue

        return overview
