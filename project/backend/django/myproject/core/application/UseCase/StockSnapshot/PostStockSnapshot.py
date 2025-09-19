from myproject.core.application.UseCase.StockSnapshot import PostStockSnapshotCommand


class PostStockSnapshot:
    def __init__(self, mysql_service):
        self.mysql_service = mysql_service

    def execute(self, command: PostStockSnapshotCommand):
        try:
            stock = self.mysql_service.fetch_one("SELECT id FROM stocks WHERE symbol = %s", (command.symbol,))
            if not stock:
                return {"error": f"Stock {command.symbol} no encontrado"}

            stock_id = stock["id"]

            # Insertar snapshot
            self.mysql_service.execute_query_params(
                "INSERT INTO stock_prices (uid, stock_id, price, recorded_at) VALUES (UUID(), %s, %s, %s)",
                (stock_id, command.price, command.recorded_at)
            )
            return {
                "result": {
                    "symbol": command.symbol,
                    "price": command.price,
                    "recorded_at": command.recorded_at,
                    "state": "inserted"
                }
            }

        except Exception as e:
            print(f"[ERROR] PostStockSnapshot failed: {str(e)}")
            return {"error": str(e)}
