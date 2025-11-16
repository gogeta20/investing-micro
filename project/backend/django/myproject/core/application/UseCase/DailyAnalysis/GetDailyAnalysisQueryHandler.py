from myproject.core.application.UseCase.DailyAnalysis.GetDailyAnalysis import GetDailyAnalysis
from myproject.core.application.UseCase.DailyAnalysis.GetDailyAnalysisQuery import GetDailyAnalysisQuery
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
