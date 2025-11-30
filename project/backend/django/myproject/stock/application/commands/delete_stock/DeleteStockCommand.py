from dataclasses import dataclass
from typing import Optional
from myproject.shared.domain.bus.command.command import Command


@dataclass
class DeleteStockCommand(Command):
    id: Optional[int] = None
    symbol: Optional[str] = None
    uid: Optional[str] = None

    def __post_init__(self):
        # Validar que al menos uno de los identificadores esté presente
        if not self.id and not self.symbol and not self.uid:
            raise ValueError("Debe proporcionar al menos uno de los siguientes: id, symbol o uid")

        # Validar que id sea un entero positivo si se proporciona
        if self.id is not None:
            if not isinstance(self.id, int) or self.id <= 0:
                raise ValueError("id debe ser un entero positivo")

        # Validar que symbol sea una cadena no vacía si se proporciona
        if self.symbol is not None:
            if not isinstance(self.symbol, str) or not self.symbol.strip():
                raise ValueError("symbol debe ser una cadena no vacía")

        # Validar que uid sea una cadena no vacía si se proporciona
        if self.uid is not None:
            if not isinstance(self.uid, str) or not self.uid.strip():
                raise ValueError("uid debe ser una cadena no vacía")
