from dataclasses import dataclass
from myproject.shared.domain.bus.query.query import Query


@dataclass
class GetStocksOverviewQuery(Query):
    portfolio_id: int = None
