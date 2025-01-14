# Usar la imagen oficial de Node.js
FROM node:18-alpine

# Instalar pnpm y vite globalmente
RUN npm install -g pnpm vite

# Establecer el directorio de trabajo en el contenedor
WORKDIR /app

# Copiar los archivos de configuración de pnpm desde la carpeta del proyecto
COPY project/front/package.json project/front/pnpm-lock.yaml ./

# Instalar las dependencias
RUN pnpm install

# Copiar todo el código fuente del frontend al contenedor
COPY project/front/ .

# Exponer el puerto para el servidor de desarrollo de Vue
EXPOSE 5173

# Comando para ejecutar el servidor de desarrollo
CMD ["pnpm", "run", "dev"]
