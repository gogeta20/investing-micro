FROM sonarqube:latest

# Exponer los puertos predeterminados de SonarQube
EXPOSE 9100
# Directorio de trabajo
WORKDIR /opt/sonarqube
