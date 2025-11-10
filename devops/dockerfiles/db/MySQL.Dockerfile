# MySQL.Dockerfile

FROM mysql:8.0
# FROM mariadb:11.3
# FROM bitnami/mariadb:10.11

# Copiar un archivo de configuración de MySQL si es necesario
COPY ./conf/mysql/my.cnf /etc/mysql/my.cnf
