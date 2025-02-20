FROM sonarqube:latest

# Exponer los puertos predeterminados de SonarQube
EXPOSE 9000 9092

# Directorio de trabajo
WORKDIR /opt/sonarqube
