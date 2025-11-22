from typing import Optional
from myproject.core.infrastructure.repository.mysql.mysql_service import MySQLService


class GetStock:
    def __init__(self, mysql_service: Optional[MySQLService] = None):
        self.mysql_service = mysql_service if mysql_service is not None else MySQLService()

    def execute(self):
        data = self.mysql_service.execute_query(f"SELECT * FROM stocks")
        return {"result": data}
