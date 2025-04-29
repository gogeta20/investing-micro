from app.shared.models import ChatRequest, ChatResponse
from app.application.use_cases.greet_user import greet_user_use_case
from app.infrastructure.ml.intent_classifier import IntentClassifierService

classifier = IntentClassifierService()

async def handle_message(payload: ChatRequest) -> ChatResponse:
    intent, confidence = classifier.predict(payload.message)

    if confidence < 0.7:
        return ChatResponse(
            intent="desconocido",
            confidence=confidence,
            response="Lo siento, no entendí tu mensaje."
        )

    if intent == "saludo":
        return greet_user_use_case()

    return ChatResponse(
        intent=intent,
        confidence=confidence,
        response="Caso no implementado aún."
    )
