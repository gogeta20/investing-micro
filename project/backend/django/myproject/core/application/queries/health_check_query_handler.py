from myproject.shared.domain.bus.query.query_handler import QueryHandler

class HealthCheckQueryHandler(QueryHandler):
    def handle(self, query):
        return {"status": "OK"}
