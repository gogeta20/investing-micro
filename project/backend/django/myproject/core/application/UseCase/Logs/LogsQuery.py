from dataclasses import dataclass

from myproject.shared.domain.bus.query.query import Query
from typing import Optional

@dataclass
class LogsQuery(Query):
    text: str

    def __get_init__(self):
        if not isinstance(self.text, str):
            raise ValueError("text must be a string")
        if not self.text.strip():
            raise ValueError("text cannot be empty")

    def get_text(self) -> str:
        return self.text

