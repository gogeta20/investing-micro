from abc import ABC, abstractmethod
from pprint import pprint

from django.http import JsonResponse
from rest_framework.views import APIView
from myproject.shared.domain.bus.query.query import Query
from myproject.shared.infrastructure.bus.command_bus import CommandBus, get_command_bus
from myproject.shared.infrastructure.bus.query_bus import QueryBus, get_query_bus
from myproject.shared.domain.bus.command.command import Command


class ApiController(APIView, ABC):
    def __init__(self, query_bus: QueryBus = None, command_bus: CommandBus = None):
        super().__init__()
        # Usa la instancia compartida si no se proporciona una
        self.query_bus = query_bus if query_bus is not None else get_query_bus()
        self.command_bus = command_bus if command_bus is not None else get_command_bus()
        self.register_exceptions()

    @abstractmethod
    def register_exceptions(self) -> dict:
        """Define los mappings de excepciones a códigos HTTP"""
        pass

    def ask(self, query: Query):
        try:
            return self.query_bus.ask(query)
        except Exception as e:
            return self._handle_exception(e)

    def dispatch_command(self, command: Command):
        try:
            return self.command_bus.dispatch(command)
        except Exception as e:
            return self._handle_exception(e)

    def _handle_exception(self, exception: Exception):
        print(f"[DEBUG] {str(exception)}")
        exception_mappings = self.register_exceptions()
        status_code = exception_mappings.get(type(exception), 500)
        error_data = {
            "error": str(exception)
        }
        return JsonResponse(error_data, status=status_code)
