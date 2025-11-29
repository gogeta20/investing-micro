from dataclasses import dataclass
from myproject.shared.domain.bus.query.query import Query


@dataclass
class SearchStockBySymbolQuery(Query):
    symbol: str

    def __post_init__(self):
        if not isinstance(self.symbol, str):
            raise ValueError("symbol must be a string")
        if not self.symbol.strip():
            raise ValueError("symbol cannot be empty")
