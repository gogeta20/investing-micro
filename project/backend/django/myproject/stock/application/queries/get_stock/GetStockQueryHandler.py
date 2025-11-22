from myproject.stock.application.queries.get_stock.GetStock import GetStock
from myproject.stock.application.queries.get_stock.GetStockQuery import GetStockQuery
from myproject.shared.domain.bus.query.query_handler import QueryHandler
from myproject.shared.domain.response import BaseResponse


class GetStockQueryHandler(QueryHandler):
    def __init__(self, use_case: GetStock):
        self.use_case = use_case

    def handle(self, query: GetStockQuery):
        result = self.use_case.execute()
        return BaseResponse(
            data={
                "result": result
            },
            message="success request",
            status=200
        ).to_dict()

    @classmethod
    def create(cls):
        use_case = GetStock()
        return cls(use_case)
