from dataclasses import dataclass
from myproject.shared.domain.bus.query.query import Query


@dataclass
class GetStockHistoryQuery(Query):
    symbol: str
    from_date: str = None
    to_date: str = None
