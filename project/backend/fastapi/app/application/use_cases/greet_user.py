from app.shared.models import ChatResponse

def greet_user_use_case() -> ChatResponse:
    return ChatResponse(
        intent="saludo",
        confidence=0.99,
        response="¡Hola! ¿En qué puedo ayudarte?"
    )
