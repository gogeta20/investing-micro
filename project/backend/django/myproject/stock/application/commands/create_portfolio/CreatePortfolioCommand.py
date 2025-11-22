from dataclasses import dataclass
from typing import List, Optional
from myproject.shared.domain.bus.command.command import Command


@dataclass
class StockItem:
    id: int
    symbol: str


@dataclass
class CreatePortfolioCommand(Command):
    name: Optional[str] = None
    stocks: List[StockItem] = None

    def __post_init__(self):
        if not self.stocks or len(self.stocks) == 0:
            raise ValueError("stocks debe ser un array con al menos un elemento")

        for stock in self.stocks:
            if not isinstance(stock, StockItem):
                raise ValueError("Cada elemento en stocks debe tener id y symbol")
            if not isinstance(stock.id, int) or stock.id <= 0:
                raise ValueError("id debe ser un número válido mayor que 0")
            if not isinstance(stock.symbol, str) or not stock.symbol.strip():
                raise ValueError("symbol debe ser una cadena no vacía")
