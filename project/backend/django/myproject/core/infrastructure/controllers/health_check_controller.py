from rest_framework.views import APIView
from django.http import JsonResponse
from myproject.core.application.queries.health_check_query import HealthCheckQuery
from myproject.shared.infrastructure.bus.query_bus import QueryBus

class HealthCheckController(APIView):
    def __init__(self):
        super().__init__()
        self.query_bus = QueryBus()
        # Registrar el handler
        from myproject.core.application.queries.health_check_query_handler import HealthCheckQueryHandler
        self.query_bus.register(HealthCheckQuery, HealthCheckQueryHandler())

    def get(self, request):
        query = HealthCheckQuery()
        result = self.query_bus.ask(query)
        return JsonResponse(result)
