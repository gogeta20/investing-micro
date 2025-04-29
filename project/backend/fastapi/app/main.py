from fastapi import FastAPI
from app.api.chat import router as chat_router

app = FastAPI(title="Chatbot V2")

app.include_router(chat_router, prefix="/api", tags=["Chat"])

