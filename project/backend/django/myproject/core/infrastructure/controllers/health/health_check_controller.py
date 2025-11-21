from django.http import JsonResponse
from myproject.core.application.UseCase.HealthCheck.health_check_query import HealthCheckQuery
from myproject.shared.infrastructure.bus.query_bus import get_query_bus
from myproject.shared.infrastructure.controller.api_controller import ApiController


class HealthCheckController(ApiController):
    def __init__(self):
        qb = get_query_bus()
        super().__init__(query_bus=qb)

    def get(self, request):
        query = HealthCheckQuery()
        response = self.ask(query)
        return JsonResponse(response, safe=False)

    def register_exceptions(self) -> dict:
        return {
            ValueError: 400,
        }
