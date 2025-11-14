from django.http import JsonResponse
from myproject.core.application.UseCase.StocksOverview.GetStocksOverviewQuery import GetStocksOverviewQuery
from myproject.shared.application.handlers.handlers import get_handler
from myproject.shared.infrastructure.bus.query_bus import QueryBus
from myproject.shared.infrastructure.controller.api_controller import ApiController

class GetStocksOverviewController(ApiController):
    def __init__(self, query_bus: QueryBus = None):
        query_bus = query_bus or QueryBus()
        query_bus.register(GetStocksOverviewQuery, get_handler("GetStocksOverviewQueryHandler"))
        super().__init__(query_bus=query_bus)

    def get(self, request):
        portfolio_id = request.GET.get("portfolio_id")
        # Convertir a int si viene, usar 1 por defecto si no viene (igual que el frontend)
        if portfolio_id:
            try:
                portfolio_id = int(portfolio_id)
            except (ValueError, TypeError):
                portfolio_id = 1  # Por defecto usar 1
        else:
            portfolio_id = 1  # Por defecto usar 1
        query = GetStocksOverviewQuery(portfolio_id=portfolio_id)
        try:
            response = self.ask(query)
            return JsonResponse({"data": response}, safe=False)
        except Exception as e:
            error_data, status_code = self._handle_exception(e)
            return JsonResponse(error_data, status=status_code)

    def register_exceptions(self) -> dict:
        return {
            Exception: 500,
        }
