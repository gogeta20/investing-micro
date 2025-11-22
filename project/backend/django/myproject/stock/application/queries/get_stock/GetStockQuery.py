from dataclasses import dataclass
from myproject.shared.domain.bus.query.query import Query


@dataclass
class GetStockQuery(Query):
    def __post_init__(self):
        pass
