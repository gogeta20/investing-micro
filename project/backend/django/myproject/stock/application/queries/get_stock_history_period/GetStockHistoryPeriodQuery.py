from dataclasses import dataclass
from myproject.shared.domain.bus.query.query import Query


@dataclass
class GetStockHistoryPeriodQuery(Query):
    symbol: str
    period: str = "1mo"
