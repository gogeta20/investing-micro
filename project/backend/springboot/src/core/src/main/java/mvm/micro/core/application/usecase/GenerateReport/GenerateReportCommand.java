package mvm.micro.core.application.usecase.GenerateReport;

import mvm.micro.shared.domain.bus.command.Command;

public class GenerateReportCommand implements Command {
    private final String reportType;  // por ejemplo "pokemon"
    private final String format;      // por ejemplo "csv"

    public GenerateReportCommand(String reportType, String format) {
        this.reportType = reportType;
        this.format = format;
    }

    public String getReportType() {
        return reportType;
    }

    public String getFormat() {
        return format;
    }
}
