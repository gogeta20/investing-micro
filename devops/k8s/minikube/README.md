# Minikube (Kubernetes local)

Este directorio contiene instrucciones para trabajar con Kubernetes en local usando **Minikube**.

---

## 🧱 Requisitos

- Ubuntu 22+
- Docker instalado y funcionando (`docker ps` debe funcionar)
- `kubectl` instalado y accesible (verificado con `kubectl version`)
- Internet activo para descargar imágenes

---

## 🚀 Instalación de Minikube

```bash
curl -LO https://storage.googleapis.com/minikube/releases/latest/minikube-linux-amd64
sudo install minikube-linux-amd64 /usr/local/bin/minikube
