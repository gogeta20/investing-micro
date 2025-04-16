ENV_LOCAL=.env.local
ENV_CLOUDBACKEND=.env.cloudbackend
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
R=rust_consumer
C=certbot
LOCAL=--env-file $(ENV_LOCAL) -f docker-compose.local.yml --profile local
BACKENDCLOUD=--env-file $(ENV_CLOUDBACKEND) -f docker-compose.cloudbackend.yml
ENV_FILE?=.env

include devops/mk/*.mk

build:
	docker compose build

build-d:
	docker compose up --build -d

build--no-cache:
	docker compose build --no-cache

up:
	docker compose up -d

up-env: # make up ENV_FILE=.env.local
	docker compose --env-file $(ENV_FILE) up -d

down:
	docker compose down

down-volume:
	docker compose down -v

up-local:
	$(COMPOSE) $(LOCAL) up -d

down-local:
	$(COMPOSE) $(LOCAL) down

build-local:
	$(COMPOSE) -f $(LOCAL) build --no-cache

up-cloud-backend:
	$(COMPOSE) $(BACKENDCLOUD) up -d

down-cloud-backend:
	$(COMPOSE) $(BACKENDCLOUD) down

build-cloud-backend:
	$(COMPOSE) $(BACKENDCLOUD) build --no-cache

restart: down up
