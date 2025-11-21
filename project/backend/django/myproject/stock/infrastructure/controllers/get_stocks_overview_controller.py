from django.http import JsonResponse
from myproject.stock.application.queries.get_stocks_overview.GetStocksOverviewQuery import GetStocksOverviewQuery
from myproject.shared.infrastructure.bus.query_bus import get_query_bus
from myproject.shared.infrastructure.controller.api_controller import ApiController


class GetStocksOverviewController(ApiController):
    def __init__(self):
        qb = get_query_bus()
        super().__init__(query_bus=qb)

    def get(self, request):
        portfolio_id = self.get_portfolio_id(request)
        query = GetStocksOverviewQuery(portfolio_id=portfolio_id)
        response = self.ask(query)
        return JsonResponse({"data": response}, safe=False)

    def register_exceptions(self) -> dict:
        return {
            ValueError: 400,
        }

    def get_portfolio_id(self, request):
        portfolio_id = request.GET.get("portfolio_id")
        if portfolio_id:
            try:
                portfolio_id = int(portfolio_id)
            except (ValueError, TypeError):
                portfolio_id = 1
        else:
            portfolio_id = 1
        return portfolio_id
