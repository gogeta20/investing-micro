
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
