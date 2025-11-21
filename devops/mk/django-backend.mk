#--------------------- BACK - django_backend python --------------------######################################
in-django:
	docker exec -it $(DB) /bin/bash

logs-back-django:
	docker logs $(DB)

django-py-require:
	docker exec -it $(DB) bash -c ""

django-down:
	$(COMPOSE) stop  $(DB) && $(COMPOSE) rm -f  $(DB)

django-build:
	$(COMPOSE) build  $(DB) --no-cache

django-up:
	$(COMPOSE) up -d  $(DB)

django-restart:
	$(COMPOSE) stop  $(DB) && $(COMPOSE) rm -f  $(DB) && $(COMPOSE) up -d  $(DB)

django-install-requirements:
	docker exec -it $(DB) pip install -r requirements.txt

django-pip: # make django-pip f=kaggle
	docker exec -it $(DB) pip install $(f)

django-migrations:
	docker exec -it $(DB) python manage.py makemigrations

django-migrate:
	docker exec -it $(DB) python manage.py migrate

django-init-migrations: django-migrations django-migrate

#docker exec -u $(id -u):$(id -g) -it $(DB) python manage.py makemigrations
#docker exec -u $(id -u):$(id -g) -it $(DB) python manage.py migrate

#docker exec -it $(DB) chown -R $(id -u):$(id -g) /path/to/migrations
