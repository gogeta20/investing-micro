package mvm.micro.core.infrastructure.controller;

import mvm.micro.core.application.usecase.HealthCheck.HealthCheckQuery;
import mvm.micro.shared.infrastructure.BaseResponse;
import mvm.micro.shared.infrastructure.controller.ApiController;
import mvm.micro.shared.domain.bus.command.CommandBus;
import mvm.micro.shared.domain.bus.query.QueryBus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

@RestController
@RequestMapping("/api/health")
public class HealthCheckController extends ApiController {

    public HealthCheckController(CommandBus commandBus, QueryBus queryBus) {
        super(commandBus, queryBus);
    }

    @GetMapping
    public ResponseEntity<Object> healthCheck() {
        try {
            BaseResponse response = ask(new HealthCheckQuery());
            return ResponseEntity.ok(response.getData());
        } catch (Exception e) {
            return ResponseEntity.status(500).body(e.getMessage());
        }
    }

    @Override
    protected void registerExceptions() {
        // Registro de excepciones similar a Symfony
    }
}
