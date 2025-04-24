from app.shared.models import ChatRequest, ChatResponse

async def handle_message(payload: ChatRequest) -> ChatResponse:
    # Aquí iría el clasificador, por ahora devuelve un ejemplo
    return ChatResponse(
        intent="saludo",
        confidence=0.98,
        response="¡Hola! ¿En qué puedo ayudarte?"
    )
