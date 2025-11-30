from myproject.stock.application.commands.delete_stock.DeleteStockCommand import DeleteStockCommand
from myproject.stock.application.commands.delete_stock.DeleteStock import DeleteStock
from myproject.shared.domain.bus.command.command_handler import CommandHandler


class DeleteStockCommandHandler(CommandHandler):
    def __init__(self, use_case: DeleteStock):
        self.use_case = use_case

    def handle(self, command: DeleteStockCommand):
        result = self.use_case.execute(command)

        # Si hay error, lanzar excepción para que el controller la maneje
        if "error" in result:
            raise ValueError(result.get("message", result["error"]))

        return result

    @classmethod
    def create(cls):
        use_case = DeleteStock()
        return cls(use_case)
