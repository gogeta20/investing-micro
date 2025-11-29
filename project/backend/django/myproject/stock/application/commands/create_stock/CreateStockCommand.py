from dataclasses import dataclass
from typing import Optional
from myproject.shared.domain.bus.command.command import Command


@dataclass
class CreateStockCommand(Command):
    symbol: str
    name: str
    sector: Optional[str] = None
    currency: str = "USD"

    def __post_init__(self):
        if not isinstance(self.symbol, str) or not self.symbol.strip():
            raise ValueError("symbol debe ser una cadena no vacía")

        if not isinstance(self.name, str) or not self.name.strip():
            raise ValueError("name debe ser una cadena no vacía")

        if self.sector is not None and (not isinstance(self.sector, str) or not self.sector.strip()):
            raise ValueError("sector debe ser una cadena no vacía o None")

        if not isinstance(self.currency, str) or len(self.currency) != 3:
            raise ValueError("currency debe ser un código de 3 caracteres (ej: USD)")
