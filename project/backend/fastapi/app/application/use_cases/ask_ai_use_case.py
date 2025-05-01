import os
import openai
from app.shared.models import ChatResponse

openai.api_key = os.getenv("OPENAI_API_KEY")

class AskAIUseCase:
    @staticmethod
    async def execute(message: str) -> ChatResponse:
        try:

            client = openai.OpenAI(api_key=os.getenv("OPENAI_API_KEY"))

            response = client.chat.completions.create(
                model="gpt-3.5-turbo",
                messages=[
                    {"role": "user", "content": "¿Qué modelo eres?"}
                ]
            )

            # response = openai.ChatCompletion.create(
            # response =  client.chat.completions.create(
            #     model="gpt-3.5-turbo",
            #     messages=[
            #         {"role": "system", "content": "Responde de forma clara, concisa y útil como un asistente."},
            #         {"role": "user", "content": message}
            #     ]
            # )
            text = response.choices[0].message["content"]
            return ChatResponse(
                intent="respuesta_ia",
                confidence=0.5,  # porque no viene de un clasificador real
                response=text.strip()
            )
        except Exception as e:
            return ChatResponse(
                intent="error_ia",
                confidence=0.0,
                response=f"No se pudo obtener una respuesta de la IA: {str(e)}"
            )
