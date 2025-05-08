from fastapi import FastAPI
from app.api.chat import router as chat_router
from prometheus_fastapi_instrumentator import Instrumentator

app = FastAPI(title="Chatbot V2")

app.include_router(chat_router, prefix="/api", tags=["Chat"])


instrumentator = Instrumentator()
instrumentator.instrument(app).expose(app)
