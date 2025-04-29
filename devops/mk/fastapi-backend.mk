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

fastapi-migrations:
	docker exec -it $(FB) python manage.py makemigrations

fastapi-migrate:
	docker exec -it $(FB) python manage.py migrate

fastapi-init-migrations: fastapi-migrations fastapi-migrate

#docker exec -u $(id -u):$(id -g) -it $(FB) python manage.py makemigrations
#docker exec -u $(id -u):$(id -g) -it $(FB) python manage.py migrate

#docker exec -it $(FB) chown -R $(id -u):$(id -g) /path/to/migrations
