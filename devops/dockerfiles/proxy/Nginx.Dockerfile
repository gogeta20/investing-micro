FROM nginx:latest

ARG ENVIRONMENT=production
ENV ENVIRONMENT=${ENVIRONMENT}

COPY conf/nginx /tmp/nginx

# Copiar los archivos correctos según el entorno
RUN if [ "$ENVIRONMENT" = "local" ]; then \
    cp /tmp/nginx/local/symfony.conf /etc/nginx/conf.d/; \
    cp /tmp/nginx/local/django.conf /etc/nginx/conf.d/; \
  else \
    cp /tmp/nginx/prod/symfony.conf /etc/nginx/conf.d/; \
    cp /tmp/nginx/prod/django.conf /etc/nginx/conf.d/; \
  fi

# COPY conf/nginx/symfony.conf /etc/nginx/conf.d/
# COPY conf/nginx/vue.conf /etc/nginx/conf.d/
# COPY conf/nginx/django.conf /etc/nginx/conf.d/


# COPY ../../../conf/nginx/symfony.conf /etc/nginx/conf.d/
# COPY ../../../conf/nginx/vue.conf /etc/nginx/conf.d/
# COPY ../../../conf/nginx/django.conf /etc/nginx/conf.d/

RUN rm /etc/nginx/conf.d/default.conf
EXPOSE 80
EXPOSE 443
ENTRYPOINT ["/usr/sbin/nginx"]
CMD ["-g", "daemon off;"]


# Copiar el archivo de configuración de Nginx al contenedor
#COPY ./conf/nginx/nginx.conf /etc/nginx/nginx.conf
#COPY ./conf/nginx/sites-available/ /etc/nginx/conf.d/
