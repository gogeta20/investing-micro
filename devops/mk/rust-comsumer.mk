#--------------------- rust consumer --------------------######################################

consumer-down:
	$(COMPOSE) stop $(R) && $(COMPOSE) rm -f  $(R)

consumer-build:
	$(COMPOSE) build $(R) --no-cache

consumer-up:
	$(COMPOSE) up -d $(R)

consumer-restart: consumer-down consumer-up

in-rust-consumer:
	docker exec -it $(R) /bin/bash

logs-consumer:
	docker logs $(R)
