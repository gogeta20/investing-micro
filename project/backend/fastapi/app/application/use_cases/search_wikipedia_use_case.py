# app/application/use_cases/search_wikipedia_use_case.py

from app.shared.models import ChatResponse
from app.infrastructure.services.wikipedia_search_service import WikipediaSearchService


async def search_wikipedia_use_case(message: str) -> ChatResponse:
    """
    Caso de uso para buscar un resumen en Wikipedia sobre el mensaje dado.
    """
    summary = await WikipediaSearchService.search_summary(message)

    return ChatResponse(
        intent="buscar_informacion",
        confidence=0.99,
        response=summary
    )
