from django.http import JsonResponse
from myproject.core.application.UseCase.DailyAnalysis.GetDailyAnalysisQuery import GetDailyAnalysisQuery
from myproject.shared.infrastructure.bus.query_bus import get_query_bus
from myproject.shared.infrastructure.controller.api_controller import ApiController


class GetDailyAnalysisController(ApiController):
    def __init__(self):
        qb = get_query_bus()
        super().__init__(query_bus=qb)

    def get(self, request):
        query = GetDailyAnalysisQuery()
        response = self.ask(query)
        return JsonResponse({"data": response}, safe=False)

    def register_exceptions(self) -> dict:
        return {
            ValueError: 400,
        }
