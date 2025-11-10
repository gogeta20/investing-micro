import pymysql
from django.conf import settings
from pymysql.cursors import DictCursor
from pymysql import Error as PyMySQLError

class MySQLService:
    def __init__(self):
        self.connection = None
        self.cursor = None
        self._connect()

    def _connect(self):
        """Establece la conexión a MySQL con manejo de errores"""
        try:
            self.connection = pymysql.connect(
                host=settings.DATABASES['default']['HOST'],
                port=settings.DATABASES['default']['PORT'],
                user=settings.DATABASES['default']['USER'],
                password=settings.DATABASES['default']['PASSWORD'],
                db=settings.DATABASES['default']['NAME'],
                charset='utf8mb4',
                cursorclass=DictCursor,
                autocommit=True,
                connect_timeout=10,
                read_timeout=30,
                write_timeout=30
            )
            self.cursor = self.connection.cursor()
        except PyMySQLError as e:
            error_msg = f"Error connecting to MySQL database: {str(e)}"
            print(f"[ERROR] {error_msg}")
            raise ConnectionError(error_msg) from e
        except Exception as e:
            error_msg = f"Unexpected error connecting to MySQL: {str(e)}"
            print(f"[ERROR] {error_msg}")
            raise ConnectionError(error_msg) from e

    def _ensure_connection(self):
        """Verifica y reconecta si la conexión está cerrada"""
        try:
            if self.connection is None:
                self._connect()
            else:
                # Intentar hacer un ping para verificar que la conexión está viva
                self.connection.ping(reconnect=False)
        except (PyMySQLError, AttributeError, Exception):
            # Si el ping falla o hay cualquier error, reconectar
            self._connect()

    def fetch_all_pokemon(self):
        with self.connection.cursor() as cursor:
            cursor.execute("SELECT * FROM pokemon")
            return cursor.fetchall()

    def execute_query(self, query):
        """Ejecuta una query sin parámetros (usar con precaución)"""
        self._ensure_connection()
        try:
            self.cursor.execute(query)
            return self.cursor.fetchall()
        except (PyMySQLError, AttributeError) as e:
            # Si hay error, intentar reconectar y reintentar una vez
            print(f"[WARNING] Query error, attempting reconnect: {str(e)}")
            self._connect()
            self.cursor.execute(query)
            return self.cursor.fetchall()

    def execute_query_params(self, query, params=None):
        """Ejecuta SELECT o INSERT/UPDATE/DELETE con parámetros seguros"""
        self._ensure_connection()
        try:
            with self.connection.cursor() as cursor:
                cursor.execute(query, params or ())
                # Si es SELECT → devuelve datos
                if query.strip().lower().startswith("select"):
                    return cursor.fetchall()
                else:
                    return cursor.lastrowid
        except (PyMySQLError, AttributeError) as e:
            # Si hay error, intentar reconectar y reintentar una vez
            print(f"[WARNING] Query error, attempting reconnect: {str(e)}")
            self._connect()
            with self.connection.cursor() as cursor:
                cursor.execute(query, params or ())
                if query.strip().lower().startswith("select"):
                    return cursor.fetchall()
                else:
                    return cursor.lastrowid

    def fetch_one(self, query, params=None):
        """Ejecuta una query y devuelve un solo resultado"""
        self._ensure_connection()
        try:
            with self.connection.cursor() as cursor:
                cursor.execute(query, params or ())
                return cursor.fetchone()
        except (PyMySQLError, AttributeError) as e:
            # Si hay error, intentar reconectar y reintentar una vez
            print(f"[WARNING] Query error, attempting reconnect: {str(e)}")
            self._connect()
            with self.connection.cursor() as cursor:
                cursor.execute(query, params or ())
                return cursor.fetchone()

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
