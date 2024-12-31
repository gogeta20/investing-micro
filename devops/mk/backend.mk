#--------------------- BACK - symfony_backend --------------------######################################

composer-require:
	docker exec -it $(S) bash -c "composer require $(pkg)"

symfony-down:
	$(COMPOSE) stop  $(S) && $(COMPOSE) rm -f  $(S)

symfony-build:
	$(COMPOSE) build  $(S) --no-cache

symfony-up:
	$(COMPOSE) up -d  $(S)


#--------------------- BACK - springboot_backend java --------------------######################################

springboot-down:
	$(COMPOSE) stop  $(SB) && $(COMPOSE) rm -f  $(SB)

springboot-build:
	$(COMPOSE) build  $(SB) --no-cache

springboot-up:
	$(COMPOSE) up -d  $(SB)

springboot-restart:
	$(COMPOSE) stop  $(SB) && $(COMPOSE) rm -f  $(SB) && $(COMPOSE) up -d  $(SB)

#--------------------- BACK - django_backend python --------------------######################################

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
