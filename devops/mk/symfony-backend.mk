#--------------------- BACK - symfony_backend --------------------######################################

in-back-symfony:
	docker exec -it $(S) /bin/bash

logs-back-symfony:
	docker logs $(S)

composer-require:
	docker exec -it $(S) bash -c "composer require $(pkg)"

symfony-down:
	$(COMPOSE) stop  $(S) && $(COMPOSE) rm -f  $(S)

symfony-build:
	$(COMPOSE) build  $(S) --no-cache

symfony-up:
	$(COMPOSE) up -d  $(S)

symfony-on-rabbit:
	docker exec -it $(S) bash -c "php bin/console messenger:consume events -vvv"

symfony-rabbit-launch-events:
	docker exec -it $(S) php bin/console messenger:consume other_queue --time-limit=60 --memory-limit=128M

symfony-on-rabbit:
	docker exec -it $(S) bash -c "php bin/console messenger:consume events -vvv"
