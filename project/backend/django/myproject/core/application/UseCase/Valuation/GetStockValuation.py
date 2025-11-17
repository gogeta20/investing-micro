import json


class GetStockValuation:
    def __init__(self, mysql_service):
        self.mysql_service = mysql_service

    def execute(self, query):
        # 1) Buscar stock por symbol
        stock_sql = """
            SELECT id, symbol, name, sector, currency
            FROM stocks
            WHERE symbol = %s
            LIMIT 1
        """
        stock_rows = self.mysql_service.execute_query_params(stock_sql, (query.symbol,))

        if not stock_rows:
            return {"error": f"Stock {query.symbol} not found"}

        stock = stock_rows[0]
        stock_id = stock["id"]

        # 2) Buscar última valoración
        valuation_sql = """
            SELECT method,
                   intrinsic_value,
                   price_at_valuation,
                   discount_percent,
                   inputs_json,
                   created_at
            FROM valuations
            WHERE stock_id = %s
            ORDER BY created_at DESC
            LIMIT 1
        """
        valuation_rows = self.mysql_service.execute_query_params(valuation_sql, (stock_id,))

        # Si no hay valoración, devolvemos info básica
        if not valuation_rows:
            return {
                "symbol": stock["symbol"],
                "name": stock["name"],
                "sector": stock["sector"],
                "currency": stock["currency"],
                "valuation": None,
                "message": "No valuation found for this stock"
            }

        valuation = valuation_rows[0]

        # Procesar JSON de inputs
        inputs = None
        if valuation.get("inputs_json"):
            try:
                inputs = json.loads(valuation["inputs_json"])
            except Exception:
                inputs = valuation["inputs_json"]

        # ---------------------------------------------------------
        # 3) Mejoras inteligentes: status, upside, value_gap, grade
        # ---------------------------------------------------------

        intrinsic = float(valuation["intrinsic_value"])
        price_now = float(valuation["price_at_valuation"])

        # Diferencia absoluta
        value_gap = intrinsic - price_now

        # Upside (potencial de subida)
        upside_percent = (value_gap / price_now * 100) if price_now > 0 else 0

        # Estado de valoración
        if intrinsic > price_now:
            status = "undervalued"
        elif intrinsic < price_now:
            status = "overvalued"
        else:
            status = "fair_value"

        # Grado profesional (A–E)
        discount = float(valuation["discount_percent"])

        if discount >= 20:
            grade = "A"
        elif 10 <= discount < 20:
            grade = "B"
        elif -10 <= discount < 10:
            grade = "C"
        elif -20 <= discount < -10:
            grade = "D"
        else:
            grade = "E"

        # ---------------------------------------------------------
        # 4) Respuesta final estructurada
        # ---------------------------------------------------------

        return {
            "symbol": stock["symbol"],
            "name": stock["name"],
            "sector": stock["sector"],
            "currency": stock["currency"],
            "valuation": {
                "method": valuation["method"],
                "intrinsic_value": intrinsic,
                "price_at_valuation": price_now,
                "discount_percent": discount,
                "upside_percent": round(upside_percent, 2),
                "value_gap": round(value_gap, 2),
                "status": status,
                "grade": grade,
                "inputs": inputs,
                "created_at": (
                    valuation["created_at"].isoformat()
                    if hasattr(valuation["created_at"], "isoformat")
                    else valuation["created_at"]
                ),
            },
        }
