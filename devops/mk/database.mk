#--------------------- DBase - mysql_db_micro mysql --------------------######################################
db-down:
	$(COMPOSE) stop  $(MYSQL) && $(COMPOSE) rm -f  $(MYSQL)

db-build:
	$(COMPOSE) build $(MYSQL) --no-cache

db-restart:
	$(COMPOSE) restart  $(MYSQL)

db-up:
	$(COMPOSE) up -d  $(MYSQL)

db-update:
	@echo "Updating database..."
	@for file in $$(ls conf/mysql/db/files/sql/migrations/*.sql | sort); do \
		echo "Executing $$file..."; \
		docker exec $(MYSQL) sh -c 'mysql -u user -ppassword pokemondb < /var/www/html/sql/migrations/'$$(basename $$file); \
	done

db-reset:
	@echo "Resetting database..."
	@docker exec $(MYSQL) sh -c 'mysql -u user -ppassword pokemondb < /var/www/html/sql/00_reset.sql'

db-init: db-reset db-update

db-migrate:
	@if [ -z "$(v)" ]; then \
		echo "Usage: make db-migrate v=<version>"; \
		exit 1; \
	fi
	@echo "Executing migration V$(v)..."
	@docker exec $(MYSQL) sh -c 'mysql -u user -ppassword my_database < /var/www/html/sql/migrations/V$(v).sql'

# Para ejecutar un seed específico
db-seed:
	@if [ -z "$(s)" ]; then \
		echo "Usage: make db-seed s=<seed>"; \
		exit 1; \
	fi
	@echo "Executing seed S$(s)..."
	@docker exec $(MYSQL) sh -c 'mysql -u user -ppassword my_database < /var/www/html/sql/seeds/S$(s).sql'

# Para revertir una migración específica
db-rollback:
	@if [ -z "$(v)" ]; then \
		echo "Usage: make db-rollback v=<version>"; \
		exit 1; \
	fi
	@echo "Rolling back migration V$(v)..."
	@docker exec $(MYSQL) sh -c 'mysql -u user -ppassword my_database < /var/www/html/sql/rollbacks/R$(v).sql'

#db-update:
#	@if [ -z "$(f)" ]; then \
#		echo "Usage: make db-update f=<filename>"; \
#		exit 1; \
#	fi
#	@echo "Executing $(f).sql..."
#	@docker exec $(MYSQL) sh -c 'mysql -u user -ppassword my_database < /var/www/html/sql/$(f).sql'


#db-update: #make db-update f=mySql
#	docker exec $(MYSQL) sh -c 'mysql -u user -ppassword -e "SOURCE /var/www/html/sql/$(f).sql"'

#--------------------- DBase - mongo_db - mongo --------------------######################################

mongo-down:
	$(COMPOSE) stop  $(M) && $(COMPOSE) rm -f  $(M)

mongo-build:
	$(COMPOSE) build $(M) --no-cache

mongo-restart:
	$(COMPOSE) restart  $(M)

mongo-up:
	$(COMPOSE) up -d  $(M)

mongo-reset:
	docker rm -v $(M)

mongo-init-db:
	docker exec -i $(M) mongosh -u root -p password < /docker-entrypoint-initdb.d/mongo-init.js

mongo-seed: # make mongo-seed s=1
	docker exec $(M) sh -c 'mongosh -u root -p password --eval "use intents_db"  < /var/www/html/scripts/seeds/S$(s).js'

