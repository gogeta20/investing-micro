class SyncDatabase:
    def __init__(self, mysql_service, mongo_service):
        self.mysql_service = mysql_service
        self.mongo_service = mongo_service

    def execute(self):
        try:
            views = self.mysql_service.list_views()
            for view in views:
                data = self.mysql_service.execute_query(f"SELECT * FROM {view}")
                for record in data:
                    self.mongo_service.insert_data(view, record)

            return {"result": "sync completed"}
        except Exception as e:
            print(f"Error syncing databases: {str(e)}")
            return {"result": {str(e)}}



    # def execute(self):
    #     try:
    #         pokemon_list = self.mysql_service.execute_query("SELECT * FROM pokemon")
    #         print(pokemon_list)
    #         for pokemon in pokemon_list:
    #             self.mongo_service.insert_pokemon(pokemon)
    #         return {"result": "sync completed"}
    #     except Exception as e:
    #         print(f"Error syncing databases: {str(e)}")
    #         return {"result": {str(e)}}
