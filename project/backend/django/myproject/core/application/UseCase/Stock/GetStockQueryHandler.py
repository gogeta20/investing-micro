import os
from django.conf import settings

from myproject.core.application.UseCase.Stock.GetStock import GetStock
from myproject.core.application.UseCase.Stock.GetStockQuery import GetStockQuery
from myproject.core.application.UseCase.Voice.Voice import Voice
from myproject.core.application.UseCase.Voice.VoiceQuery import VoiceQuery
from myproject.core.infrastructure.repository.mysql.mysql_repository import PokemonRepository
from myproject.core.infrastructure.repository.mysql.mysql_service import MySQLService
from myproject.shared.domain.bus.query.query_handler import QueryHandler
from myproject.shared.domain.response import BaseResponse
from myproject.shared.infrastructure.services.voice.VoiceMLService import VoiceMLService

class GetStockQueryHandler(QueryHandler):
    def __init__(self, use_case):
        self.use_case = use_case

    def handle(self, query: GetStockQuery):
        result = self.use_case.execute()
        return BaseResponse(
            data={
                "result": result
            },
            message="success request",
            status=200
        ).to_dict()

    @classmethod
    def create(cls):
        mysql_service = MySQLService()
        use_case = GetStock(mysql_service)
        return cls(use_case)
