class GetStock:
    def __init__(self, mysql_service):
        self.mysql_service = mysql_service

    def execute(self):
        data = self.mysql_service.execute_query(f"SELECT * FROM stocks")
        return {"result": data}
