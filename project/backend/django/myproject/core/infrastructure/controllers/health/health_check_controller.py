from django.http import JsonResponse

from myproject.core.application.UseCase.HealthCheck.HealthCheckQueryHandler import HealthCheckQueryHandler
from myproject.core.application.UseCase.HealthCheck.health_check_query import HealthCheckQuery
from myproject.shared.infrastructure.controller.api_controller import ApiController
from myproject.shared.infrastructure.bus.query_bus import QueryBus


class HealthCheckController(ApiController):
    def __init__(self, query_bus: QueryBus = None):
        query_bus = query_bus or QueryBus()
        query_bus.register(HealthCheckQuery, HealthCheckQueryHandler())
        super().__init__(query_bus=query_bus)

    def get(self, request):
        query = HealthCheckQuery()
        try:
            response = self.ask(query)
            return JsonResponse(response, safe=False)
        except Exception as e:
            error_data, status_code = self._handle_exception(e)
            return JsonResponse(error_data, status=status_code)

    def register_exceptions(self) -> dict:
        return {
            Exception: 500,
        }
