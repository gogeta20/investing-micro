from myproject.core.application.UseCase.Voice.VoiceQuery import VoiceQuery
from myproject.shared.domain.bus.query.query_handler import QueryHandler
from myproject.shared.domain.response import BaseResponse

class VoiceQueryHandler(QueryHandler):
    def handle(self, query: VoiceQuery):
        return BaseResponse(
            data={"status": "OK"},
            message=query.get_text(),
            status=200
        ).to_dict()
