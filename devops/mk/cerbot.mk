#--------------------- CERBOT --------------------######################################

certbot-down:
	$(COMPOSE) stop $(C) && $(COMPOSE) rm -f $(C)

certbot-build:
	$(COMPOSE) build $(C) --no-cache

certbot-up:
	$(COMPOSE) up -d $(C)

certbot-restart:
	$(COMPOSE) restart $(C)

in-certbot:
	docker exec -it $(C) /bin/sh

logs-certbot:
	docker logs $(C)

verify-certbot-conf:
	docker exec $(C) nginx -t

reload-certbot:
	docker exec $(C) nginx -s reload


