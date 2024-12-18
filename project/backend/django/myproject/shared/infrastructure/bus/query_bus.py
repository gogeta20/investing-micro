from typing import Any
from myproject.shared.domain.bus.query.query import Query

class QueryBus:
    def __init__(self):
        self._handlers = {}

    def register(self, query_class, handler):
        self._handlers[query_class] = handler

    def ask(self, query: Query):
        handler = self._handlers.get(type(query))
        if not handler:
            raise Exception(f"Ask No handler for {type(query)}")
        return handler.handle(query)
