
# Variable para el directorio del proyecto
COMPOSE=docker compose
SPRINGBOOT_DIR=project/backend/springboot
SB=symfony_backend
NG=nginx_proxy
VF=front_micro
J=jenkins_micro
SB=springboot_backend
MYSQL=mysql_db

include devops/mk/*.mk

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

delete-images: #Eliminar Imágenes "Dangling" (Sin Nombre ni Etiqueta)
	docker image prune -f

delete-images-off: #Eliminar Contenedores Parados
	docker container prune -f

delete-volumes:
	docker volume prune -f

delete-networks:
	docker network prune -f

delete-no-use:
	docker system prune -f

delete-system-all:
	docker system prune -a --volumes -f

clean-docker:
	@echo "Eliminando contenedores parados..."
	docker container prune -f
	@echo "Eliminando imágenes dangling..."
	docker image prune -f
	@echo "Eliminando volúmenes no utilizados..."
	docker volume prune -f
	@echo "Eliminando redes no utilizadas..."
	docker network prune -f

clean-micro:
	@echo "Eliminando contenedores del proyecto micro..."
	docker ps -a --filter "name=micro" -q | xargs -r docker rm -f
	@echo "Eliminando imágenes del proyecto micro..."
	docker images --filter=reference="micro*" -q | xargs -r docker rmi -f
	@echo "Eliminando volúmenes del proyecto micro..."
	docker volume ls --filter "name=micro" -q | xargs -r docker volume rm
	@echo "Eliminando redes del proyecto micro..."
	docker network ls --filter "name=app_network" -q | xargs -r docker network rm


build:
	docker compose build

build--no-cache:
	docker compose build --no-cache

view-network:
	docker network inspect app_network

setup-git-hooks:
	@echo "🔧 Configurando hooks compartidos en .githooks..."
	git config core.hooksPath .githooks
	chmod +x .githooks/pre-push
	@echo "✅ Hooks compartidos configurados correctamente."

#--------------------- FRONT vue - front_micro --------------------######################################
in-front:
	docker exec -it $(VF) sh
# make composer-require pkg=guzzlehttp/guzzle

logs-front:
	docker logs $(VF)

#--------------------- BACK - symfony_backend --------------------######################################
in-back-symfony:
	docker exec -it $(SB) /bin/bash

logs-back-symfony:
	docker logs $(SB)

composer-require:
	docker exec -it $(SB) bash -c "composer require $(pkg)"

#--------------------- PROXY nginx - nginx_proxy --------------------######################################
proxy-nginx-logs:
	docker logs $(NG)

in-nginx:
	docker exec -it $(NG) /bin/bash

verify-nginx-conf:
	docker exec $(NG) nginx -t

reload-nginx:
	docker exec $(NG) nginx -s reload

#--------------------- JENKINS - jenkins_micro --------------------######################################
pass-init-jenkins:
	docker exec -it $(J) cat /var/jenkins_home/secrets/initialAdminPassword

#--------------------- BACK - springboot_backendspringboot_backend --------------------######################################

springboot-down:
	$(COMPOSE) stop  $(SB) && $(COMPOSE) rm -f  $(SB)

springboot-build:
	$(COMPOSE) build  $(SB) --no-cache

springboot-up:
	$(COMPOSE) up -d  $(SB)

springboot-restart:
	$(COMPOSE) stop  $(SB) && $(COMPOSE) rm -f  $(SB) && $(COMPOSE) up -d  $(SB)

logs-springboot:
	docker logs  $(SB)

in-sprintboot:
	docker exec -it $(SB) /bin/bash

#--------------------- DBase - mysql_db_micro mysql --------------------######################################
db-down:
	$(COMPOSE) stop  $(MYSQL) && $(COMPOSE) rm -f  $(MYSQL)

db-build:
	$(COMPOSE) build $(MYSQL) --no-cache

db-restart:
	$(COMPOSE) restart  $(MYSQL)

db-up:
	$(COMPOSE) up -d  $(MYSQL)

in-db:
	docker exec -it  $(MYSQL) /bin/bash

in-db-mysql:
	docker exec -it $(MYSQL) mysql -u user -ppassword

in-db-mysql-root:
	docker exec -it $(MYSQL) mysql -u root -prootpassword

db-update:
	@echo "Updating database..."
	@for file in $$(ls conf/mysql/db/files/sql/migrations/*.sql | sort); do \
		echo "Executing $$file..."; \
		docker exec $(MYSQL) sh -c 'mysql -u user -ppassword pokemondb < /var/www/html/sql/migrations/'$$(basename $$file); \
	done

db-reset:
	@echo "Resetting database..."
	@docker exec $(MYSQL) sh -c 'mysql -u user -ppassword pokemondb < /var/www/html/sql/00_reset.sql'

db-init: db-reset db-update



