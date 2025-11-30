from typing import Optional
from myproject.stock.application.commands.create_stock.CreateStockCommand import CreateStockCommand
from myproject.core.infrastructure.repository.mysql.mysql_service import MySQLService


class CreateStock:
    def __init__(self, mysql_service: Optional[MySQLService] = None):
        self.mysql_service = mysql_service if mysql_service is not None else MySQLService()

    def execute(self, command: CreateStockCommand):
        try:
            # Verificar que el símbolo no exista ya en la BD
            existing_stock = self.mysql_service.fetch_one(
                "SELECT id, symbol FROM stocks WHERE symbol = %s",
                (command.symbol.upper(),)
            )

            if existing_stock:
                return {
                    "error": f"La acción con símbolo {command.symbol.upper()} ya existe",
                    "message": f"Ya existe una acción con el símbolo {command.symbol.upper()} en la base de datos",
                    "existing_stock_id": existing_stock["id"]
                }

            # Insertar la nueva acción
            # El campo uid se genera automáticamente con UUID() en MySQL
            # El campo created_at se genera automáticamente con CURRENT_TIMESTAMP
            insert_sql = """
                INSERT INTO stocks (uid, symbol, name, sector, currency)
                VALUES (UUID(), %s, %s, %s, %s)
            """

            # Normalizar el símbolo a mayúsculas
            symbol_upper = command.symbol.upper()

            # Ejecutar la inserción
            self.mysql_service.execute_query_params(
                insert_sql,
                (symbol_upper, command.name, command.sector, command.currency)
            )

            # Obtener el stock recién creado para retornarlo
            new_stock = self.mysql_service.fetch_one(
                "SELECT id, uid, symbol, name, sector, currency, created_at FROM stocks WHERE symbol = %s",
                (symbol_upper,)
            )

            if not new_stock:
                return {
                    "error": "Error al crear la acción",
                    "message": "La acción se insertó pero no se pudo recuperar"
                }

            return {
                "message": "Acción creada exitosamente",
                "stock": {
                    "id": new_stock["id"],
                    "uid": new_stock["uid"],
                    "symbol": new_stock["symbol"],
                    "name": new_stock["name"],
                    "sector": new_stock["sector"],
                    "currency": new_stock["currency"],
                    "created_at": str(new_stock["created_at"]) if new_stock["created_at"] else None
                }
            }

        except ValueError as e:
            return {
                "error": "Error de validación",
                "message": str(e)
            }
        except Exception as e:
            print(f"[ERROR] CreateStock failed: {str(e)}")
            return {
                "error": "Error al crear la acción",
                "message": str(e)
            }
