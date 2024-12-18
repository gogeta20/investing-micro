from django.http import JsonResponse

from myproject.core.application.UseCase.Voice.VoiceQuery import VoiceQuery
from myproject.core.application.UseCase.Voice.VoiceQueryHandler import VoiceQueryHandler
from myproject.shared.infrastructure.bus.query_bus import QueryBus
from myproject.shared.infrastructure.controller.api_controller import ApiController


class VoicePokemonController(ApiController):
    def __init__(self, query_bus: QueryBus = None):
        query_bus = query_bus or QueryBus()
        query_bus.register(VoiceQuery, VoiceQueryHandler())
        super().__init__(query_bus=query_bus)

    def get(self, request, text):
        query = VoiceQuery(text)
        response = self.ask(query)

        return JsonResponse(response)

    def register_exceptions(self) -> dict:
        return {
            Exception: 500,
        }
