FROM gradle:8.4-jdk17 AS build

WORKDIR /app
COPY project/backend/springboot/build.gradle .
COPY project/backend/springboot/settings.gradle .
RUN gradle dependencies --no-daemon || true
COPY project/backend/springboot/ .
RUN gradle clean build -x test --no-daemon

FROM openjdk:17-slim
WORKDIR /app
COPY --from=build /app/applications/build/libs/*.jar app.jar
EXPOSE 8080
CMD ["java", "-jar", "app.jar"]
