import yfinance as yf
from myproject.stock.application.queries.search_stock_by_symbol.SearchStockBySymbolQuery import SearchStockBySymbolQuery


class SearchStockBySymbol:
    def __init__(self):
        pass

    def execute(self, query: SearchStockBySymbolQuery):
        try:
            ticker = yf.Ticker(query.symbol)
            info = ticker.info

            # Extraer información relevante
            symbol = info.get("symbol", query.symbol.upper())
            name = info.get("longName") or info.get("shortName") or info.get("name", "")
            sector = info.get("sector") or None
            currency = info.get("currency", "USD")

            # Validar que tenemos al menos el nombre
            if not name:
                return {
                    "error": f"No se pudo obtener información para el símbolo {query.symbol}",
                    "message": "El símbolo puede no existir o no estar disponible"
                }

            return {
                "symbol": symbol,
                "name": name,
                "sector": sector,
                "currency": currency,
                "available": True
            }

        except Exception as e:
            return {
                "error": f"Error al buscar información para {query.symbol}",
                "message": str(e),
                "available": False
            }
