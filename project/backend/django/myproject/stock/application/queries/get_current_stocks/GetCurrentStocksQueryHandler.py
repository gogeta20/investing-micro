from myproject.stock.application.queries.get_current_stocks.GetCurrentStocks import GetCurrentStocks
from myproject.stock.application.queries.get_current_stocks.GetCurrentStocksQuery import GetCurrentStocksQuery
from myproject.shared.domain.bus.query.query_handler import QueryHandler


class GetCurrentStocksQueryHandler(QueryHandler):
    def __init__(self, use_case: GetCurrentStocks):
        self.use_case = use_case

    def handle(self, query: GetCurrentStocksQuery):
        return self.use_case.execute(query)

    @classmethod
    def create(cls):
        use_case = GetCurrentStocks()
        return cls(use_case)
