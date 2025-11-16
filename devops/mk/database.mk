#--------------------- DBase - mysql_db_micro mysql --------------------######################################

in-db:
	docker exec -it  $(MYSQL) /bin/bash

in-db-mysql:
	docker exec -it $(MYSQL) mysql -u user -ppassword

in-db-mysql-root:
	docker exec -it $(MYSQL) mysql -u root -prootpassword

mysql-down:
	$(COMPOSE) stop  $(MYSQL) && $(COMPOSE) rm -f  $(MYSQL)

dump-db:
	docker exec $(MYSQL) mysqldump -u user -ppassword investing_micro_db > dumpdb.sql
#     docker exec $(MYSQL) mysqldump -u root -prootpassword investing_micro_db > backup_$(date +%Y%m%d_%H%M%S).sql

mysql-clean-volume:
	@echo "⚠️  WARNING: This will delete all MySQL data!"
	@echo "Stopping and removing MySQL container..."
	$(COMPOSE) stop $(MYSQL) || true
	$(COMPOSE) rm -f $(MYSQL) || true
	@echo "Removing MySQL volume data..."
	@rm -rf ./devops/volumes/mysql/*
	@echo "✅ MySQL volume cleaned. You can now start MySQL with: make mysql-up"

mysql-build:
	$(COMPOSE) build $(MYSQL) --no-cache

mysql-restart:
	$(COMPOSE) restart  $(MYSQL)

mysql-up:
	$(COMPOSE) up -d  $(MYSQL)

mysql-update:
	@echo "Updating database..."
	@for file in $$(ls conf/mysql/db/files/sql/migrations/*.sql | sort); do \
		echo "Executing $$file..."; \
		docker exec $(MYSQL) sh -c 'mysql -u user -ppassword $(MYSQL_DATABASE) < /var/www/html/sql/migrations/'$$(basename $$file); \
	done

mysql-reset:
	@echo "Resetting database..."
	docker exec $(MYSQL) sh -c 'mysql -u user -ppassword $(MYSQL_DATABASE) < /var/www/html/sql/00_reset.sql'

mysql-init: mysql-reset mysql-update

mysql-migrate:
	@if [ -z "$(v)" ]; then \
		echo "Usage: make mysql-migrate v=<version>"; \
		exit 1; \
	fi
	@echo "Executing migration V$(v)..."
	@docker exec $(MYSQL) sh -c 'mysql -u user -ppassword $(MYSQL_DATABASE) < /var/www/html/sql/migrations/V$(v).sql'

# Para ejecutar un seed específico
mysql-seed:
	@if [ -z "$(s)" ]; then \
		echo "Usage: make mysql-seed s=<seed>"; \
		exit 1; \
	fi
	@echo "Executing seed S$(s)..."
	@docker exec $(MYSQL) sh -c 'mysql -u user -ppassword $(MYSQL_DATABASE) < /var/www/html/sql/seeds/S$(s).sql'

# Para revertir una migración específica
mysql-rollback:
	@if [ -z "$(v)" ]; then \
		echo "Usage: make mysql-rollback v=<version>"; \
		exit 1; \
	fi
	@echo "Rolling back migration V$(v)..."
	@docker exec $(MYSQL) sh -c 'mysql -u user -ppassword my_database < /var/www/html/sql/rollbacks/R$(v).sql'

#mysql-update:
#	@if [ -z "$(f)" ]; then \
#		echo "Usage: make mysql-update f=<filename>"; \
#		exit 1; \
#	fi
#	@echo "Executing $(f).sql..."
#	@docker exec $(MYSQL) sh -c 'mysql -u user -ppassword my_database < /var/www/html/sql/$(f).sql'


#mysql-update: #make mysql-update f=mySql
#	docker exec $(MYSQL) sh -c 'mysql -u user -ppassword -e "SOURCE /var/www/html/sql/$(f).sql"'

#--------------------- DBase - mongo_db - mongo --------------------######################################

in-mongo:
	docker exec -it $(M) /bin/bash

in-db-mongo:
	docker exec -it $(M) mongosh -u root -p password

logs-mongo:
	docker logs $(M)

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

mongo-seed-db-primary: # make mongo-seed s=3
	docker exec $(M) sh -c 'mongosh -u root -p password --eval "use pokemondb"  < /var/www/html/scripts/seeds/S$(s).js'


mongo-seed-basic: # make mongo-seed s=1
	docker exec $(M) sh -c 'mongosh -u root -p password  < /var/www/html/scripts/seeds/S$(s).js'

mongo-export:
	docker exec -it $(M) mongoexport --host localhost --port 27017 --username root --password password --authenticationDatabase admin --db intents_db --collection intents --out=/var/www/html/intents.json --jsonArray
