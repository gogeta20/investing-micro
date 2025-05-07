#--------------------- BACK - fastapi_backend python --------------------######################################
in-fastapi:
	docker exec -it $(FB) /bin/bash

logs-back-fastapi:
	docker logs $(FB)

build-trainer-fastapi:
	docker build -f ./devops/dockerfiles/backend/FastAPI.Trainer.Dockerfile \
  -t chatbot-trainer:latest .

build-trainer-fastapi-two:
	docker build -f ../../../../devops/dockerfiles/backend/FastAPI.Trainer.Dockerfile \
  -t chatbot-trainer:latest .

py-require:
	docker exec -it $(FB) bash -c ""

fastapi-down:
	$(COMPOSE) stop  $(FB) && $(COMPOSE) rm -f  $(FB)

fastapi-build:
	$(COMPOSE) build  $(FB) --no-cache

fastapi-up:
	$(COMPOSE) up -d  $(FB)

fastapi-restart:
	$(COMPOSE) stop  $(FB) && $(COMPOSE) rm -f  $(FB) && $(COMPOSE) up -d  $(FB)

fastapi-install-requirements:
	docker exec -it $(FB) pip install -r requirements.txt

fastapi-pip: # make fastapi-pip f=kaggle
	docker exec -it $(FB) pip install $(f)

# Instala un paquete y recompila requirements.txt
# make pip-add p=python-dotenv
# make pip-install
pip-add:
	docker exec -it $(FB) sh -c "echo '$(p)' >> requirements.in && pip-compile requirements.in && pip install -r requirements.txt"

# Instala todo desde el .txt
pip-install:
	docker exec -it $(FB) pip install -r requirements.txt

# Actualiza todas las versiones
pip-upgrade:
	docker exec -it $(FB) pip-compile --upgrade

# Inspecciona lo que hay instalado (opcional)
pip-freeze:
	docker exec -it $(FB) pip freeze


fastapi-migrations:
	docker exec -it $(FB) python manage.py makemigrations

fastapi-migrate:
	docker exec -it $(FB) python manage.py migrate

fastapi-init-migrations: fastapi-migrations fastapi-migrate

#docker exec -u $(id -u):$(id -g) -it $(FB) python manage.py makemigrations
#docker exec -u $(id -u):$(id -g) -it $(FB) python manage.py migrate

#docker exec -it $(FB) chown -R $(id -u):$(id -g) /path/to/migrations
