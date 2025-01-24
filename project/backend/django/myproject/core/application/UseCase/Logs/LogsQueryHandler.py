import os
from django.conf import settings

from myproject.core.application.UseCase.Logs.Logs import Logs
from myproject.core.application.UseCase.Logs.LogsQuery import LogsQuery
from myproject.shared.domain.bus.query.query_handler import QueryHandler
from myproject.shared.domain.response import BaseResponse

class LogsQueryHandler(QueryHandler):
    def __init__(self, use_case):
        self.use_case = use_case

    def handle(self, query: LogsQuery):

        result = self.use_case.execute(query)

        return BaseResponse(
            data= { "container" : result},
            message="success request",
            status=200
        ).to_dict()

    @classmethod
    def create(cls):
        use_case = Logs()
        return cls(use_case)
