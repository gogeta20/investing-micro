from typing import Optional
from myproject.stock.application.queries.get_portfolios_list.GetPortfoliosListQuery import GetPortfoliosListQuery
from myproject.core.infrastructure.repository.mysql.mysql_service import MySQLService


class GetPortfoliosList:
    def __init__(self, mysql_service: Optional[MySQLService] = None):
        self.mysql_service = mysql_service if mysql_service is not None else MySQLService()

    def execute(self, query: GetPortfoliosListQuery):
        try:
            # Obtener todos los portafolios con sus acciones
            portfolios_sql = """
                SELECT
                    p.id,
                    p.name,
                    p.created_at,
                    s.id as stock_id,
                    s.symbol
                FROM portfolios p
                LEFT JOIN portfolio_stocks ps ON p.id = ps.portfolio_id
                LEFT JOIN stocks s ON ps.stock_id = s.id
                ORDER BY p.id ASC, s.id ASC
            """

            rows = self.mysql_service.execute_query(portfolios_sql)

            if not rows:
                return {"data": []}

            # Agrupar por portafolio
            portfolios_dict = {}
            for row in rows:
                portfolio_id = row["id"]

                if portfolio_id not in portfolios_dict:
                    portfolios_dict[portfolio_id] = {
                        "id": portfolio_id,
                        "name": row["name"],
                        "created_at": row["created_at"].strftime("%Y-%m-%dT%H:%M:%SZ") if row["created_at"] else None,
                        "stocks": []
                    }

                # Agregar stock si existe
                if row["stock_id"]:
                    stock = {
                        "id": row["stock_id"],
                        "symbol": row["symbol"]
                    }
                    # Evitar duplicados
                    if stock not in portfolios_dict[portfolio_id]["stocks"]:
                        portfolios_dict[portfolio_id]["stocks"].append(stock)

            # Convertir a lista y agregar stocks_count
            portfolios_list = []
            for portfolio in portfolios_dict.values():
                portfolio["stocks_count"] = len(portfolio["stocks"])
                portfolios_list.append(portfolio)

            return {"data": portfolios_list}

        except Exception as e:
            print(f"[ERROR] GetPortfoliosList failed: {str(e)}")
            return {
                "error": "Error al cargar los portafolios",
                "message": str(e)
            }
