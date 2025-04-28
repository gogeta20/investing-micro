#--------------------- JENKINS - jenkins_micro --------------------######################################

pass-init-jenkins:
	docker exec -it $(J) cat /var/jenkins_home/secrets/initialAdminPassword

in-jenkins:
	docker exec -it  $(J) /bin/bash

up-ci:
	docker compose -f docker-compose.extra.yml --profile ci up -d

up-ci-test:
	docker compose -f docker-compose.test.yml --profile ci up -d

build-ci-test:
	docker compose -f docker-compose.test.yml --profile ci build

down-ci-test:
	docker compose -f docker-compose.test.yml --profile ci down

sonar-space:
	sudo sysctl -w vm.max_map_count=262144
