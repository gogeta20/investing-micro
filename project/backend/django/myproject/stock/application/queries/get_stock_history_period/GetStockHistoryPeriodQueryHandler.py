from myproject.stock.application.queries.get_stock_history_period.GetStockHistoryPeriod import GetStockHistoryPeriod
from myproject.stock.application.queries.get_stock_history_period.GetStockHistoryPeriodQuery import GetStockHistoryPeriodQuery
from myproject.shared.domain.bus.query.query_handler import QueryHandler


class GetStockHistoryPeriodQueryHandler(QueryHandler):
    def __init__(self, use_case: GetStockHistoryPeriod):
        self.use_case = use_case

    def handle(self, query: GetStockHistoryPeriodQuery):
        return self.use_case.execute(query)

    @classmethod
    def create(cls):
        use_case = GetStockHistoryPeriod()
        return cls(use_case)
