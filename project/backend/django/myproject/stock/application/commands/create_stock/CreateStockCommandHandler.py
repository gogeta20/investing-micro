from myproject.stock.application.commands.create_stock.CreateStockCommand import CreateStockCommand
from myproject.stock.application.commands.create_stock.CreateStock import CreateStock
from myproject.shared.domain.bus.command.command_handler import CommandHandler


class CreateStockCommandHandler(CommandHandler):
    def __init__(self, use_case: CreateStock):
        self.use_case = use_case

    def handle(self, command: CreateStockCommand):
        result = self.use_case.execute(command)

        # Si hay error, lanzar excepción para que el controller la maneje
        if "error" in result:
            raise ValueError(result.get("message", result["error"]))

        return result

    @classmethod
    def create(cls):
        use_case = CreateStock()
        return cls(use_case)
