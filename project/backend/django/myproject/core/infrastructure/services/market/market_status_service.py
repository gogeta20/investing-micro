import os
import requests


class MarketStatusService:
    def __init__(self):
        self.api_key = os.getenv("ALPHA_VANTAGE_TOKEN")

        # Manejo seguro si la API key NO está configurada
        if not self.api_key:
            print("[WARN] ALPHA_VANTAGE_TOKEN is missing. Market will be treated as CLOSED.")
            self.api_key = None

        if self.api_key:
            self.url = (
                "https://www.alphavantage.co/query?function=MARKET_STATUS&apikey="
                + self.api_key
            )
        else:
            self.url = None  # No llamar a la API sin key

    def is_market_open(self) -> bool:
        """Devuelve True si el mercado está abierto. False si no hay API key o falla la petición."""

        # Si no tenemos API key → tratamos mercado como CERRADO
        if not self.api_key:
            return False

        try:
            response = requests.get(self.url, timeout=5)
            data = response.json()

            if "markets" not in data:
                return False

            for market in data["markets"]:
                if market.get("region") == "United States":
                    return market.get("current_status") == "open"

            return False

        except Exception as e:
            print(f"[ERROR] MarketStatusService error: {str(e)}")
            return False  # fallback seguro

    def get_raw(self):
        if not self.api_key:
            return {"error": "ALPHA_VANTAGE_TOKEN not configured"}

        try:
            return requests.get(self.url, timeout=5).json()
        except:
            return {}
