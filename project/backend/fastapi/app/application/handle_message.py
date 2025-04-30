from app.shared.models import ChatRequest, ChatResponse
from app.application.use_cases.greet_user import greet_user_use_case
from app.infrastructure.ml.intent_classifier import IntentClassifierService
from app.application.use_cases.search_wikipedia_use_case import search_wikipedia_use_case

classifier = IntentClassifierService()

async def handle_message(payload: ChatRequest) -> ChatResponse:
    intent, confidence = classifier.predict(payload.message)

    confidence_threshold = 0.6
    if confidence < confidence_threshold:
        return ChatResponse(
            intent="desconocido",
            confidence=confidence,
            response="Lo siento, no entendí tu mensaje."
        )

    if intent == "saludo":
        return greet_user_use_case()

    if intent == "buscar_informacion":
        return await search_wikipedia_use_case(payload.message)

    return ChatResponse(
        intent="unknown",
        confidence=confidence,
        response="Lo siento, no encontré una respuesta adecuada."
    )
