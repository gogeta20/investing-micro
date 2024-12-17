from abc import ABC, abstractmethod

class QueryHandler(ABC):
    @abstractmethod
    def handle(self, query):
        pass
