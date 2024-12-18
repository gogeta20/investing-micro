from myproject.shared.domain.bus.query.query_handler import QueryHandler
from myproject.shared.domain.response import BaseResponse
from myproject.core.application.UseCase.HealthCheck.health_check_query import HealthCheckQuery

class HealthCheckQueryHandler(QueryHandler):
    def handle(self, query: HealthCheckQuery):
        return BaseResponse(
            data={"status": "OK"},
            message="Health check successful",
            status=200
        ).to_dict()
