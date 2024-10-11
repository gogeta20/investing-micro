
# Variable para el directorio del proyecto
SPRINGBOOT_DIR=project/backend/springboot
SB=symfony_backend
NG=nginx_proxy
MQ=rabbitmq

build-d:
	docker compose up --build -d

up:
	docker compose up -d

down:
	docker compose down

restart: down up

ps:
	docker compose ps

logs:
	docker compose logs $(c)

init-volumes:
	mkdir -p devops/volumes/mysql devops/volumes/postgres devops/volumes/jenkins

clean-volumes:
	docker volume rm $$(docker volume ls -q)

build:
	docker compose build

#--------------------- FRONT --------------------######################################
front-in:
	docker exec -it front_micro sh

front-logs:
	docker logs front_micro

#--------------------- BACK --------------------######################################
back-symfony-in:
	docker exec -it $(SB) /bin/bash

back-symfony-logs:
	docker logs $(SB)

# make composer-require pp=guzzlehttp/guzzle
composer-require:
	docker exec -it $(SB) bash -c "composer require $(pp)"

backend-on-rabbit:
	docker exec -it $(SB) bash -c "php bin/console messenger:consume events -vvv"

#--------------------- nginx  --------------------######################################
proxy-nginx-logs:
	docker logs $(NG)

#--------------------- rabbitmq --------------------######################################
rabbit-enable-rabbitmq-management:
	docker exec -it $(MQ) rabbitmq-plugins enable rabbitmq_management

rabbit-disable-rabbitmq-management:
	docker exec -it $(MQ) rabbitmq-plugins disable rabbitmq_management



