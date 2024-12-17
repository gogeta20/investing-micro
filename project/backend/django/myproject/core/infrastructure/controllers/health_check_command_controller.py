from django.http import JsonResponse

from myproject.shared.infrastructure.bus.command_bus import CommandBus
from myproject.core.application.UseCase.HealthCheck.health_check_command import HealthCheckCommand
from myproject.core.application.UseCase.HealthCheck.health_check_command_handler import HealthCheckCommandHandler
from myproject.shared.infrastructure.controller.api_controller import ApiController


class HealthCheckCommandController(ApiController):

    def __init__(self, query_bus=None, command_bus=None):
        command_bus = command_bus or CommandBus()
        command_bus.register(HealthCheckCommand, HealthCheckCommandHandler())
        super().__init__(query_bus=query_bus, command_bus=command_bus)

    def get(self, request):
        command = HealthCheckCommand()
        try:
            response = self.command_bus.dispatch_command(command)
            return JsonResponse(response, safe=False)
        except Exception as e:
            error_data, status_code = self._handle_exception(e)
            return JsonResponse(error_data, status=status_code)

    def register_exceptions(self) -> dict:
        return {
            Exception: 500,
        }
