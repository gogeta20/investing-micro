from fastapi import APIRouter
from app.application.handle_message import handle_message
from app.shared.models import ChatRequest, ChatResponse

router = APIRouter()

@router.post("/chat", response_model=ChatResponse)
async def chat(payload: ChatRequest):
    return await handle_message(payload)
