import os
import importlib
from django.conf import settings
from myproject.shared.domain.bus.query.query_handler import QueryHandler  # Asegúrate de que la ruta sea correcta
from myproject.shared.domain.bus.command.command_handler import CommandHandler  # Asegúrate de que la ruta sea correcta

def load_handlers():
    handlers = {}
    base_path = os.path.join(settings.BASE_DIR, 'myproject', 'core', 'application', 'UseCase')
    for root, _, files in os.walk(base_path):
        for file in files:
            if file.endswith('Handler.py'):
                module_name = os.path.relpath(os.path.join(root, file), settings.BASE_DIR).replace('/', '.').replace('\\', '.').replace('.py', '')
                module = importlib.import_module(module_name)
                for attr in dir(module):
                    cls = getattr(module, attr)
                    if isinstance(cls, type) and (
                        (issubclass(cls, QueryHandler) and cls is not QueryHandler) or
                        (issubclass(cls, CommandHandler) and cls is not CommandHandler)
                    ):
                        handlers[cls.__name__] = cls.create()
    return handlers
