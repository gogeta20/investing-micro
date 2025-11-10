from myproject.core.application.UseCase.StocksOverview.GetStocksOverview import GetStocksOverview
from myproject.core.application.UseCase.StocksOverview.GetStocksOverviewQuery import GetStocksOverviewQuery
from myproject.shared.domain.bus.query.query_handler import QueryHandler
from myproject.core.infrastructure.repository.mysql.mysql_service import MySQLService

class GetStocksOverviewQueryHandler(QueryHandler):
    def __init__(self, use_case):
        self.use_case = use_case

    def handle(self, query: GetStocksOverviewQuery):
        return self.use_case.execute(query)

    @classmethod
    def create(cls):
        mysql_service = MySQLService()
        use_case = GetStocksOverview(mysql_service)
        return cls(use_case)
