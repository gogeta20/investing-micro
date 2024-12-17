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
