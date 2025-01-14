#--------------------- FRONT vue - front_micro --------------------######################################

frontend-down:
	$(COMPOSE) stop $(VF) && $(COMPOSE) rm -f $(VF)

frontend-build:
	$(COMPOSE) build $(VF) --no-cache

frontend-up:
	$(COMPOSE) up -d $(VF)

in-front:
	docker exec -it $(VF) sh

logs-front:
	docker logs $(VF)
