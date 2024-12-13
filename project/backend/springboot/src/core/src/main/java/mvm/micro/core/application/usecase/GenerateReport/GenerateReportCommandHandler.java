package mvm.micro.core.application.usecase.GenerateReport;

import mvm.micro.shared.domain.bus.command.CommandHandler;
import mvm.micro.shared.infrastructure.BaseResponse;
import org.springframework.stereotype.Service;
import java.util.HashMap;
import java.util.Map;

@Service("GenerateReportCommandHandler")
public class GenerateReportCommandHandler implements CommandHandler<GenerateReportCommand> {
    private final GenerateReport generateReport;

    public GenerateReportCommandHandler(GenerateReport generateReport) {
        this.generateReport = generateReport;
    }

    @Override
    public void handle(GenerateReportCommand command) {
        try {
            generateReport.process(command);
        } catch (Exception e) {
            throw new RuntimeException("Error in command handler: " + e.getMessage());
        }
    }
}
