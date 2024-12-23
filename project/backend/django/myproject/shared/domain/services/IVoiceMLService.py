from abc import ABC, abstractmethod

class IVoiceMLService(ABC):
    @abstractmethod
    def predict(self, text: str):
        """
        Predice la intención a partir de un texto.
        :param text: Texto de entrada.
        :return: Una tupla con la intención y la confianza.
        """
        pass
