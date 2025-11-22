from myproject.stock.application.commands.create_portfolio.CreatePortfolioCommand import CreatePortfolioCommand
from myproject.stock.application.commands.create_portfolio.CreatePortfolio import CreatePortfolio
from myproject.shared.domain.bus.command.command_handler import CommandHandler


class CreatePortfolioCommandHandler(CommandHandler):
    def __init__(self, use_case: CreatePortfolio):
        self.use_case = use_case

    def handle(self, command: CreatePortfolioCommand):
        result = self.use_case.execute(command)

        # Si hay error, lanzar excepción para que el controller la maneje
        if "error" in result:
            raise ValueError(result.get("message", result["error"]))

        return result

    @classmethod
    def create(cls):
        use_case = CreatePortfolio()
        return cls(use_case)
