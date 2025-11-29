from myproject.stock.application.queries.search_stock_by_symbol.SearchStockBySymbolQuery import SearchStockBySymbolQuery
from myproject.stock.application.queries.search_stock_by_symbol.SearchStockBySymbol import SearchStockBySymbol
from myproject.shared.domain.bus.query.query_handler import QueryHandler


class SearchStockBySymbolQueryHandler(QueryHandler):
    def __init__(self, use_case: SearchStockBySymbol):
        self.use_case = use_case

    def handle(self, query: SearchStockBySymbolQuery):
        return self.use_case.execute(query)

    @classmethod
    def create(cls):
        use_case = SearchStockBySymbol()
        return cls(use_case)
