FROM mongo:5.0
COPY ./conf/mongodb/mongo-init.js /docker-entrypoint-initdb.d/
