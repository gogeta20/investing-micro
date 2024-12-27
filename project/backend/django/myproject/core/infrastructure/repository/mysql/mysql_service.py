import pymysql
from django.conf import settings
from pymysql.cursors import DictCursor

class MySQLService:
    def __init__(self):
        self.connection = pymysql.connect(
            host=settings.DATABASES['default']['HOST'],
            port=settings.DATABASES['default']['PORT'],
            user=settings.DATABASES['default']['USER'],
            password=settings.DATABASES['default']['PASSWORD'],
            db=settings.DATABASES['default']['NAME'],
            charset='utf8mb4',
            cursorclass=DictCursor
        )
        self.cursor = self.connection.cursor()

    def fetch_all_pokemon(self):
        with self.connection.cursor() as cursor:
            cursor.execute("SELECT * FROM pokemon")
            return cursor.fetchall()

    def execute_query(self, query):
        self.cursor.execute(query)
        return self.cursor.fetchall()

    def list_views(self):
        query = """
        SELECT TABLE_NAME
        FROM information_schema.TABLES
        WHERE TABLE_SCHEMA = %s AND TABLE_TYPE = 'VIEW' AND TABLE_NAME LIKE %s
        """
        with self.connection.cursor() as cursor:
            cursor.execute(query, (settings.DATABASES['default']['NAME'], '%_view'))
            return [row['TABLE_NAME'] for row in cursor.fetchall()]

    def close_connection(self):
        self.connection.close()
