from typing import Optional
from myproject.stock.application.queries.get_results_portafolio.GetResultsPortafolioQuery import GetResultsPortafolioQuery
from myproject.core.infrastructure.repository.mysql.mysql_service import MySQLService


class GetResultsPortfolio:
    def __init__(self, mysql_service: Optional[MySQLService] = None):
        self.mysql_service = mysql_service if mysql_service is not None else MySQLService()

    def execute(self, query: GetResultsPortafolioQuery):
        try:
            query_request = """
                SELECT s.symbol, sp.price, sp.recorded_at
                FROM portfolio_stocks ps
                JOIN stocks s ON ps.stock_id = s.id
                JOIN stock_prices sp ON sp.stock_id = s.id
                WHERE ps.portfolio_id = %s
                  AND sp.recorded_at = (
                    SELECT MAX(recorded_at)
                    FROM stock_prices
                    WHERE stock_id = s.id
                )
            """
            rows = self.mysql_service.execute_query_params(query_request, (query.portfolio_id,))

            if not rows:
                return {"error": f"No hay datos para portfolio {query.portfolio_id}"}

            total_value = sum(row["price"] for row in rows)

            return {
                "portfolio_id": query.portfolio_id,
                "total_value": total_value,
                "stocks": rows
            }
        except Exception as e:
            print(f"[ERROR] GetResultsPortfolio failed: {str(e)}")
            return {"error": str(e)}
