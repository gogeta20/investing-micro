from myproject.stock.application.queries.get_stocks_overview.GetStocksOverview import GetStocksOverview
from myproject.stock.application.queries.get_stocks_overview.GetStocksOverviewQuery import GetStocksOverviewQuery
from myproject.shared.domain.bus.query.query_handler import QueryHandler


class GetStocksOverviewQueryHandler(QueryHandler):
    def __init__(self, use_case: GetStocksOverview):
        self.use_case = use_case

    def handle(self, query: GetStocksOverviewQuery):
        return self.use_case.execute(query)

    @classmethod
    def create(cls):
        use_case = GetStocksOverview()
        return cls(use_case)
