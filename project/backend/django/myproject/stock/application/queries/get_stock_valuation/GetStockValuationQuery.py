from dataclasses import dataclass
from myproject.shared.domain.bus.query.query import Query


@dataclass
class GetStockValuationQuery(Query):
    symbol: str
