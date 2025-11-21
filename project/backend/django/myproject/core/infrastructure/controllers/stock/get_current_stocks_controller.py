from django.http import JsonResponse
from myproject.core.application.UseCase.StockCurrent.GetCurrentStocksQuery import GetCurrentStocksQuery
from myproject.shared.infrastructure.bus.query_bus import get_query_bus
from myproject.shared.infrastructure.controller.api_controller import ApiController


class GetCurrentStocksController(ApiController):
    def __init__(self):
        qb = get_query_bus()
        super().__init__(query_bus=qb)

    def get(self, request):
        portfolio_id = request.GET.get("portfolio_id")
        query = GetCurrentStocksQuery(portfolio_id=portfolio_id)
        response = self.ask(query)
        return JsonResponse(response, safe=False)

    def register_exceptions(self) -> dict:
        return {
            ValueError: 400,
        }
