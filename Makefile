
# Variable para el directorio del proyecto
SPRINGBOOT_DIR=project/backend/springboot
SB=symfony_backend
NG=nginx_proxy
VF=front_micro
J=jenkins_micro


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

