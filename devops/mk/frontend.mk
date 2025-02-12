#--------------------- FRONT vue - front_micro --------------------######################################

frontend-down:
	$(COMPOSE) stop $(VF) && $(COMPOSE) rm -f $(VF)

frontend-build:
	$(COMPOSE) build $(VF) --no-cache

frontend-up:
	$(COMPOSE) up -d $(VF)

frontend-restart:
	$(COMPOSE) restart $(VF)

in-front:
	docker exec -it $(VF) sh

logs-front:
	docker logs $(VF)

copy-dist:
	cp -r ~/projects/personal/micro/project/front/dist/* ~/projects/personal/micro/docs
