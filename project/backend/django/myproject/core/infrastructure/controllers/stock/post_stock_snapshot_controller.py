from django.http import JsonResponse

from myproject.core.application.UseCase.StockSnapshot.PostStockSnapshotCommand import PostStockSnapshotCommand
from myproject.shared.application.handlers.handlers import get_handler
from myproject.shared.infrastructure.bus.command_bus import CommandBus
from myproject.shared.infrastructure.controller.api_controller import ApiController


class PostStockSnapshotController(ApiController):
    def __init__(self, command_bus: CommandBus = None):
        command_bus = command_bus or CommandBus()
        command_bus.register(PostStockSnapshotCommand, get_handler("PostStockSnapshotCommandHandler"))
        super().__init__(command_bus=command_bus)

    def post(self, request):
        data = request.data
        command = PostStockSnapshotCommand(
            symbol=data.get("symbol"),
            price=data.get("price"),
            recorded_at=data.get("recorded_at"),
        )

        self.dispatch_command(command)

        return JsonResponse({"state":"success"})

    def register_exceptions(self) -> dict:
        return {
            Exception: 500,
        }
