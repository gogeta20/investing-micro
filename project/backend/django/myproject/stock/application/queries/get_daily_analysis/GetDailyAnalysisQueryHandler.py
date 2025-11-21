from myproject.stock.application.queries.get_daily_analysis.GetDailyAnalysis import GetDailyAnalysis
from myproject.stock.application.queries.get_daily_analysis.GetDailyAnalysisQuery import GetDailyAnalysisQuery
from myproject.shared.domain.bus.query.query_handler import QueryHandler
from myproject.core.infrastructure.repository.mysql.mysql_service import MySQLService


class GetDailyAnalysisQueryHandler(QueryHandler):
    def __init__(self, use_case):
        self.use_case = use_case

    def handle(self, query: GetDailyAnalysisQuery):
        return self.use_case.execute(query)

    @classmethod
    def create(cls):
        mysql_service = MySQLService()
        use_case = GetDailyAnalysis(mysql_service)
        return cls(use_case)
