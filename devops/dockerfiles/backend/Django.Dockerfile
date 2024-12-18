FROM python:3.10

RUN apt-get update && apt-get install -y \
    portaudio19-dev \
    python3-pyaudio \
    libasound2 \
    libasound2-plugins \
    alsa-utils \
    libsndfile1 \
    && rm -rf /var/lib/apt/lists/*

RUN mkdir -p /etc/alsa
RUN echo "pcm.!default {type hw card 0}" > /etc/asound.conf
RUN echo "ctl.!default {type hw card 0}" >> /etc/asound.conf

WORKDIR /app
COPY project/backend/django/requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt
COPY project/backend/django/ .
EXPOSE 8000
CMD ["python", "manage.py", "runserver", "0.0.0.0:8000"]
