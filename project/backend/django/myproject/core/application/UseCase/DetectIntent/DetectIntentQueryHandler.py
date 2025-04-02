import os
from django.conf import settings
# from myproject.core.application.UseCase.Voice.Voice import Voice
# from myproject.core.application.UseCase.Voice.VoiceQuery import VoiceQuery
# from myproject.core.infrastructure.repository.mysql.mysql_repository import PokemonRepository

from myproject.core.application.UseCase.DetectIntent.DetectIntent import DetectIntent
from myproject.core.application.UseCase.DetectIntent.DetectIntentQuery import DetectIntentQuery
from myproject.shared.domain.bus.query.query_handler import QueryHandler
from myproject.shared.domain.response import BaseResponse
from myproject.shared.infrastructure.services.voice.IntentClassifierService import IntentClassifierService
from myproject.shared.infrastructure.services.voice.VoiceMLService import VoiceMLService

class DetectIntentQueryHandler(QueryHandler):
    def __init__(self, use_case):
        self.use_case = use_case

    def handle(self, query: DetectIntentQuery):
        text = query.get_text()
        result = self.use_case.execute(text)

        return BaseResponse(
            data={
                "intent": result["intent"],
                "confidence": result["confidence"],
            },
            message="success request",
            status=200
        ).to_dict()

    @classmethod
    def create(cls):
        model_path = os.path.join(settings.BASE_DIR, 'data', 'model', 'model_restaurant.joblib')
        intents_path = os.path.join(settings.BASE_DIR, 'data', 'dataset', 'intents_restaurant.json')
        # ml_service = VoiceMLService(model_path, intents_path)
        classifier_service = IntentClassifierService(model_path, intents_path)
        # pokemon_repository = PokemonRepository()
        # use_case = Voice(ml_service, pokemon_repository)
        use_case = DetectIntent(classifier_service)
        return cls(use_case)
