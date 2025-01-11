#--------------------- BACK - springboot_backend java --------------------######################################

in-back-sprintboot:
	docker exec -it $(SB) /bin/bash

logs-springboot:
	docker logs  $(SB)

springboot-down:
	$(COMPOSE) stop  $(SB) && $(COMPOSE) rm -f  $(SB)

springboot-build:
	$(COMPOSE) build  $(SB) --no-cache

springboot-up:
	$(COMPOSE) up -d  $(SB)

springboot-restart:
	$(COMPOSE) stop  $(SB) && $(COMPOSE) rm -f  $(SB) && $(COMPOSE) up -d  $(SB)
