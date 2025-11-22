from myproject.stock.application.queries.get_daily_analysis.GetDailyAnalysis import GetDailyAnalysis
from myproject.stock.application.queries.get_daily_analysis.GetDailyAnalysisQuery import GetDailyAnalysisQuery
from myproject.shared.domain.bus.query.query_handler import QueryHandler


class GetDailyAnalysisQueryHandler(QueryHandler):
    def __init__(self, use_case: GetDailyAnalysis):
        self.use_case = use_case

    def handle(self, query: GetDailyAnalysisQuery):
        return self.use_case.execute(query)

    @classmethod
    def create(cls):
        use_case = GetDailyAnalysis()
        return cls(use_case)
