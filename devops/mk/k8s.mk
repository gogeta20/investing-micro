# devops/mk/k8s.mk

IMAGE_NAME := linuxlite20/fastapi-chatbot
TAG := 0.0.1

.PHONY: k8s-build k8s-push k8s-apply k8s-status k8s-delete

k8s-build:
	docker build -f devops/dockerfiles/backend/FastAPI.Dockerfile -t $(IMAGE_NAME):$(TAG) .

k8s-push:
	docker push $(IMAGE_NAME):$(TAG)

k8s-apply:
	kubectl apply -f devops/k8s/fastapi/deployment.yml
	kubectl apply -f devops/k8s/fastapi/service.yml

k8s-status:
	kubectl get pods
	kubectl get svc

k8s-delete:
	kubectl delete -f devops/k8s/fastapi/deployment.yml || true
	kubectl delete -f devops/k8s/fastapi/service.yml || true
