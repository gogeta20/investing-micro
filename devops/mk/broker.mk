#--------------------- rabbitmq --------------------######################################
in-rabbit:
	docker exec -it $(MQ) /bin/bash

rabbit-enable-rabbitmq-management:
	docker exec -it $(MQ) rabbitmq-plugins enable rabbitmq_management

rabbit-disable-rabbitmq-management:
	docker exec -it $(MQ) rabbitmq-plugins disable rabbitmq_management

rabbit-down:
	$(COMPOSE) stop $(MQ) && $(COMPOSE) rm -f  $(MQ)

rabbit-build:
	$(COMPOSE) build $(MQ) --no-cache

rabbit-up:
	$(COMPOSE) up -d $(MQ)

logs-rabbit:
	docker logs $(MQ)

rabbit-list:
	docker exec -it $(MQ) rabbitmqctl list_queues
# docker exec -it rabbitmq rabbitmqctl purge_queue events
# docker exec -it rabbitmq rabbitmqctl list_consumers

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
