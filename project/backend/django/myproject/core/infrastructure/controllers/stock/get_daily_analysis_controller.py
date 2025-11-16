from django.http import JsonResponse

from myproject.core.application.UseCase.DailyAnalysis.GetDailyAnalysisQuery import GetDailyAnalysisQuery
from myproject.shared.application.handlers.handlers import get_handler
from myproject.shared.infrastructure.bus.query_bus import QueryBus
from myproject.shared.infrastructure.controller.api_controller import ApiController

class GetDailyAnalysisController(ApiController):
    def __init__(self, query_bus: QueryBus = None):
        query_bus = query_bus or QueryBus()
        query_bus.register(GetDailyAnalysisQuery, get_handler("GetDailyAnalysisQueryHandler"))
        super().__init__(query_bus=query_bus)

    def get(self, request):
        query = GetDailyAnalysisQuery()

        try:
            response = self.ask(query)
            return JsonResponse({"data": response}, safe=False)
        except Exception as e:
            error_data, status_code = self._handle_exception(e)
            return JsonResponse(error_data, status=status_code)

    def register_exceptions(self) -> dict:
        return {
            Exception: 500,
        }
