from django.http import JsonResponse
from myproject.stock.application.commands.post_stock_snapshot.PostStockSnapshotCommand import PostStockSnapshotCommand
from myproject.shared.infrastructure.bus.command_bus import get_command_bus
from myproject.shared.infrastructure.controller.api_controller import ApiController


class PostStockSnapshotController(ApiController):
    def __init__(self):
        cb = get_command_bus()
        super().__init__(command_bus=cb)

    def post(self, request):
        data = request.data
        command = PostStockSnapshotCommand(
            symbol=data.get("symbol"),
            price=data.get("price"),
            recorded_at=data.get("recorded_at"),
        )

        self.dispatch_command(command)

        return JsonResponse({"state": "success"})

    def register_exceptions(self) -> dict:
        return {
            ValueError: 400,
        }
