from myproject.shared.domain.bus.command.command_handler import CommandHandler
from myproject.shared.domain.bus.command.command import Command
from myproject.core.application.UseCase.HealthCheck.health_check_command import HealthCheckCommand

class HealthCheckCommandHandler(CommandHandler):
    def handle(self, command: HealthCheckCommand):
        # Aquí defines la lógica del comando
        print("HealthCheckCommand handled successfully")
        return {"status": "OK", "message": "Health check completed"}
