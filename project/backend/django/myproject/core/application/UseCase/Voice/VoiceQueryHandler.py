from sklearn.feature_extraction.text import CountVectorizer
from sklearn.naive_bayes import MultinomialNB

from myproject.core.application.UseCase.Voice.Voice import Voice
from myproject.core.infrastructure.repository.mongo.mongo_repository import MongoRepository
from myproject.core.Domain.Model.Pokemon import Pokemon

from myproject.core.application.UseCase.Voice.VoiceQuery import VoiceQuery
from myproject.core.infrastructure.repository.mysql.mysql_repository import PokemonRepository
from myproject.shared.domain.bus.query.query_handler import QueryHandler
from myproject.shared.domain.response import BaseResponse
# import kagglehub
import json

import os
from django.conf import settings
from joblib import dump, load

from myproject.shared.domain.services.IVoiceMLService import IVoiceMLService
from myproject.shared.infrastructure.services.voice.VoiceMLService import VoiceMLService


class VoiceQueryHandler(QueryHandler):
    def __init__(self, use_case):
        self.use_case = use_case

    def handle(self, query: VoiceQuery):
        text = query.get_text()
        result = self.use_case.execute(text)

        return BaseResponse(
            data={
                "status": "OK",
                "intent": result["intent"],
                "pokemon": result["pokemon"],
                "confidence": result["confidence"],
                "result": result["result"]
            },
            message="success request",
            status=200
        ).to_dict()

    @classmethod
    def create(cls):
        model_path = os.path.join(settings.BASE_DIR, 'data', 'model', 'model.joblib')
        intents_path = os.path.join(settings.BASE_DIR, 'data', 'mongodb', 'intents.json')
        ml_service = VoiceMLService(model_path, intents_path)
        pokemon_repository = PokemonRepository()
        use_case = Voice(ml_service, pokemon_repository)
        return cls(use_case)
