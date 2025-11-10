FROM python:3.10-slim
ARG MICRO_ENV
WORKDIR /app
COPY project/backend/fastapi/requirements.txt .

RUN if [ "$MICRO_ENV" = "local" ]; then \
  pip install --no-cache-dir pip-tools; \
  fi

RUN pip install --no-cache-dir -r requirements.txt
COPY project/backend/fastapi /app
CMD ["uvicorn", "app.main:app", "--host", "0.0.0.0", "--port", "8000"]
