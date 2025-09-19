from pymongo import MongoClient
from django.conf import settings

class MongoRepository:
    pass
    # def __init__(self):
    #     self.client = MongoClient(
    #         host=settings.MONGO_DB_SETTINGS['HOST'],
    #         port=settings.MONGO_DB_SETTINGS['PORT'],
    #         username=settings.MONGO_DB_SETTINGS['USERNAME'],
    #         password=settings.MONGO_DB_SETTINGS['PASSWORD']
    #     )
    #     self.db = self.client[settings.MONGO_DB_SETTINGS['DB_NAME']]
    #
    # def get_collection(self, collection_name):
    #     return self.db[collection_name]
    #
    # def insert_pokemon(self, pokemon):
    #     collection = self.db["pokemon"]
    #     collection.insert_one(pokemon)
    #
    # def insert_data(self, collection_name, data):
    #     collection = self.db[collection_name]
    #     collection.insert_one(data)
    #
    # def close_connection(self):
    #     self.client.close()
