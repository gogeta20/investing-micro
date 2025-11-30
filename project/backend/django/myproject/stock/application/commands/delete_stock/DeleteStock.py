from typing import Optional
from myproject.stock.application.commands.delete_stock.DeleteStockCommand import DeleteStockCommand
from myproject.core.infrastructure.repository.mysql.mysql_service import MySQLService


class DeleteStock:
    def __init__(self, mysql_service: Optional[MySQLService] = None):
        self.mysql_service = mysql_service if mysql_service is not None else MySQLService()

    def execute(self, command: DeleteStockCommand):
        try:
            # Primero, buscar el stock a eliminar para verificar que existe
            stock = None
            where_clause = ""
            params = None

            if command.id:
                where_clause = "WHERE id = %s"
                params = (command.id,)
            elif command.symbol:
                where_clause = "WHERE symbol = %s"
                params = (command.symbol.upper(),)
            elif command.uid:
                where_clause = "WHERE uid = %s"
                params = (command.uid,)

            # Buscar el stock antes de eliminarlo
            stock = self.mysql_service.fetch_one(
                f"SELECT id, uid, symbol, name, sector, currency, created_at FROM stocks {where_clause}",
                params
            )

            if not stock:
                identifier = command.id or command.symbol or command.uid
                return {
                    "error": "Acción no encontrada",
                    "message": f"No se encontró una acción con el identificador proporcionado: {identifier}"
                }

            # Guardar información del stock antes de eliminarlo para retornarla
            deleted_stock = {
                "id": stock["id"],
                "uid": stock["uid"],
                "symbol": stock["symbol"],
                "name": stock["name"],
                "sector": stock["sector"],
                "currency": stock["currency"],
                "created_at": str(stock["created_at"]) if stock["created_at"] else None
            }

            # Eliminar el stock
            delete_sql = f"DELETE FROM stocks {where_clause}"
            self.mysql_service.execute_query_params(delete_sql, params)

            return {
                "message": "Acción eliminada exitosamente",
                "deleted_stock": deleted_stock
            }

        except ValueError as e:
            return {
                "error": "Error de validación",
                "message": str(e)
            }
        except Exception as e:
            print(f"[ERROR] DeleteStock failed: {str(e)}")
            return {
                "error": "Error al eliminar la acción",
                "message": str(e)
            }
