from myproject.stock.application.queries.get_stock_by_symbol.GetStockBySymbolQuery import GetStockBySymbolQuery
from myproject.stock.application.queries.get_stock_by_symbol.GetStockBySymbol import GetStockBySymbol
from myproject.shared.domain.bus.query.query_handler import QueryHandler
from myproject.shared.domain.response import BaseResponse


class GetStockBySymbolQueryHandler(QueryHandler):
    def __init__(self, use_case: GetStockBySymbol):
        self.use_case = use_case

    def handle(self, query: GetStockBySymbolQuery):
        text = query.get_text()
        result = self.use_case.execute(text)
        return BaseResponse(
            data={
                "result": result
            },
            message="success request",
            status=200
        ).to_dict()

    @classmethod
    def create(cls):
        use_case = GetStockBySymbol()
        return cls(use_case)
