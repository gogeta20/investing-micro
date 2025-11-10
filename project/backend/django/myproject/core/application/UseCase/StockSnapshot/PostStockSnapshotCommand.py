from dataclasses import dataclass
from myproject.shared.domain.bus.command.command import Command

@dataclass
class PostStockSnapshotCommand(Command):
    symbol: str
    price: float
    recorded_at: str
