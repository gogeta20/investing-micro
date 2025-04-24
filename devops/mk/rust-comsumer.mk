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

rust-check:
	docker exec -it $(R) /bin/bash -c "cd /app/src && cargo check"

rust-lint:
	docker exec -it $(R) /bin/bash -c "cd /app/src && cargo clippy"

build-rust-consumer:
	docker exec -it $(R) /bin/bash -c "cd /app/src && cargo build --release"

rust-pwd:
	docker exec -it $(R) /bin/bash -c "cd /app && pwd"

run-rust-consumer:
	docker exec -it $(R) /app/rust_micro_app

logs-consumer:
	docker logs $(R)

rebuild-rust:
	docker compose --env-file .env.local -f docker-compose.local.yml down rust_consumer
	docker compose --env-file .env.local -f docker-compose.local.yml build rust_consumer
	docker compose --env-file .env.local -f docker-compose.local.yml up -d rust_consumer

upload-cloud:
	rm -rf ~/rust_micro_app
	docker cp rust_consumer:/app/rust_micro_app ~/rust_micro_app
	scp -i ~/.ssh/google_compute_engine ~/rust_micro_app mau@34.60.193.103:/home/mau/binarios/
