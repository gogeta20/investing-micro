from django.http import JsonResponse
from myproject.stock.application.commands.create_portfolio.CreatePortfolioCommand import CreatePortfolioCommand, StockItem
from myproject.shared.infrastructure.bus.command_bus import get_command_bus
from myproject.shared.infrastructure.controller.api_controller import ApiController


class CreatePortfolioController(ApiController):
    def __init__(self):
        cb = get_command_bus()
        super().__init__(command_bus=cb)

    def post(self, request):
        data = request.data

        # Validar que stocks esté presente
        if "stocks" not in data:
            return JsonResponse({
                "error": "stocks es requerido",
                "message": "Debes proporcionar un array de stocks"
            }, status=400)

        # Convertir los stocks a objetos StockItem
        stocks = []
        for stock_data in data.get("stocks", []):
            if "id" not in stock_data or "symbol" not in stock_data:
                return JsonResponse({
                    "error": "Cada stock debe tener id y symbol",
                    "message": "Formato inválido en el array de stocks"
                }, status=400)
            stocks.append(StockItem(
                id=stock_data["id"],
                symbol=stock_data["symbol"]
            ))

        try:
            command = CreatePortfolioCommand(
                name=data.get("name"),
                stocks=stocks
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
                "error": "Error al crear el portafolio",
                "message": str(e)
            }, status=500)

    def register_exceptions(self) -> dict:
        return {
            ValueError: 400,
            Exception: 500,
        }
