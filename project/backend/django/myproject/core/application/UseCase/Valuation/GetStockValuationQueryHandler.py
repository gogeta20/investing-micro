from myproject.core.application.UseCase.Valuation.GetStockValuation import GetStockValuation
from myproject.core.application.UseCase.Valuation.GetStockValuationQuery import GetStockValuationQuery
from myproject.shared.domain.bus.query.query_handler import QueryHandler
from myproject.core.infrastructure.repository.mysql.mysql_service import MySQLService


class GetStockValuationQueryHandler(QueryHandler):
    def __init__(self, use_case: GetStockValuation):
        self.use_case = use_case

    def handle(self, query: GetStockValuationQuery):
        return self.use_case.execute(query)

    @classmethod
    def create(cls):
        mysql_service = MySQLService()
        use_case = GetStockValuation(mysql_service)
        return cls(use_case)
