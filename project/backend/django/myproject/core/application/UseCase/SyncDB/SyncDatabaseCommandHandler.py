from myproject.core.application.UseCase.SyncDB.SyncDatabase import SyncDatabase
from myproject.core.application.UseCase.SyncDB.SyncDatabaseCommand import SyncDatabaseCommand
from myproject.core.infrastructure.repository.mongo.mongo_repository import MongoRepository
from myproject.core.infrastructure.repository.mysql.mysql_service import MySQLService
from myproject.shared.domain.bus.command.command_handler import CommandHandler
from myproject.shared.domain.response import BaseResponse

class SyncDatabaseCommandHandler(CommandHandler):
    def __init__(self, use_case):
        self.use_case = use_case

    def handle(self, command: SyncDatabaseCommand):
        self.use_case.execute()

        return BaseResponse(
            data={"status": "OK"},
            message="success request",
            status=200
        ).to_dict()

    @classmethod
    def create(cls):
        mysql_service = MySQLService()
        mongo_service = MongoRepository()
        use_case = SyncDatabase(mysql_service, mongo_service)
        return cls(use_case)
