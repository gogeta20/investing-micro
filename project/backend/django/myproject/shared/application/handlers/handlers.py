from myproject.shared.application.handlers.handler_loader import load_handlers
import os
from django.conf import settings

from myproject.core.infrastructure.repository.mysql.mysql_repository import PokemonRepository
from myproject.shared.infrastructure.services.voice.VoiceMLService import VoiceMLService

model_path = os.path.join(settings.BASE_DIR, 'data', 'model', 'model.joblib')
intents_path = os.path.join(settings.BASE_DIR, 'data', 'mongodb', 'intents.json')

ml_service = VoiceMLService(model_path, intents_path)
pokemon_repository = PokemonRepository()
handlers = load_handlers()

def get_handler(handler_name):
    handler = handlers.get(handler_name)
    if not handler:
        raise Exception(f"El handler '{handler_name}' no está registrado.")
    return handler
