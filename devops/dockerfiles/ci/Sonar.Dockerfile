# FROM sonarqube:latest
# EXPOSE 9100
# WORKDIR /opt/sonarqube

FROM sonarqube:9.9.3-community
EXPOSE 9000
WORKDIR /opt/sonarqube
