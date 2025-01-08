#--------------------- JENKINS - jenkins_micro --------------------######################################

pass-init-jenkins:
	docker exec -it $(J) cat /var/jenkins_home/secrets/initialAdminPassword
