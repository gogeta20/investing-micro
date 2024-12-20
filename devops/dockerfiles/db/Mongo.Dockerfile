FROM mongo:5.0
COPY ./conf/mongodb/db/files/scripts/querys/mongo-init.js /docker-entrypoint-initdb.d/
