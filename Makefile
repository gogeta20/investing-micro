ENV_LOCAL=.env.local
ENV_CLOUDBACKEND=.env.cloudbackend
COMPOSE=docker compose
VF=front_micro
S=symfony_backend
SB=springboot_backend
DB=django_backend
FB=fastapi_backend
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
	$(COMPOSE) $(LOCAL) build --build-arg MICRO_ENV=local --no-cache
#     docker compose build --build-arg MICRO_ENV=local nginx_proxy

up-cloud-backend:
	$(COMPOSE) $(BACKENDCLOUD) up -d

down-cloud-backend:
	$(COMPOSE) $(BACKENDCLOUD) down

build-cloud-backend:
	$(COMPOSE) $(BACKENDCLOUD) build --no-cache

restart: down up

.PHONY: help
help: ## Muestra todos los comandos disponibles con sus parámetros y descripciones
	@echo "═══════════════════════════════════════════════════════════════════════════════"
	@echo "                    COMANDOS MAKE DISPONIBLES"
	@echo "═══════════════════════════════════════════════════════════════════════════════"
	@echo ""
	@echo "📦 DOCKER COMPOSE - Gestión General"
	@echo "───────────────────────────────────────────────────────────────────────────────"
	@echo "  make build                    - Construye todas las imágenes"
	@echo "  make build-d                  - Construye y levanta en modo detached"
	@echo "  make build--no-cache          - Construye sin usar caché"
	@echo "  make up                       - Levanta todos los servicios"
	@echo "  make up-env ENV_FILE=.env.local - Levanta con archivo de entorno específico"
	@echo "  make down                     - Detiene todos los servicios"
	@echo "  make down-volume              - Detiene y elimina volúmenes"
	@echo "  make restart                  - Reinicia todos los servicios"
	@echo "  make ps                       - Muestra estado de contenedores"
	@echo "  make logs c=<container>       - Muestra logs de un contenedor"
	@echo ""
	@echo "🏠 ENTORNO LOCAL"
	@echo "───────────────────────────────────────────────────────────────────────────────"
	@echo "  make up-local                 - Levanta servicios en modo local"
	@echo "  make down-local               - Detiene servicios locales"
	@echo "  make build-local              - Construye imágenes para entorno local"
	@echo ""
	@echo "☁️  ENTORNO CLOUD BACKEND"
	@echo "───────────────────────────────────────────────────────────────────────────────"
	@echo "  make up-cloud-backend         - Levanta servicios cloud backend"
	@echo "  make down-cloud-backend       - Detiene servicios cloud backend"
	@echo "  make build-cloud-backend      - Construye imágenes cloud backend"
	@echo ""
	@echo "🗄️  BASE DE DATOS - MySQL"
	@echo "───────────────────────────────────────────────────────────────────────────────"
	@echo "  make in-db                    - Entra al contenedor MySQL"
	@echo "  make in-db-mysql              - Accede a MySQL como usuario"
	@echo "  make in-db-mysql-root         - Accede a MySQL como root"
	@echo "  make mysql-down               - Detiene MySQL"
	@echo "  make mysql-build              - Construye imagen MySQL"
	@echo "  make mysql-up                 - Levanta MySQL"
	@echo "  make mysql-restart            - Reinicia MySQL"
	@echo "  make dump-db                  - Crea dump de la base de datos"
	@echo "  make mysql-clean-volume       - Limpia volúmenes de MySQL (⚠️  destructivo)"
	@echo "  make mysql-update             - Ejecuta todas las migraciones"
	@echo "  make mysql-reset              - Resetea la base de datos"
	@echo "  make mysql-init               - Inicializa DB (reset + update)"
	@echo "  make mysql-migrate v=<version> - Ejecuta migración específica (ej: v=2)"
	@echo "  make mysql-execute v=<version> - Ejecuta SQL específico (ej: v=2)"
	@echo "  make mysql-execute-in-db v=<file> db=<database> - Ejecuta SQL en DB específica"
	@echo "  make mysql-seed s=<seed>      - Ejecuta seed específico (ej: s=1)"
	@echo "  make mysql-rollback v=<version> - Revierte migración (ej: v=2)"
	@echo ""
	@echo "🍃 BASE DE DATOS - MongoDB"
	@echo "───────────────────────────────────────────────────────────────────────────────"
	@echo "  make in-mongo                 - Entra al contenedor MongoDB"
	@echo "  make in-db-mongo              - Accede a MongoDB shell"
	@echo "  make logs-mongo               - Muestra logs de MongoDB"
	@echo "  make mongo-down               - Detiene MongoDB"
	@echo "  make mongo-build              - Construye imagen MongoDB"
	@echo "  make mongo-up                 - Levanta MongoDB"
	@echo "  make mongo-restart            - Reinicia MongoDB"
	@echo "  make mongo-reset              - Resetea MongoDB"
	@echo "  make mongo-init-db            - Inicializa base de datos MongoDB"
	@echo "  make mongo-seed s=<seed>      - Ejecuta seed en intents_db (ej: s=1)"
	@echo "  make mongo-seed-db-primary s=<seed> - Ejecuta seed en pokemondb (ej: s=3)"
	@echo "  make mongo-seed-basic s=<seed> - Ejecuta seed básico (ej: s=1)"
	@echo "  make mongo-export             - Exporta colección intents a JSON"
	@echo ""
	@echo "🐍 BACKEND - Django"
	@echo "───────────────────────────────────────────────────────────────────────────────"
	@echo "  make in-django                - Entra al contenedor Django"
	@echo "  make logs-back-django         - Muestra logs de Django"
	@echo "  make django-down              - Detiene Django"
	@echo "  make django-build             - Construye imagen Django"
	@echo "  make django-up                - Levanta Django"
	@echo "  make django-restart           - Reinicia Django"
	@echo "  make django-install-requirements - Instala dependencias Python"
	@echo "  make django-pip f=<package>   - Instala paquete Python (ej: f=kaggle)"
	@echo "  make django-migrations        - Crea migraciones Django"
	@echo "  make django-migrate           - Ejecuta migraciones Django"
	@echo "  make django-init-migrations   - Crea y ejecuta migraciones"
	@echo ""
	@echo "🚀 BACKEND - FastAPI"
	@echo "───────────────────────────────────────────────────────────────────────────────"
	@echo "  make in-fastapi               - Entra al contenedor FastAPI"
	@echo "  make logs-back-fastapi        - Muestra logs de FastAPI"
	@echo "  make fastapi-down             - Detiene FastAPI"
	@echo "  make fastapi-build            - Construye imagen FastAPI"
	@echo "  make fastapi-up               - Levanta FastAPI"
	@echo "  make fastapi-restart          - Reinicia FastAPI"
	@echo "  make fastapi-install-requirements - Instala dependencias Python"
	@echo "  make fastapi-pip f=<package>  - Instala paquete Python (ej: f=kaggle)"
	@echo "  make pip-add p=<package>      - Añade paquete a requirements.in y compila"
	@echo "  make pip-install              - Instala desde requirements.txt"
	@echo "  make pip-upgrade              - Actualiza todas las versiones"
	@echo "  make pip-freeze               - Muestra paquetes instalados"
	@echo "  make fastapi-migrations       - Crea migraciones FastAPI"
	@echo "  make fastapi-migrate          - Ejecuta migraciones FastAPI"
	@echo "  make fastapi-init-migrations  - Crea y ejecuta migraciones"
	@echo "  make build-trainer-fastapi    - Construye imagen trainer FastAPI"
	@echo "  make build-trainer-fastapi-two - Construye trainer desde otra ruta"
	@echo ""
	@echo "🎨 FRONTEND - Vue"
	@echo "───────────────────────────────────────────────────────────────────────────────"
	@echo "  make in-front                 - Entra al contenedor frontend"
	@echo "  make logs-front               - Muestra logs del frontend"
	@echo "  make frontend-down            - Detiene frontend"
	@echo "  make frontend-build           - Construye imagen frontend"
	@echo "  make frontend-up              - Levanta frontend"
	@echo "  make frontend-restart         - Reinicia frontend"
	@echo "  make build-front              - Compila frontend para producción"
	@echo "  make copy-dist folder=<name>  - Copia dist a carpeta (ej: folder=last)"
	@echo ""
	@echo "🔧 BACKEND - Symfony"
	@echo "───────────────────────────────────────────────────────────────────────────────"
	@echo "  make in-back-symfony          - Entra al contenedor Symfony"
	@echo "  make logs-back-symfony        - Muestra logs de Symfony"
	@echo "  make symfony-down             - Detiene Symfony"
	@echo "  make symfony-build            - Construye imagen Symfony"
	@echo "  make symfony-up               - Levanta Symfony"
	@echo "  make composer-require pkg=<package> - Instala paquete Composer"
	@echo "  make symfony-on-rabbit        - Consume mensajes de RabbitMQ (events)"
	@echo "  make symfony-rabbit-launch-events - Consume cola other_queue con límites"
	@echo ""
	@echo "☕ BACKEND - Spring Boot"
	@echo "───────────────────────────────────────────────────────────────────────────────"
	@echo "  make in-back-sprintboot       - Entra al contenedor Spring Boot"
	@echo "  make logs-springboot          - Muestra logs de Spring Boot"
	@echo "  make springboot-down          - Detiene Spring Boot"
	@echo "  make springboot-build         - Construye imagen Spring Boot"
	@echo "  make springboot-up            - Levanta Spring Boot"
	@echo "  make springboot-restart       - Reinicia Spring Boot"
	@echo ""
	@echo "🦀 RUST CONSUMER"
	@echo "───────────────────────────────────────────────────────────────────────────────"
	@echo "  make in-rust-consumer         - Entra al contenedor Rust"
	@echo "  make logs-consumer            - Muestra logs del consumer"
	@echo "  make consumer-down            - Detiene consumer"
	@echo "  make consumer-build           - Construye imagen consumer"
	@echo "  make consumer-up              - Levanta consumer"
	@echo "  make consumer-restart         - Reinicia consumer"
	@echo "  make rust-check               - Ejecuta cargo check"
	@echo "  make rust-lint                - Ejecuta cargo clippy"
	@echo "  make build-rust-consumer      - Compila Rust en modo release"
	@echo "  make run-rust-consumer        - Ejecuta aplicación Rust"
	@echo "  make rebuild-rust             - Reconstruye consumer Rust"
	@echo "  make upload-cloud             - Sube binario a servidor cloud"
	@echo ""
	@echo "🌐 PROXY - Nginx"
	@echo "───────────────────────────────────────────────────────────────────────────────"
	@echo "  make in-nginx                 - Entra al contenedor Nginx"
	@echo "  make logs-nginx               - Muestra logs de Nginx"
	@echo "  make nginx-down                - Detiene Nginx"
	@echo "  make nginx-build               - Construye imagen Nginx"
	@echo "  make nginx-up                  - Levanta Nginx"
	@echo "  make nginx-restart             - Reinicia Nginx"
	@echo "  make verify-nginx-conf         - Verifica configuración Nginx"
	@echo "  make reload-nginx              - Recarga configuración Nginx"
	@echo ""
	@echo "🔐 CERTBOT"
	@echo "───────────────────────────────────────────────────────────────────────────────"
	@echo "  make in-certbot               - Entra al contenedor Certbot"
	@echo "  make logs-certbot             - Muestra logs de Certbot"
	@echo "  make certbot-down             - Detiene Certbot"
	@echo "  make certbot-build            - Construye imagen Certbot"
	@echo "  make certbot-up               - Levanta Certbot"
	@echo "  make certbot-restart          - Reinicia Certbot"
	@echo "  make verify-certbot-conf      - Verifica configuración Certbot"
	@echo "  make reload-certbot           - Recarga configuración Certbot"
	@echo ""
	@echo "🐰 BROKER - RabbitMQ"
	@echo "───────────────────────────────────────────────────────────────────────────────"
	@echo "  make in-rabbit                - Entra al contenedor RabbitMQ"
	@echo "  make logs-rabbit              - Muestra logs de RabbitMQ"
	@echo "  make rabbit-down              - Detiene RabbitMQ"
	@echo "  make rabbit-build             - Construye imagen RabbitMQ"
	@echo "  make rabbit-up                - Levanta RabbitMQ"
	@echo "  make rabbit-list              - Lista colas de RabbitMQ"
	@echo "  make rabbit-enable-rabbitmq-management - Habilita plugin management"
	@echo "  make rabbit-disable-rabbitmq-management - Deshabilita plugin management"
	@echo ""
	@echo "☸️  KUBERNETES"
	@echo "───────────────────────────────────────────────────────────────────────────────"
	@echo "  make k8s-build                - Construye imagen para K8s"
	@echo "  make k8s-push                 - Sube imagen a registry"
	@echo "  make k8s-apply                - Aplica deployments y services"
	@echo "  make k8s-status               - Muestra estado de pods y services"
	@echo "  make k8s-delete               - Elimina deployments y services"
	@echo ""
	@echo "🔄 CI/CD - Jenkins & GitLab"
	@echo "───────────────────────────────────────────────────────────────────────────────"
	@echo "  make ci                       - Levanta GitLab CI completo"
	@echo "  make ci-up                    - Levanta GitLab y Runner"
	@echo "  make ci-down                  - Detiene GitLab y Runner"
	@echo "  make ci-logs                  - Muestra logs de GitLab"
	@echo "  make ci-restart               - Reinicia GitLab y Runner"
	@echo "  make ci-status                - Muestra estado de servicios CI"
	@echo "  make in-jenkins               - Entra al contenedor Jenkins"
	@echo "  make pass-init-jenkins        - Muestra password inicial de Jenkins"
	@echo "  make up-ci-jenkins            - Levanta Jenkins en modo CI"
	@echo "  make up-ci-test-jenkins       - Levanta Jenkins para tests"
	@echo "  make build-ci-test-jenkins    - Construye Jenkins para tests"
	@echo "  make down-ci-test-jenkins     - Detiene Jenkins de tests"
	@echo "  make sonar-space              - Configura vm.max_map_count para SonarQube"
	@echo ""
	@echo "🛠️  HERRAMIENTAS Y MANTENIMIENTO"
	@echo "───────────────────────────────────────────────────────────────────────────────"
	@echo "  make init-volumes             - Crea directorios de volúmenes"
	@echo "  make clean-volumes            - Elimina todos los volúmenes Docker"
	@echo "  make delete-images            - Elimina imágenes dangling"
	@echo "  make delete-images-off        - Elimina contenedores parados"
	@echo "  make delete-volumes           - Elimina volúmenes no utilizados"
	@echo "  make delete-networks          - Elimina redes no utilizadas"
	@echo "  make delete-no-use            - Limpia sistema Docker (prune)"
	@echo "  make delete-system-all        - Limpia TODO el sistema Docker (⚠️  destructivo)"
	@echo "  make clean-docker             - Limpia contenedores, imágenes, volúmenes y redes"
	@echo "  make clean-micro              - Limpia recursos del proyecto micro"
	@echo "  make view-network             - Inspecciona red app_network"
	@echo "  make setup-git-hooks          - Configura hooks de Git compartidos"
	@echo ""
	@echo "═══════════════════════════════════════════════════════════════════════════════"
	@echo "💡 Tip: Usa 'make help' para ver esta ayuda en cualquier momento"
	@echo "═══════════════════════════════════════════════════════════════════════════════"
