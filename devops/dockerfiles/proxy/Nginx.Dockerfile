FROM nginx:latest

ARG MICRO_ENV=production
ENV MICRO_ENV=${MICRO_ENV}

COPY conf/nginx /tmp/nginx

RUN if [ "$MICRO_ENV" = "local" ]; then \
    cp /tmp/nginx/local/symfony.conf /etc/nginx/conf.d/; \
    cp /tmp/nginx/local/vue.conf /etc/nginx/conf.d/; \
    cp /tmp/nginx/local/django.conf /etc/nginx/conf.d/; \
  else \
    cp /tmp/nginx/symfony.conf /etc/nginx/conf.d/; \
    cp /tmp/nginx/django.conf /etc/nginx/conf.d/; \
  fi

RUN rm /etc/nginx/conf.d/default.conf
EXPOSE 80
EXPOSE 443
ENTRYPOINT ["/usr/sbin/nginx"]
CMD ["-g", "daemon off;"]


# Copiar el archivo de configuración de Nginx al contenedor
#COPY ./conf/nginx/nginx.conf /etc/nginx/nginx.conf
#COPY ./conf/nginx/sites-available/ /etc/nginx/conf.d/
