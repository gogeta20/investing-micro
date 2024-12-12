# Usar la imagen oficial de Gradle con OpenJDK 17 para la compilación de la aplicación
#FROM gradle:7.6.2-jdk17 AS build
FROM gradle:8.4-jdk17 AS build
# Establecer el directorio de trabajo
WORKDIR /app

# Copiar solo los archivos necesarios para resolver dependencias
COPY project/backend/springboot/build.gradle .
COPY project/backend/springboot/settings.gradle .
#COPY project/backend/springboot/settings.properties .
#COPY project/backend/springboot/glade .

# Descargar las dependencias de Gradle
RUN gradle dependencies --no-daemon || true

# Copiar todo el código fuente al contenedor
COPY project/backend/springboot/ .

# Compilar el código fuente y crear el archivo JAR
RUN #gradle clean build --no-daemon || true
RUN #gradle clean build -x test
RUN gradle clean build -x test --no-daemon

# Usar la imagen oficial de OpenJDK 17 para ejecutar la aplicación
#FROM eclipse-temurin:17-jdk-jammy
FROM openjdk:17-slim

# Establecer el directorio de trabajo
WORKDIR /app

# Copiar el archivo JAR desde la imagen de compilación
COPY --from=build /app/build/libs/*.jar app.jar

# Exponer el puerto para la aplicación Spring Boot
EXPOSE 8080

# Comando para ejecutar la aplicación Spring Boot
#CMD ["ls", "-la"]
CMD ["java", "-jar", "app.jar"]
