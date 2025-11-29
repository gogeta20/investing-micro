from django.http import JsonResponse
from myproject.stock.application.commands.create_stock.CreateStockCommand import CreateStockCommand
from myproject.shared.infrastructure.bus.command_bus import get_command_bus
from myproject.shared.infrastructure.controller.api_controller import ApiController


class CreateStockController(ApiController):
    def __init__(self):
        cb = get_command_bus()
        super().__init__(command_bus=cb)

    def post(self, request):
        data = request.data

        # Validar campos requeridos
        if "symbol" not in data:
            return JsonResponse({
                "error": "symbol es requerido",
                "message": "Debes proporcionar un símbolo para la acción"
            }, status=400)

        if "name" not in data:
            return JsonResponse({
                "error": "name es requerido",
                "message": "Debes proporcionar un nombre para la acción"
            }, status=400)

        try:
            command = CreateStockCommand(
                symbol=data.get("symbol"),
                name=data.get("name"),
                sector=data.get("sector"),
                currency=data.get("currency", "USD")
            )

            result = self.dispatch_command(command)

            # Si dispatch_command retorna un JsonResponse (por excepción), retornarlo directamente
            if isinstance(result, JsonResponse):
                return result

            # Si retorna el resultado del handler, crear JsonResponse
            return JsonResponse(result, status=201)

        except ValueError as e:
            return JsonResponse({
                "error": str(e),
                "message": "Error de validación"
            }, status=400)
        except Exception as e:
            return JsonResponse({
                "error": "Error al crear la acción",
                "message": str(e)
            }, status=500)

    def register_exceptions(self) -> dict:
        return {
            ValueError: 400,
            Exception: 500,
        }
