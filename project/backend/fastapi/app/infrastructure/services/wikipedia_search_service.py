# app/infra/services/wikipedia_search_service.py

import httpx


class WikipediaSearchService:
    BASE_URL = "https://es.wikipedia.org/api/rest_v1/page/summary/"

    @staticmethod
    async def search_summary(topic: str) -> str:
        topic_formatted = topic.strip().replace(" ", "_")
        url = f"{WikipediaSearchService.BASE_URL}{topic_formatted}"

        async with httpx.AsyncClient(follow_redirects=True) as client:
            response = await client.get(url, timeout=5.0)

        if response.status_code == 200:
            try:
                data = response.json()
                return data.get("extract", "No se encontró información.")
            except Exception:
                return "La respuesta no contenía datos válidos."
        else:
            return "No se encontró información sobre ese tema."

