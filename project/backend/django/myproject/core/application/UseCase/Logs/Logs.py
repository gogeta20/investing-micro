import docker
from django.http import JsonResponse

from myproject.core.application.UseCase.Logs.LogsQuery import  LogsQuery

class Logs:
    def __init__(self):
        self.client = docker.from_env()

    def execute(self, query : LogsQuery):
        try:
            container = self.client.containers.get(query.get_text())
            logs = container.logs(tail=10).decode("utf-8")
            return logs
            # return logs.replace('\"', '')
        except Exception as e:
            return str(e)
