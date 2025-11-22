from typing import Optional
from datetime import datetime
from myproject.stock.application.commands.create_portfolio.CreatePortfolioCommand import CreatePortfolioCommand
from myproject.core.infrastructure.repository.mysql.mysql_service import MySQLService


class CreatePortfolio:
    def __init__(self):
        self.mysql_service = MySQLService()

    def execute(self, command: CreatePortfolioCommand):
        try:
            stock_ids = [stock.id for stock in command.stocks]
            stock_symbols = [stock.symbol for stock in command.stocks]

            # Verificar que los IDs y símbolos existan en la base de datos
            placeholders = ','.join(['%s'] * len(stock_ids))
            validation_sql = f"""
                SELECT id, symbol FROM stocks
                WHERE id IN ({placeholders}) AND symbol IN ({','.join(['%s'] * len(stock_symbols))})
            """
            params = tuple(stock_ids + stock_symbols)
            existing_stocks = self.mysql_service.execute_query_params(validation_sql, params)

            if len(existing_stocks) != len(stock_ids):
                return {
                    "error": "Algunos stocks no existen o los IDs/símbolos no coinciden",
                    "message": "Verifica que todos los IDs y símbolos sean válidos"
                }

            # Crear el portafolio
            portfolio_name = command.name if command.name and command.name.strip() else None
            created_at = datetime.utcnow().strftime("%Y-%m-%d %H:%M:%S")

            insert_portfolio_sql = """
                INSERT INTO portfolios (uid,name,created_at)
                VALUES (UUID(),%s, %s)
            """
            # execute_query_params devuelve lastrowid para INSERT
            portfolio_id = self.mysql_service.execute_query_params(insert_portfolio_sql, (portfolio_name, created_at))

            # Si no se obtuvo el ID (puede ser 0 o None), intentar obtenerlo de otra manera
            if not portfolio_id or portfolio_id == 0:
                portfolio_result = self.mysql_service.fetch_one(
                    "SELECT id FROM portfolios WHERE created_at = %s ORDER BY id DESC LIMIT 1",
                    (created_at,)
                )
                if portfolio_result:
                    portfolio_id = portfolio_result["id"]
                else:
                    return {
                        "error": "Error al crear el portafolio",
                        "message": "No se pudo obtener el ID del portafolio creado"
                    }

            # Crear las relaciones portfolio_stocks
            for stock in command.stocks:
                # Verificar si ya existe la relación (evitar duplicados)
                check_sql = """
                    SELECT portfolio_id FROM portfolio_stocks
                    WHERE portfolio_id = %s AND stock_id = %s
                """
                existing = self.mysql_service.fetch_one(check_sql, (portfolio_id, stock.id))

                if not existing:
                    insert_relation_sql = """
                        INSERT INTO portfolio_stocks (portfolio_id, stock_id)
                        VALUES (%s, %s)
                    """
                    self.mysql_service.execute_query_params(insert_relation_sql, (portfolio_id, stock.id))

            # Preparar la respuesta
            stocks_response = [
                {"id": stock.id, "symbol": stock.symbol}
                for stock in command.stocks
            ]

            return {
                "portfolio_id": portfolio_id,
                "message": "Portafolio creado exitosamente",
                "stocks": stocks_response
            }

        except ValueError as e:
            return {
                "error": str(e),
                "message": "Error de validación"
            }
        except Exception as e:
            print(f"[ERROR] CreatePortfolio failed: {str(e)}")
            return {
                "error": "Error al crear el portafolio",
                "message": str(e)
            }
