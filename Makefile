
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
LOCAL=docker-compose.local.yml --profile local

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

up-local:
	$(COMPOSE) -f $(LOCAL) up -d

down-local:
	$(COMPOSE) -f $(LOCAL) down

build-local:
	$(COMPOSE) -f $(LOCAL) build --no-cache

restart: down up

cloud-back-up:
	docker compose -f docker-compose.cloudaws.yml up -d

cloud-back-down:
	docker compose -f docker-compose.cloudaws.yml down

cloud-back-build:
	docker compose -f docker-compose.cloudaws.yml build
