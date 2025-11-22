from myproject.stock.application.commands.post_stock_snapshot.PostStockSnapshotCommand import PostStockSnapshotCommand
from myproject.stock.application.commands.post_stock_snapshot.PostStockSnapshot import PostStockSnapshot
from myproject.shared.domain.bus.command.command_handler import CommandHandler
from myproject.shared.domain.response import BaseResponse


class PostStockSnapshotCommandHandler(CommandHandler):
    def __init__(self, use_case: PostStockSnapshot):
        self.use_case = use_case

    def handle(self, command: PostStockSnapshotCommand):
        self.use_case.execute(command)

        return BaseResponse(
            data={"status": "OK"},
            message="success request",
            status=200
        ).to_dict()

    @classmethod
    def create(cls):
        use_case = PostStockSnapshot()
        return cls(use_case)
