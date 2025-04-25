FROM python:3.10-slim

WORKDIR /app

# Instala dependencias
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copia solo lo necesario
COPY app app/
COPY model model/   # Ya entrenado
COPY app/config.py app/config.py

CMD ["uvicorn", "app.main:app", "--host", "0.0.0.0", "--port", "8000"]
