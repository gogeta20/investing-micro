# Jenkins.Dockerfile

FROM jenkins/jenkins:lts

# Instalar Docker
USER root
RUN apt-get update && \
    apt-get install -y docker.io && \
    usermod -aG docker jenkins

# Devolver el control al usuario Jenkins
USER jenkins

