FROM python:3.10

# Instalar dependencias del sistema + zsh + vim
RUN apt-get update && apt-get install -y \
    portaudio19-dev \
    python3-pyaudio \
    libasound2 \
    libasound2-plugins \
    alsa-utils \
    libsndfile1 \
    python3-dev \
    build-essential \
    zsh \
    vim \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Instalar Oh My Zsh (opcional pero recomendado)
RUN sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)" "" --unattended

# Cambiar shell por defecto a zsh
RUN chsh -s $(which zsh)

# Configuración básica de vim (opcional)
RUN echo "syntax on" > /root/.vimrc && \
    echo "set number" >> /root/.vimrc && \
    echo "set tabstop=4" >> /root/.vimrc && \
    echo "set shiftwidth=4" >> /root/.vimrc && \
    echo "set expandtab" >> /root/.vimrc

WORKDIR /app

# Copiar requirements y agregar debugpy
COPY project/backend/django/requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt && \
    pip install --no-cache-dir debugpy

COPY project/backend/django/ .

# Configuración de Kaggle
RUN mkdir -p /root/.kaggle
COPY conf/django/kaggle/kaggle.json /root/.kaggle/kaggle.json
RUN chmod 600 /root/.kaggle/kaggle.json

# Configurar tema y plugins de Oh My Zsh
RUN sed -i 's/ZSH_THEME="robbyrussell"/ZSH_THEME="agnoster"/g' /root/.zshrc && \
    sed -i 's/plugins=(git)/plugins=(git docker docker-compose python pip)/g' /root/.zshrc

    
EXPOSE 8000
EXPOSE 5678

# Comando por defecto (puedes sobreescribirlo en docker-compose)
CMD ["python", "manage.py", "runserver", "0.0.0.0:8000"]
