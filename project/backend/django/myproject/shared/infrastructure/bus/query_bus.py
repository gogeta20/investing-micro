import inspect
from typing import Any, get_type_hints, get_origin, get_args
from myproject.shared.domain.bus.query.query import Query
from myproject.shared.domain.bus.query.query_handler import QueryHandler
from myproject.shared.domain.bus.command.command_handler import CommandHandler
from myproject.shared.application.handlers.handler_loader import load_handlers


class QueryBus:
    def __init__(self, auto_register: bool = True):
        self._handlers = {}
        if auto_register:
            self._auto_register_handlers()

    def _auto_register_handlers(self):
        """Auto-registra todos los handlers descubriendo qué Query manejan"""
        handlers_dict = load_handlers()
        for handler_name, handler_instance in handlers_dict.items():
            # Obtener la clase del handler para inspeccionar el método handle()
            handler_class = handler_instance.__class__

            # Filtrar CommandHandlers - no deben ser procesados por QueryBus
            if issubclass(handler_class, CommandHandler):
                continue  # Los CommandHandlers no son responsabilidad del QueryBus

            # Solo procesar QueryHandlers
            if not issubclass(handler_class, QueryHandler):
                continue

            # Inspeccionar el método handle() para obtener el tipo del Query
            if hasattr(handler_class, 'handle'):
                handle_method = handler_class.handle
                query_type = None

                # Obtener las anotaciones de tipo del método handle()
                try:
                    # Intentar obtener type hints de la función directamente
                    type_hints = get_type_hints(handle_method, include_extras=True)
                    query_type = type_hints.get('query')

                    # Si no se encuentra, intentar con signature directamente
                    if not query_type:
                        signature = inspect.signature(handle_method)
                        query_param = signature.parameters.get('query')
                        if query_param and query_param.annotation != inspect.Parameter.empty:
                            query_type = query_param.annotation
                            # Si es un string (forward reference), intentar resolverlo
                            if isinstance(query_type, str):
                                # Buscar en el módulo del handler
                                module = handler_class.__module__
                                if module:
                                    try:
                                        import sys
                                        handler_module = sys.modules.get(module)
                                        if handler_module:
                                            query_type = getattr(handler_module, query_type, None)
                                    except (AttributeError, ImportError):
                                        pass

                    # Verificar que sea una clase válida
                    if query_type:
                        # Manejar casos donde query_type puede ser una string o una clase
                        if isinstance(query_type, str):
                            # Intentar importar el tipo desde el módulo del handler
                            try:
                                module = handler_class.__module__
                                if module:
                                    import sys
                                    handler_module = sys.modules.get(module)
                                    if handler_module:
                                        query_type = getattr(handler_module, query_type, None)
                            except (AttributeError, ImportError):
                                pass

                        # Verificar que sea una clase y subclase de Query
                        if query_type and isinstance(query_type, type):
                            try:
                                if issubclass(query_type, Query):
                                    self._handlers[query_type] = handler_instance
                                    print(f"[DEBUG] Auto-registrado: {query_type.__name__} -> {handler_name}")
                                    continue
                            except (TypeError, AttributeError):
                                pass

                    # Si llegamos aquí, no se pudo detectar el tipo Query
                    print(f"[WARNING] No se encontró tipo Query válido para {handler_name} (tipo detectado: {query_type})")

                except Exception as e:
                    print(f"[WARNING] No se pudo auto-registrar {handler_name}: {e}")

    def register(self, query_class, handler):
        """Registro manual (opcional, para casos especiales)"""
        self._handlers[query_class] = handler

    def ask(self, query: Query):
        handler = self._handlers.get(type(query))
        if not handler:
            raise Exception(f"Ask No handler for {type(query)}")
        return handler.handle(query)


# Instancia compartida del QueryBus (singleton pattern)
_shared_query_bus = QueryBus()


def get_query_bus() -> QueryBus:
    """Retorna la instancia compartida del QueryBus"""
    return _shared_query_bus
