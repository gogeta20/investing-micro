"""
QueryBus - Sistema de auto-registro de Query Handlers

Este módulo implementa un bus de queries que automáticamente registra los handlers
descubriendo qué tipo de Query maneja cada handler mediante inspección de tipos.

Funcionamiento:
1. Al inicializarse, busca todos los handlers en las carpetas configuradas
2. Para cada handler, inspecciona el método handle() para obtener el tipo de Query
3. Registra automáticamente la relación Query -> Handler
4. Cuando se ejecuta una query, el bus encuentra el handler correspondiente

Mejoras implementadas:
- Usa el contexto del módulo (globals) para resolver correctamente las anotaciones de tipo
- Resuelve forward references (strings) usando el módulo del handler
- Maneja errores gracefully sin romper el sistema
"""
import inspect
from typing import get_type_hints
from myproject.shared.domain.bus.query.query import Query
from myproject.shared.domain.bus.query.query_handler import QueryHandler
from myproject.shared.domain.bus.command.command_handler import CommandHandler
from myproject.shared.application.handlers.handler_loader import load_handlers


class QueryBus:
    """
    Bus de queries que maneja el enrutamiento automático de queries a sus handlers.

    El bus se auto-inicializa al crearse y registra automáticamente todos los handlers
    encontrados en las carpetas configuradas en handler_loader.
    """

    def __init__(self, auto_register: bool = True):
        self._handlers = {}
        if auto_register:
            self._auto_register_handlers()

    def _auto_register_handlers(self):
        """
        Auto-registra todos los handlers descubriendo qué Query manejan.

        Proceso:
        1. Carga todos los handlers usando handler_loader
        2. Para cada QueryHandler, inspecciona el método handle()
        3. Obtiene el tipo de Query desde las anotaciones de tipo
        4. Registra la relación Query -> Handler
        """
        handlers_dict = load_handlers()
        for handler_name, handler_instance in handlers_dict.items():
            handler_class = handler_instance.__class__

            # Filtrar CommandHandlers - no son responsabilidad del QueryBus
            if issubclass(handler_class, CommandHandler):
                continue

            # Solo procesar QueryHandlers
            if not issubclass(handler_class, QueryHandler):
                continue

            # Inspeccionar el método handle() para obtener el tipo del Query
            if not hasattr(handler_class, 'handle'):
                continue

            try:
                query_type = self._extract_query_type(handler_class, handler_name)

                if query_type and isinstance(query_type, type):
                    try:
                        if issubclass(query_type, Query):
                            self._handlers[query_type] = handler_instance
                            print(f"[DEBUG] Auto-registrado: {query_type.__name__} -> {handler_name}")
                    except (TypeError, AttributeError) as e:
                        print(f"[DEBUG] Error verificando subclase para {handler_name}: {e}")
                else:
                    print(f"[WARNING] No se encontró tipo Query válido para {handler_name} (tipo detectado: {query_type})")

            except Exception as e:
                print(f"[WARNING] No se pudo auto-registrar {handler_name}: {e}")

    def _extract_query_type(self, handler_class, handler_name):
        """
        Extrae el tipo de Query desde las anotaciones del método handle().

        Intenta múltiples estrategias:
        1. get_type_hints con contexto del módulo (mejor resolución)
        2. inspect.signature como fallback
        3. Resolución de forward references (strings) usando el módulo

        Args:
            handler_class: La clase del handler
            handler_name: Nombre del handler para logging

        Returns:
            El tipo de Query o None si no se pudo determinar
        """
        handle_method = handler_class.handle

        # Obtener el módulo del handler para usar como contexto
        handler_module = None
        try:
            import sys
            module_name = handler_class.__module__
            handler_module = sys.modules.get(module_name)
        except:
            pass

        query_type = None

        # Estrategia 1: get_type_hints con contexto del módulo (mejor resolución)
        try:
            if handler_module:
                type_hints = get_type_hints(handle_method, globals=handler_module.__dict__, include_extras=True)
            else:
                type_hints = get_type_hints(handle_method, include_extras=True)
            query_type = type_hints.get('query')
        except:
            pass

        # Estrategia 2: inspect.signature como fallback
        if not query_type:
            try:
                signature = inspect.signature(handle_method)
                query_param = signature.parameters.get('query')
                if query_param and query_param.annotation != inspect.Parameter.empty:
                    query_type = query_param.annotation
            except:
                pass

        # Estrategia 3: Resolver forward references (strings)
        if isinstance(query_type, str) and handler_module:
            try:
                resolved = getattr(handler_module, query_type, None)
                if resolved and isinstance(resolved, type):
                    query_type = resolved
            except:
                pass

        return query_type

    def register(self, query_class, handler):
        """
        Registro manual de un handler (opcional, para casos especiales).

        Args:
            query_class: La clase de Query
            handler: La instancia del handler
        """
        self._handlers[query_class] = handler

    def ask(self, query: Query):
        """
        Ejecuta una query encontrando y llamando al handler correspondiente.

        Args:
            query: La instancia de Query a ejecutar

        Returns:
            El resultado del handler

        Raises:
            Exception: Si no se encuentra un handler para la query
        """
        handler = self._handlers.get(type(query))
        if not handler:
            raise Exception(f"Ask No handler for {type(query)}")
        return handler.handle(query)


# Instancia compartida del QueryBus (singleton pattern)
_shared_query_bus = QueryBus()


def get_query_bus() -> QueryBus:
    """Retorna la instancia compartida del QueryBus"""
    return _shared_query_bus
