from django.http import JsonResponse
from myproject.core.application.UseCase.StockCurrent.GetCurrentStocksQuery import GetCurrentStocksQuery
from myproject.shared.application.handlers.handlers import get_handler
from myproject.shared.infrastructure.bus.query_bus import QueryBus
from myproject.shared.infrastructure.controller.api_controller import ApiController

class GetCurrentStocksController(ApiController):
    def __init__(self, query_bus: QueryBus = None):
        query_bus = query_bus or QueryBus()
        query_bus.register(GetCurrentStocksQuery, get_handler("GetCurrentStocksQueryHandler"))
        super().__init__(query_bus=query_bus)

    def get(self, request):
        portfolio_id = request.GET.get("portfolio_id")
        query = GetCurrentStocksQuery(portfolio_id=portfolio_id)
        try:
            response = self.ask(query)
            print(f"[DEBUG]dd {str(response)}")
            return JsonResponse(response, safe=False)
        except Exception as e:
            error_data, status_code = self._handle_exception(e)
            return JsonResponse(error_data, status=status_code)

    def register_exceptions(self) -> dict:
        return {
            Exception: 500,
        }
