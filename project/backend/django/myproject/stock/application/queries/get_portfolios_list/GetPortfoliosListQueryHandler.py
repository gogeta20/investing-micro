from myproject.stock.application.queries.get_portfolios_list.GetPortfoliosList import GetPortfoliosList
from myproject.stock.application.queries.get_portfolios_list.GetPortfoliosListQuery import GetPortfoliosListQuery
from myproject.shared.domain.bus.query.query_handler import QueryHandler


class GetPortfoliosListQueryHandler(QueryHandler):
    def __init__(self, use_case: GetPortfoliosList):
        self.use_case = use_case

    def handle(self, query: GetPortfoliosListQuery):
        return self.use_case.execute(query)

    @classmethod
    def create(cls):
        use_case = GetPortfoliosList()
        return cls(use_case)
