from myproject.core.application.UseCase.StockHistory.GetStockHistory import GetStockHistory
from myproject.core.application.UseCase.StockHistory.GetStockHistoryQuery import GetStockHistoryQuery
from myproject.core.infrastructure.repository.mysql.mysql_service import MySQLService
from myproject.shared.domain.bus.query.query_handler import QueryHandler

class GetStockHistoryQueryHandler(QueryHandler):
    def __init__(self, use_case):
        self.use_case = use_case

    def handle(self, query: GetStockHistoryQuery):
        return self.use_case.execute(query)

    @classmethod
    def create(cls):
        mysql_service = MySQLService()
        use_case = GetStockHistory(mysql_service)
        return cls(use_case)
