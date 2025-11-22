# Guía para Crear un Caso de Uso en el Proyecto

Esta guía describe el patrón estándar para crear un nuevo caso de uso (query) en el proyecto Django con arquitectura VSA (Vertical Slice Architecture).

## Estructura de Archivos

Para cada caso de uso, se deben crear los siguientes archivos en la carpeta `myproject/stock/application/queries/<nombre_caso_uso>/`:

1. `<NombreCasoUso>Query.py` - Define la query (dataclass)
2. `<NombreCasoUso>.py` - Implementa la lógica del caso de uso
3. `<NombreCasoUso>QueryHandler.py` - Handler que conecta la query con el caso de uso
4. `__init__.py` - Archivo vacío para hacer el directorio un paquete Python

Adicionalmente, se debe crear:
5. `myproject/stock/infrastructure/controllers/<nombre_caso_uso>_controller.py` - Controlador HTTP
6. Actualizar `myproject/stock/application/routing/urls.py` - Registrar la ruta

## Paso 1: Crear el Query (Query Object)

**Ubicación**: `myproject/stock/application/queries/<nombre_caso_uso>/<NombreCasoUso>Query.py`

```python
from dataclasses import dataclass
from myproject.shared.domain.bus.query.query import Query
from typing import Optional


@dataclass
class NombreCasoUsoQuery(Query):
    # Parámetros requeridos
    parametro1: str
    parametro2: int

    # Parámetros opcionales (con valores por defecto)
    parametro_opcional: Optional[str] = None
    otro_parametro: str = "valor_por_defecto"
```

**Ejemplo real**:
```python
from dataclasses import dataclass
from myproject.shared.domain.bus.query.query import Query


@dataclass
class GetStockHistoryPeriodQuery(Query):
    symbol: str
    period: str = "1mo"
```

## Paso 2: Crear el Caso de Uso (Use Case)

**Ubicación**: `myproject/stock/application/queries/<nombre_caso_uso>/<NombreCasoUso>.py`

```python
from typing import Optional
from myproject.stock.application.queries.<nombre_caso_uso>.<NombreCasoUso>Query import NombreCasoUsoQuery
# Importar servicios necesarios (MySQLService, yfinance, etc.)


class NombreCasoUso:
    def __init__(self):
        # Inicializar servicios necesarios
        # self.mysql_service = MySQLService()  # Si se necesita BD
        pass

    def execute(self, query: NombreCasoUsoQuery):
        # Implementar la lógica del caso de uso
        # Acceder a los parámetros con: query.parametro1, query.parametro2, etc.

        try:
            # Lógica principal aquí
            result = {
                "data": "resultado"
            }
            return result
        except Exception as e:
            return {
                "error": str(e)
            }
```

**Ejemplo real**:
```python
import yfinance as yf
from myproject.stock.application.queries.get_stock_history_period.GetStockHistoryPeriodQuery import GetStockHistoryPeriodQuery


class GetStockHistoryPeriod:
    def __init__(self):
        pass

    def execute(self, query: GetStockHistoryPeriodQuery):
        try:
            ticker = yf.Ticker(query.symbol)
            history = ticker.history(period=query.period)
            history_reset = history.reset_index()

            result = []
            for _, row in history_reset.iterrows():
                result.append({
                    "date": row["Date"].strftime("%Y-%m-%d") if hasattr(row["Date"], "strftime") else str(row["Date"]),
                    "open": float(row["Open"]) if "Open" in row else None,
                    "high": float(row["High"]) if "High" in row else None,
                    "low": float(row["Low"]) if "Low" in row else None,
                    "close": float(row["Close"]) if "Close" in row else None,
                    "volume": int(row["Volume"]) if "Volume" in row else None,
                })

            return {
                "symbol": query.symbol,
                "period": query.period,
                "data": result
            }
        except Exception as e:
            return {
                "symbol": query.symbol,
                "period": query.period,
                "error": str(e)
            }
```

## Paso 3: Crear el Query Handler

**Ubicación**: `myproject/stock/application/queries/<nombre_caso_uso>/<NombreCasoUso>QueryHandler.py`

```python
from myproject.stock.application.queries.<nombre_caso_uso>.<NombreCasoUso> import NombreCasoUso
from myproject.stock.application.queries.<nombre_caso_uso>.<NombreCasoUso>Query import NombreCasoUsoQuery
from myproject.shared.domain.bus.query.query_handler import QueryHandler


class NombreCasoUsoQueryHandler(QueryHandler):
    def __init__(self, use_case: NombreCasoUso):
        self.use_case = use_case

    def handle(self, query: NombreCasoUsoQuery):
        return self.use_case.execute(query)

    @classmethod
    def create(cls):
        use_case = NombreCasoUso()
        return cls(use_case)
```

**Nota**: El handler se auto-registra en el QueryBus gracias al sistema de auto-discovery. No es necesario registro manual.

## Paso 4: Crear el Controller

**Ubicación**: `myproject/stock/infrastructure/controllers/<nombre_caso_uso>_controller.py`

```python
from django.http import JsonResponse
from myproject.stock.application.queries.<nombre_caso_uso>.<NombreCasoUso>Query import NombreCasoUsoQuery
from myproject.shared.infrastructure.bus.query_bus import get_query_bus
from myproject.shared.infrastructure.controller.api_controller import ApiController


class NombreCasoUsoController(ApiController):
    def __init__(self):
        qb = get_query_bus()
        super().__init__(query_bus=qb)

    def get(self, request, parametro_url=None):
        # Extraer parámetros de request.GET para query params
        parametro1 = request.GET.get("parametro1")
        parametro2 = request.GET.get("parametro2", "valor_por_defecto")

        # Crear la query con los parámetros
        query = NombreCasoUsoQuery(
            parametro1=parametro1,
            parametro2=parametro2
        )

        # Ejecutar la query a través del bus
        response = self.ask(query)
        return JsonResponse(response, safe=False)

    def register_exceptions(self) -> dict:
        return {
            ValueError: 400,
        }
```

**Ejemplo real**:
```python
from django.http import JsonResponse
from myproject.stock.application.queries.get_stock_history_period.GetStockHistoryPeriodQuery import GetStockHistoryPeriodQuery
from myproject.shared.infrastructure.bus.query_bus import get_query_bus
from myproject.shared.infrastructure.controller.api_controller import ApiController


class GetStockHistoryPeriodController(ApiController):
    def __init__(self):
        qb = get_query_bus()
        super().__init__(query_bus=qb)

    def get(self, request, symbol: str):
        period = request.GET.get("period", "1mo")
        query = GetStockHistoryPeriodQuery(symbol=symbol, period=period)
        response = self.ask(query)
        return JsonResponse(response, safe=False)

    def register_exceptions(self) -> dict:
        return {
            ValueError: 400,
        }
```

## Paso 5: Registrar la Ruta en urls.py

**Ubicación**: `myproject/stock/application/routing/urls.py`

1. **Importar el controller**:
```python
from myproject.stock.infrastructure.controllers.<nombre_caso_uso>_controller import NombreCasoUsoController
```

2. **Agregar la ruta en urlpatterns**:
```python
urlpatterns = [
    # ... otras rutas ...
    path('<str:symbol>/history', GetStockHistoryPeriodController.as_view(), name='get_stock_history_period'),
    # ... más rutas ...
]
```

**Ejemplo completo**:
```python
from django.urls import path
from myproject.stock.infrastructure.controllers.get_stock_history_period_controller import GetStockHistoryPeriodController
# ... otros imports ...

urlpatterns = [
    # ... otras rutas ...
    path('<str:symbol>/history', GetStockHistoryPeriodController.as_view(), name='get_stock_history_period'),
    # ... más rutas ...
]
```

## Paso 6: Crear __init__.py

**Ubicación**: `myproject/stock/application/queries/<nombre_caso_uso>/__init__.py`

Crear un archivo vacío para hacer el directorio un paquete Python válido.

## Convenciones de Nomenclatura

- **Query**: `<NombreCasoUso>Query` (ej: `GetStockHistoryPeriodQuery`)
- **Caso de Uso**: `<NombreCasoUso>` (ej: `GetStockHistoryPeriod`)
- **Handler**: `<NombreCasoUso>QueryHandler` (ej: `GetStockHistoryPeriodQueryHandler`)
- **Controller**: `<NombreCasoUso>Controller` (ej: `GetStockHistoryPeriodController`)
- **Archivo Controller**: `<nombre_caso_uso>_controller.py` (snake_case)

## Estructura de Directorios Final

```
myproject/stock/
├── application/
│   ├── queries/
│   │   └── <nombre_caso_uso>/
│   │       ├── __init__.py
│   │       ├── <NombreCasoUso>Query.py
│   │       ├── <NombreCasoUso>.py
│   │       └── <NombreCasoUso>QueryHandler.py
│   └── routing/
│       └── urls.py
└── infrastructure/
    └── controllers/
        └── <nombre_caso_uso>_controller.py
```

## Flujo de Ejecución

1. Cliente hace petición HTTP → `GET /api/stock/<ruta>`
2. Django routing → `Controller.get()`
3. Controller crea → `Query` con parámetros
4. Controller ejecuta → `self.ask(query)` → QueryBus
5. QueryBus encuentra → `QueryHandler` (auto-registrado)
6. QueryHandler ejecuta → `use_case.execute(query)`
7. Caso de uso retorna → resultado
8. Controller retorna → `JsonResponse(resultado)`

## Notas Importantes

- El sistema **auto-registra** los handlers, no es necesario registro manual
- Los handlers deben tener el método `create()` como classmethod
- Los controllers heredan de `ApiController` y usan `get_query_bus()`
- El método `register_exceptions()` define cómo manejar excepciones
- Las rutas se registran en `myproject/stock/application/routing/urls.py`
- La ruta base es `/api/stock/` (definida en `myproject/urls.py`)

## Variaciones Comunes

### Usar BaseResponse (opcional)
Algunos handlers usan `BaseResponse` para formatear la respuesta:
```python
from myproject.shared.domain.response import BaseResponse

def handle(self, query: NombreCasoUsoQuery):
    result = self.use_case.execute(query)
    return BaseResponse(
        data={"result": result},
        message="success request",
        status=200
    ).to_dict()
```

### Usar MySQLService
Si necesitas acceso a la base de datos:
```python
from myproject.core.infrastructure.repository.mysql.mysql_service import MySQLService

class NombreCasoUso:
    def __init__(self, mysql_service: Optional[MySQLService] = None):
        self.mysql_service = mysql_service if mysql_service is not None else MySQLService()
```

### Validación en Query
Puedes agregar validación en el Query usando `__post_init__`:
```python
@dataclass
class NombreCasoUsoQuery(Query):
    parametro: str

    def __post_init__(self):
        if not self.parametro.strip():
            raise ValueError("parametro cannot be empty")
```
