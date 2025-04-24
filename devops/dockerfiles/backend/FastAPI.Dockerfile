FROM python:3.10-slim

WORKDIR /app

COPY project/backend/fastapi/requirements.txt .

RUN pip install --no-cache-dir -r requirements.txt

COPY project/backend/fastapi /app

CMD ["uvicorn", "app.main:app", "--host", "0.0.0.0", "--port", "8000"]
