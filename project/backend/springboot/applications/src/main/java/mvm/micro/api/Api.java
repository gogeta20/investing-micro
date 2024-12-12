package mvm.micro.api;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.context.annotation.ComponentScan;

@SpringBootApplication
@ComponentScan(basePackages = {
    "mvm.micro.api",
    "mvm.micro.core.infrastructure.controller"
})
public class Api {
    public static void main(String[] args) {
        SpringApplication.run(Api.class, args);
    }
}
