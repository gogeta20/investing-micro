#--------------------- PROXY nginx - nginx_proxy --------------------######################################

in-nginx:
	docker exec -it $(NG) /bin/bash

proxy-nginx-logs:
	docker logs $(NG)

verify-nginx-conf:
	docker exec $(NG) nginx -t

reload-nginx:
	docker exec $(NG) nginx -s reload
