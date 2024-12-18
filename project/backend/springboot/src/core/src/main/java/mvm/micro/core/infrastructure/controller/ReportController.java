package mvm.micro.core.infrastructure.controller;

import mvm.micro.core.application.usecase.GenerateReport.GenerateReportCommand;
import mvm.micro.shared.infrastructure.controller.ApiController;
import mvm.micro.shared.domain.bus.command.CommandBus;
import mvm.micro.shared.domain.bus.query.QueryBus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/api/reports")
public class ReportController extends ApiController {

    public ReportController(CommandBus commandBus, QueryBus queryBus) {
        super(commandBus, queryBus);
    }

    @PostMapping("/generate")
    public ResponseEntity<Object> generateReport(
        @RequestParam(defaultValue = "pokemon") String reportType,
        @RequestParam(defaultValue = "csv") String format) {
        try {
            dispatch(new GenerateReportCommand(reportType, format));
            return ResponseEntity.accepted().body("Report generation started");
        } catch (Exception e) {
            return ResponseEntity.status(500).body(e.getMessage());
        }
    }

    @Override
    protected void registerExceptions() {
        // Registro de excepciones
    }
}
