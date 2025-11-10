from django.http import JsonResponse

from myproject.core.application.UseCase.Logs.LogsQuery import LogsQuery
from myproject.shared.application.handlers.handlers import get_handler
from myproject.shared.infrastructure.bus.query_bus import QueryBus
from myproject.shared.infrastructure.controller.api_controller import ApiController


class LogsController(ApiController):
    def __init__(self, query_bus: QueryBus = None):
        query_bus = query_bus or QueryBus()
        query_bus.register(LogsQuery, get_handler("LogsQueryHandler"))
        super().__init__(query_bus=query_bus)

    def get(self, request, text):
        query = LogsQuery(text)
        response = self.ask(query)

        return JsonResponse(response)

    def register_exceptions(self) -> dict:
        return {
            Exception: 500,
        }
