from myproject.stock.application.queries.get_results_portafolio.GetResultsPortafolioQuery import GetResultsPortafolioQuery
from myproject.stock.application.queries.get_results_portafolio.GetResultsPortafolio import GetResultsPortfolio
from myproject.shared.domain.bus.query.query_handler import QueryHandler
from myproject.shared.domain.response import BaseResponse


class GetResultsPortafolioQueryHandler(QueryHandler):
    def __init__(self, use_case: GetResultsPortfolio):
        self.use_case = use_case

    def handle(self, query: GetResultsPortafolioQuery):
        result = self.use_case.execute(query)
        return BaseResponse(
            data={
                "result": result
            },
            message="success request",
            status=200
        ).to_dict()

    @classmethod
    def create(cls):
        use_case = GetResultsPortfolio()
        return cls(use_case)
