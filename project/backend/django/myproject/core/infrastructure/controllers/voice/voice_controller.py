from django.http import JsonResponse
from myproject.core.application.UseCase.Voice.VoiceQuery import VoiceQuery
from myproject.shared.infrastructure.bus.query_bus import get_query_bus
from myproject.shared.infrastructure.controller.api_controller import ApiController


class VoicePokemonController(ApiController):
    def __init__(self):
        qb = get_query_bus()
        super().__init__(query_bus=qb)

    def get(self, request, text):
        query = VoiceQuery(text)
        response = self.ask(query)
        return JsonResponse(response, safe=False)

    def register_exceptions(self) -> dict:
        return {
            ValueError: 400,
        }
