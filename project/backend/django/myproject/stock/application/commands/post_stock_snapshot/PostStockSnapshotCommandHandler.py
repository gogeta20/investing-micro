from myproject.stock.application.commands.post_stock_snapshot.PostStockSnapshotCommand import PostStockSnapshotCommand
from myproject.stock.application.commands.post_stock_snapshot.PostStockSnapshot import PostStockSnapshot
from myproject.core.infrastructure.repository.mysql.mysql_service import MySQLService
from myproject.shared.domain.bus.command.command_handler import CommandHandler
from myproject.shared.domain.response import BaseResponse


class PostStockSnapshotCommandHandler(CommandHandler):
    def __init__(self, use_case):
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
        mysql_service = MySQLService()
        use_case = PostStockSnapshot(mysql_service)
        return cls(use_case)
