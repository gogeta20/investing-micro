FROM certbot/certbot

# Se define una variable de entorno para evitar tener que pasarla en el comando (esto es opcional)
ENV DOMAIN=django-gcloud.duckdns.org
ENV EMAIL=tu-email@ejemplo.com

# Este ENTRYPOINT ejecuta Certbot para obtener un certificado usando el plugin webroot
ENTRYPOINT ["certbot", "certonly", "--non-interactive", "--agree-tos", "--webroot", "-w", "/var/www/html", "-d", "${DOMAIN}", "--email", "${EMAIL}"]
