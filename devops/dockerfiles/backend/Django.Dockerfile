FROM python:3.10

RUN apt-get update && apt-get install -y \
    portaudio19-dev \
    python3-pyaudio \
    libasound2 \
    libasound2-plugins \
    alsa-utils \
    libsndfile1 \
    python3-dev \
    build-essential \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app
COPY project/backend/django/requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt
COPY project/backend/django/ .

RUN mkdir -p /root/.kaggle
COPY conf/django/kaggle/kaggle.json /root/.kaggle/kaggle.json
RUN chmod 600 /root/.kaggle/kaggle.json

EXPOSE 8000
CMD ["python", "manage.py", "runserver", "0.0.0.0:8000"]
