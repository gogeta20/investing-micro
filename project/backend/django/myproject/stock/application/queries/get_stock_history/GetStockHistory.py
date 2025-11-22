from typing import Optional
from myproject.core.infrastructure.repository.mysql.mysql_service import MySQLService


class GetStockHistory:
    def __init__(self, mysql_service: Optional[MySQLService] = None):
        self.mysql_service = mysql_service if mysql_service is not None else MySQLService()

    def execute(self, query):
        sql = """
            SELECT sp.price, sp.recorded_at
            FROM stock_prices sp
            JOIN stocks s ON sp.stock_id = s.id
            WHERE s.symbol = %s
        """
        params = [query.symbol]

        if query.from_date:
            sql += " AND sp.recorded_at >= %s"
            params.append(query.from_date)

        if query.to_date:
            sql += " AND sp.recorded_at <= %s"
            params.append(query.to_date)

        sql += " ORDER BY sp.recorded_at ASC"

        return self.mysql_service.execute_query_params(sql, tuple(params))
