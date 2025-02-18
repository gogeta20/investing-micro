# Usamos la imagen oficial de Jenkins LTS basada en Debian 12 (Bookworm)
FROM jenkins/jenkins:lts

# Cambiamos al usuario root para instalar paquetes
USER root

# Eliminamos versiones antiguas de Docker si existen
RUN apt-get remove -y docker docker-engine docker.io containerd runc || true

# Instalamos dependencias necesarias
RUN apt-get update && apt-get install -y \
    ca-certificates \
    curl \
    gnupg \
    unzip \
    lsb-release

# Creamos el directorio de claves GPG y descargamos la clave oficial de Docker
RUN mkdir -p /etc/apt/keyrings
RUN curl -fsSL https://download.docker.com/linux/debian/gpg | tee /etc/apt/keyrings/docker.asc > /dev/null
RUN chmod a+r /etc/apt/keyrings/docker.asc

# Agregamos el repositorio oficial de Docker para Debian 12 (Bookworm)
RUN echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/debian bookworm stable" \
    | tee /etc/apt/sources.list.d/docker.list > /dev/null

# Actualizamos e instalamos la última versión estable de Docker y Docker Compose
RUN apt-get update && apt-get install -y \
    docker-ce \
    docker-ce-cli \
    containerd.io \
    docker-buildx-plugin \
    docker-compose-plugin

# Agregamos el usuario Jenkins al grupo Docker para que pueda ejecutarlo
RUN usermod -aG docker jenkins

# Limpiamos archivos innecesarios
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Volvemos al usuario Jenkins
USER jenkins
