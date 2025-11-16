.PHONY: help ci ci-up ci-down ci-logs ci-restart

# Colores para output
GREEN  := \033[0;32m
YELLOW := \033[0;33m
RESET  := \033[0m

help: ## Muestra esta ayuda
	@echo "$(GREEN)Comandos disponibles:$(RESET)"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  $(YELLOW)%-15s$(RESET) %s\n", $$1, $$2}'

ci: ci-up ## Levanta GitLab CI completo

ci-up: ## Levanta GitLab y Runner
	@echo "$(GREEN)Levantando GitLab CI...$(RESET)"
	docker compose --env-file $(ENV_LOCAL) -f docker-compose.local.yml --profile ci up -d
	@echo "$(YELLOW)GitLab estará listo en ~3-5 minutos en http://localhost:8090$(RESET)"
	@echo "$(YELLOW)Usuario: root | Password: rootpassword123$(RESET)"

ci-down: ## Detiene GitLab y Runner
	@echo "$(GREEN)Deteniendo GitLab CI...$(RESET)"
	docker compose --env-file $(ENV_LOCAL) -f docker-compose.local.yml --profile ci down

ci-logs: ## Muestra logs de GitLab
	docker compose --env-file $(ENV_LOCAL) -f docker-compose.local.yml --profile ci logs -f gitlab

ci-restart: ## Reinicia GitLab y Runner
	@echo "$(GREEN)Reiniciando GitLab CI...$(RESET)"
	docker compose --env-file $(ENV_LOCAL) -f docker-compose.local.yml --profile ci restart

ci-status: ## Muestra el estado de los servicios CI
	@echo "$(GREEN)Estado de servicios CI:$(RESET)"
	docker compose --env-file $(ENV_LOCAL) -f docker-compose.local.yml --profile ci ps



pass-init-jenkins:
	docker exec -it $(J) cat /var/jenkins_home/secrets/initialAdminPassword

in-jenkins:
	docker exec -it  $(J) /bin/bash

up-ci-jenkins:
	docker compose --env-file $(ENV_LOCAL) -f docker-compose.extra.yml --profile ci up -d

up-ci-test-jenkins:
	docker compose --env-file $(ENV_LOCAL) -f docker-compose.test.yml --profile ci-main up -d

build-ci-test-jenkins:
	docker compose --env-file $(ENV_LOCAL) -f docker-compose.test.yml --profile ci-main build

down-ci-test-jenkins:
	docker compose --env-file $(ENV_LOCAL) -f docker-compose.test.yml --profile ci-main down

sonar-space:
	sudo sysctl -w vm.max_map_count=262144
