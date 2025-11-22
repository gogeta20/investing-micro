from typing import Optional
from myproject.core.infrastructure.repository.mysql.mysql_service import MySQLService


class GetStockBySymbol:
    def __init__(self, mysql_service: Optional[MySQLService] = None):
        self.mysql_service = mysql_service if mysql_service is not None else MySQLService()

    def execute(self, name):
        data = self.mysql_service.execute_query_params(
            "SELECT * FROM stocks WHERE symbol = %s",
            (name,)
        )
        print(f"[DEBUG] in use case {data}")
        return {"result": data}
