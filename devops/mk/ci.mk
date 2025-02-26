#--------------------- JENKINS - jenkins_micro --------------------######################################

pass-init-jenkins:
	docker exec -it $(J) cat /var/jenkins_home/secrets/initialAdminPassword

in-jenkins:
	docker exec -it  $(J) /bin/bash

up-ci:
	docker compose -f docker-compose.extra.yml --profile ci up -d

sonar-space:
	sudo sysctl -w vm.max_map_count=262144
