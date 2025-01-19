#--------------------- PROXY nginx - nginx_proxy --------------------######################################

nginx-down:
	$(COMPOSE) stop $(NG) && $(COMPOSE) rm -f $(NG)

nginx-build:
	$(COMPOSE) build $(NG) --no-cache

nginx-up:
	$(COMPOSE) up -d $(NG)

nginx-restart:
	$(COMPOSE) restart $(NG)

in-nginx:
	docker exec -it $(NG) /bin/bash

logs-nginx:
	docker logs $(NG)

verify-nginx-conf:
	docker exec $(NG) nginx -t

reload-nginx:
	docker exec $(NG) nginx -s reload
