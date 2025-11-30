from django.http import JsonResponse
from myproject.stock.application.commands.delete_stock.DeleteStockCommand import DeleteStockCommand
from myproject.shared.infrastructure.bus.command_bus import get_command_bus
from myproject.shared.infrastructure.controller.api_controller import ApiController


class DeleteStockController(ApiController):
    def __init__(self):
        cb = get_command_bus()
        super().__init__(command_bus=cb)

    def delete(self, request):
        data = request.data

        # Extraer los identificadores del body
        stock_id = data.get("id")
        symbol = data.get("symbol")
        uid = data.get("uid")

        # Validar que al menos uno esté presente
        if not stock_id and not symbol and not uid:
            return JsonResponse({
                "error": "Identificador requerido",
                "message": "Debes proporcionar al menos uno de los siguientes: id, symbol o uid"
            }, status=400)

        try:
            command = DeleteStockCommand(
                id=stock_id,
                symbol=symbol,
                uid=uid
            )

            result = self.dispatch_command(command)

            # Si dispatch_command retorna un JsonResponse (por excepción), retornarlo directamente
            if isinstance(result, JsonResponse):
                return result

            # Si retorna el resultado del handler, crear JsonResponse
            return JsonResponse(result, status=200)

        except ValueError as e:
            return JsonResponse({
                "error": str(e),
                "message": "Error de validación"
            }, status=400)
        except Exception as e:
            return JsonResponse({
                "error": "Error al eliminar la acción",
                "message": str(e)
            }, status=500)

    def register_exceptions(self) -> dict:
        return {
            ValueError: 400,
            Exception: 500,
        }
