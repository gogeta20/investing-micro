from django.http import JsonResponse

from myproject.core.application.UseCase.SyncDB.SyncDatabaseCommand import SyncDatabaseCommand
from myproject.shared.application.handlers.handlers import get_handler
from myproject.shared.infrastructure.bus.command_bus import CommandBus
from myproject.shared.infrastructure.controller.api_controller import ApiController


class SyncDatabaseController(ApiController):
    def __init__(self, command_bus: CommandBus = None):
        command_bus = command_bus or CommandBus()
        command_bus.register(SyncDatabaseCommand, get_handler("SyncDatabaseCommandHandler"))
        super().__init__(command_bus=command_bus)

    def post(self, request):
        command = SyncDatabaseCommand()
        self.dispatch_command(command)

        return JsonResponse({"state":"success"})

    def register_exceptions(self) -> dict:
        return {
            Exception: 500,
        }
