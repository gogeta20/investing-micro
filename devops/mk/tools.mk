ps:
	docker compose ps

logs:
	docker compose logs $(c)

init-volumes:
	mkdir -p devops/volumes/mysql devops/volumes/postgres devops/volumes/jenkins

clean-volumes:
	docker volume rm $$(docker volume ls -q)

delete-images: #Eliminar Imágenes "Dangling" (Sin Nombre ni Etiqueta)
	docker image prune -f

delete-images-off: #Eliminar Contenedores Parados
	docker container prune -f

delete-volumes:
	docker volume prune -f

delete-networks:
	docker network prune -f

delete-no-use:
	docker system prune -f

delete-system-all:
	docker system prune -a --volumes -f

clean-docker:
	@echo "Eliminando contenedores parados..."
	docker container prune -f
	@echo "Eliminando imágenes dangling..."
	docker image prune -f
	@echo "Eliminando volúmenes no utilizados..."
	docker volume prune -f
	@echo "Eliminando redes no utilizadas..."
	docker network prune -f

clean-micro:
	@echo "Eliminando contenedores del proyecto micro..."
	docker ps -a --filter "name=micro" -q | xargs -r docker rm -f
	@echo "Eliminando imágenes del proyecto micro..."
	docker images --filter=reference="micro*" -q | xargs -r docker rmi -f
	@echo "Eliminando volúmenes del proyecto micro..."
	docker volume ls --filter "name=micro" -q | xargs -r docker volume rm
	@echo "Eliminando redes del proyecto micro..."
	docker network ls --filter "name=app_network" -q | xargs -r docker network rm




view-network:
	docker network inspect app_network

setup-git-hooks:
	@echo "🔧 Configurando hooks compartidos en .githooks..."
	git config core.hooksPath .githooks
	chmod +x .githooks/pre-push
	@echo "✅ Hooks compartidos configurados correctamente."
