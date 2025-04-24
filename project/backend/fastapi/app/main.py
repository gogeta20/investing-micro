from fastapi import FastAPI
from fastapi.responses import JSONResponse

app = FastAPI(title="FastAPI Service", version="0.1")

@app.get("/health", tags=["Status"])
def health_check():
    return JSONResponse(content={"status": "ok"})
