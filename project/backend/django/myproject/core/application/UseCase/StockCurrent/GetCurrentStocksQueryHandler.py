from myproject.core.application.UseCase.StockCurrent.GetCurrentStocks import GetCurrentStocks
from myproject.core.application.UseCase.StockCurrent.GetCurrentStocksQuery import GetCurrentStocksQuery
from myproject.shared.domain.bus.query.query_handler import QueryHandler
from myproject.core.infrastructure.repository.mysql.mysql_service import MySQLService

class GetCurrentStocksQueryHandler(QueryHandler):
    def __init__(self, use_case):
        self.use_case = use_case

    def handle(self, query: GetCurrentStocksQuery):
        return self.use_case.execute(query)

    @classmethod
    def create(cls):
        mysql_service = MySQLService()
        use_case = GetCurrentStocks(mysql_service)
        return cls(use_case)
