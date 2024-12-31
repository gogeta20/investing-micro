
COMPOSE=docker compose
VF=front_micro
S=symfony_backend
SB=springboot_backend
DB=django_backend
MYSQL=mysql_db
M=mongo_db
NG=nginx_proxy
MQ=rabbitmq
J=jenkins_micro

include devops/mk/*.mk

build:
	docker compose build

build-d:
	docker compose up --build -d

build--no-cache:
	docker compose build --no-cache

up:
	docker compose up -d

down:
	docker compose down

down-volume:
	docker compose down -v

restart: down up

#--------------------- FRONT vue - front_micro --------------------######################################

in-front:
	docker exec -it $(VF) sh

logs-front:
	docker logs $(VF)

#--------------------- BACK - symfony_backend --------------------######################################

in-back-symfony:
	docker exec -it $(S) /bin/bash

logs-back-symfony:
	docker logs $(S)

#--------------------- BACK - django_backend python --------------------######################################

in-back-django:
	docker exec -it $(DB) /bin/bash

logs-back-django:
	docker logs $(DB)

py-require:
	docker exec -it $(DB) bash -c ""

#--------------------- BACK - springboot_backend java --------------------######################################

in-back-sprintboot:
	docker exec -it $(SB) /bin/bash

logs-springboot:
	docker logs  $(SB)

#--------------------- PROXY nginx - nginx_proxy --------------------######################################

in-nginx:
	docker exec -it $(NG) /bin/bash


backend-on-rabbit:
	docker exec -it $(SB) bash -c "php bin/console messenger:consume events -vvv"

proxy-nginx-logs:
	docker logs $(NG)

#--------------------- JENKINS - jenkins_micro --------------------######################################

pass-init-jenkins:
	docker exec -it $(J) cat /var/jenkins_home/secrets/initialAdminPassword

#--------------------- DBase - mysql_db_micro mysql --------------------######################################

in-db:
	docker exec -it  $(MYSQL) /bin/bash

in-db-mysql:
	docker exec -it $(MYSQL) mysql -u user -ppassword

in-db-mysql-root:
	docker exec -it $(MYSQL) mysql -u root -prootpassword

#--------------------- DBase - mongo_db - mongo --------------------######################################

in-mongo:
	docker exec -it $(M) /bin/bash

in-db-mongo:
	docker exec -it $(M) mongosh -u root -p password

logs-mongo:
	docker logs $(M)

#--------------------- rabbitmq --------------------######################################
rabbit-enable-rabbitmq-management:
	docker exec -it $(MQ) rabbitmq-plugins enable rabbitmq_management

rabbit-disable-rabbitmq-management:
	docker exec -it $(MQ) rabbitmq-plugins disable rabbitmq_management



