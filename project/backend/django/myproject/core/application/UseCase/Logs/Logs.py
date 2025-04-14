import docker
from django.http import JsonResponse

from myproject.core.application.UseCase.Logs.LogsQuery import  LogsQuery

class Logs:
    def __init__(self):
        try:
            self.client = docker.from_env()
        except Exception as e:
            print(f"[Logs] Error inicializando Docker: {e}")
            self.client = None  # o un mock, o lanzás una excepción controlada

    def execute(self, query: LogsQuery):
        if not self.client:
            return "Docker no disponible en este entorno"
        try:
            container = self.client.containers.get(query.get_text())
            logs = container.logs(tail=10).decode("utf-8")
            return logs
        except Exception as e:
            return str(e)
#
#
#
# class Logs:
#     def __init__(self):
#         self.client = docker.from_env()
#
#     def execute(self, query : LogsQuery):
#         try:
#             container = self.client.containers.get(query.get_text())
#             logs = container.logs(tail=10).decode("utf-8")
#             return logs
#             # return logs.replace('\"', '')
#         except Exception as e:
#             return str(e)
