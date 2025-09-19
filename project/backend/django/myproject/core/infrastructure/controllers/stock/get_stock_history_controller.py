from django.http import JsonResponse
from myproject.core.application.UseCase.StockHistory.GetStockHistoryQuery import GetStockHistoryQuery
from myproject.shared.application.handlers.handlers import get_handler
from myproject.shared.infrastructure.bus.query_bus import QueryBus
from myproject.shared.infrastructure.controller.api_controller import ApiController

class GetStockHistoryController(ApiController):
    def __init__(self, query_bus: QueryBus = None):
        query_bus = query_bus or QueryBus()
        query_bus.register(GetStockHistoryQuery, get_handler("GetStockHistoryQueryHandler"))
        super().__init__(query_bus=query_bus)

    def get(self, request, symbol: str):
        from_date = request.GET.get("from")
        to_date = request.GET.get("to")

        query = GetStockHistoryQuery(symbol=symbol, from_date=from_date, to_date=to_date)
        result = self.ask(query)
        return JsonResponse({"symbol": symbol, "history": result})

    def register_exceptions(self) -> dict:
        return {
            Exception: 500,
        }
