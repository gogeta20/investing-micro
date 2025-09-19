class GetStockBySymbol:
    def __init__(self,mysql_service):
        self.mysql_service = mysql_service

    def execute(self, name):
        data = self.mysql_service.execute_query(f"SELECT * FROM stocks where symbol = '{name}'")
        print(f"[DEBUG] in use case {data}")
        return {"result": data}
