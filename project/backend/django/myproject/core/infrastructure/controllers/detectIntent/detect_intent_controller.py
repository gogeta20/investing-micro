from django.http import JsonResponse


from myproject.core.application.UseCase.DetectIntent.DetectIntentQuery import DetectIntentQuery
from myproject.shared.application.handlers.handlers import get_handler
from myproject.shared.infrastructure.bus.query_bus import QueryBus
from myproject.shared.infrastructure.controller.api_controller import ApiController


class DetectIntentController(ApiController):
    def __init__(self, query_bus: QueryBus = None):
        query_bus = query_bus or QueryBus()
        query_bus.register(DetectIntentQuery, get_handler("DetectIntentQueryHandler"))
        super().__init__(query_bus=query_bus)

    def get(self, request, text):
        query = DetectIntentQuery(text)
        response = self.ask(query)

        return JsonResponse(response)

    def register_exceptions(self) -> dict:
        return {
            Exception: 500,
        }
