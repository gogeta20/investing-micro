class GetStockBySymbol:
    def __init__(self,mysql_service):
        self.mysql_service = mysql_service

    def execute(self, name):
        # Usar parámetros para evitar SQL injection
        data = self.mysql_service.execute_query_params(
            "SELECT * FROM stocks WHERE symbol = %s",
            (name,)
        )
        print(f"[DEBUG] in use case {data}")
        return {"result": data}
