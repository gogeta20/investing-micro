from django.http import JsonResponse
from myproject.core.application.UseCase.GetResultsPortafolio.GetResultsPortafolioQuery import GetResultsPortafolioQuery
from myproject.shared.infrastructure.bus.query_bus import get_query_bus
from myproject.shared.infrastructure.controller.api_controller import ApiController


class GetResultsPortafolioController(ApiController):
    def __init__(self):
        qb = get_query_bus()
        super().__init__(query_bus=qb)

    def get(self, request, id_portafolio):
        query = GetResultsPortafolioQuery(portfolio_id=id_portafolio)
        response = self.ask(query)
        return JsonResponse(response, safe=False)

    def register_exceptions(self) -> dict:
        return {
            ValueError: 400,
        }
