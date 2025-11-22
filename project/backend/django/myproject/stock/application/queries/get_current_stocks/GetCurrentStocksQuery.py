from dataclasses import dataclass
from myproject.shared.domain.bus.query.query import Query


@dataclass
class GetCurrentStocksQuery(Query):
    portfolio_id: int = None
