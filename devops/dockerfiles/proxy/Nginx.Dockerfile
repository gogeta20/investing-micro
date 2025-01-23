FROM nginx:latest
COPY ../../../conf/nginx/symfony.conf /etc/nginx/conf.d/
COPY ../../../conf/nginx/vue.conf /etc/nginx/conf.d/
COPY ../../../conf/nginx/django.conf /etc/nginx/conf.d/

RUN rm /etc/nginx/conf.d/default.conf
EXPOSE 80
EXPOSE 443
ENTRYPOINT ["/usr/sbin/nginx"]
CMD ["-g", "daemon off;"]


# Copiar el archivo de configuración de Nginx al contenedor
#COPY ./conf/nginx/nginx.conf /etc/nginx/nginx.conf
#COPY ./conf/nginx/sites-available/ /etc/nginx/conf.d/
