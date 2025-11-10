from dataclasses import dataclass

from myproject.shared.domain.bus.query.query import Query
from typing import Optional

@dataclass
class GetResultsPortafolioQuery(Query):
    portfolio_id: int

    def get_id_portafolio(self) -> int:
        return self.portfolio_id
