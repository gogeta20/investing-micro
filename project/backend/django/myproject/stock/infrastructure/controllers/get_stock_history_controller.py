from django.http import JsonResponse
from myproject.stock.application.queries.get_stock_history.GetStockHistoryQuery import GetStockHistoryQuery
from myproject.shared.infrastructure.bus.query_bus import get_query_bus
from myproject.shared.infrastructure.controller.api_controller import ApiController


class GetStockHistoryController(ApiController):
    def __init__(self):
        qb = get_query_bus()
        super().__init__(query_bus=qb)

    def get(self, request, symbol: str):
        from_date = request.GET.get("from")
        to_date = request.GET.get("to")
        query = GetStockHistoryQuery(symbol=symbol, from_date=from_date, to_date=to_date)
        response = self.ask(query)
        return JsonResponse({"symbol": symbol, "history": response})

    def register_exceptions(self) -> dict:
        return {
            ValueError: 400,
        }
