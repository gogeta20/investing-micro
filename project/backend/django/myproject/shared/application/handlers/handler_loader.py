import os
import importlib
from django.conf import settings
from myproject.shared.domain.bus.query.query_handler import QueryHandler
from myproject.shared.domain.bus.command.command_handler import CommandHandler


def load_handlers():
    handlers = {}

    # Buscar handlers en el contexto core (UseCase antiguo) y en stock (VSA)
    base_paths = [
        os.path.join(settings.BASE_DIR, 'myproject', 'core', 'application', 'UseCase'),
        os.path.join(settings.BASE_DIR, 'myproject', 'stock', 'application', 'queries'),
        os.path.join(settings.BASE_DIR, 'myproject', 'stock', 'application', 'commands'),
    ]

    for base_path in base_paths:
        if not os.path.exists(base_path):
            continue

        for root, _, files in os.walk(base_path):
            for file in files:
                if file.endswith('Handler.py'):
                    module_name = os.path.relpath(os.path.join(root, file), settings.BASE_DIR).replace('/', '.').replace('\\', '.').replace('.py', '')
                    try:
                        module = importlib.import_module(module_name)
                        for attr in dir(module):
                            cls = getattr(module, attr)
                            if isinstance(cls, type) and (
                                (issubclass(cls, QueryHandler) and cls is not QueryHandler) or
                                (issubclass(cls, CommandHandler) and cls is not CommandHandler)
                            ):
                                handlers[cls.__name__] = cls.create()
                    except Exception as e:
                        print(f"[WARNING] No se pudo cargar handler {module_name}: {e}")

    return handlers
