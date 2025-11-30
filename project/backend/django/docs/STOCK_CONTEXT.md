# Contexto del Desarrollo - Contexto Stock

## 🎯 Objetivo Principal

Migrar el contexto de **Stock** a una arquitectura **Vertical Slice Architecture (VSA)** siguiendo principios de **Clean Architecture** y **CQRS**, mejorando la organización del código y simplificando los patrones actuales.

## 📋 Estado Actual

### ✅ Completado

1. **Migración a VSA del contexto Stock**
   - Todos los casos de uso relacionados con Stock están organizados en `myproject/stock/`
   - Estructura Vertical Slice implementada: cada caso de uso tiene su propia carpeta con Query, UseCase, Handler

2. **Simplificación de Controladores**
   - Todos los controladores GET actualizados a formato minimalista
   - Eliminado registro manual de handlers (ahora es auto-registro)
   - Removidos try-except redundantes
   - Mapeo de excepciones mejorado (ValueError → 400)

3. **Simplificación de Handlers**
   - Los handlers ya no conocen directamente `MySQLService`
   - Los UseCases crean sus propias dependencias internamente si no se pasan
   - Handlers simplificados: solo reciben el use_case y delegan

4. **QueryBus con Auto-registro**
   - Instancia compartida del QueryBus (singleton pattern)
   - Auto-descubrimiento de handlers por inspección de tipos
   - Handler loader busca en ambos contextos: `core` y `stock`

5. **Router específico del contexto**
   - Router creado en `stock/routing/urls.py`
   - Incluido en el router principal de Django

## 🏗️ Estructura del Contexto Stock (VSA)

```
myproject/
└── stock/                                    # 🆕 Contexto Stock (VSA)
    ├── application/
    │   ├── queries/                         # Casos de uso tipo Query
    │   │   ├── get_stock/
    │   │   │   ├── GetStockQuery.py
    │   │   │   ├── GetStock.py              # Use Case
    │   │   │   └── GetStockQueryHandler.py  # Handler
    │   │   ├── get_stock_by_symbol/
    │   │   ├── get_current_stocks/
    │   │   ├── get_stock_history/
    │   │   ├── get_stocks_overview/
    │   │   ├── get_stock_valuation/
    │   │   ├── get_daily_analysis/
    │   │   └── get_results_portafolio/
    │   └── commands/                        # Casos de uso tipo Command
    │       └── post_stock_snapshot/
    ├── domain/                              # (Vacío por ahora - futuro DDD)
    ├── infrastructure/
    │   ├── controllers/                     # Controladores específicos
    │   └── repositories/                    # (Vacío por ahora)
    └── routing/
        └── urls.py                          # Router específico del contexto
```

## 🔑 Patrones y Decisiones Tomadas

### 1. Handlers Simplificados

**Antes:**
```python
class GetCurrentStocksQueryHandler(QueryHandler):
    @classmethod
    def create(cls):
        mysql_service = MySQLService()  # Handler conoce MySQLService
        use_case = GetCurrentStocks(mysql_service)
        return cls(use_case)
```

**Ahora:**
```python
class GetCurrentStocksQueryHandler(QueryHandler):
    def __init__(self, use_case: GetCurrentStocks):
        self.use_case = use_case

    def handle(self, query: GetCurrentStocksQuery):
        return self.use_case.execute(query)

    @classmethod
    def create(cls):
        use_case = GetCurrentStocks()  # UseCase crea sus dependencias
        return cls(use_case)
```

**Ventajas:**
- Handler desacoplado: no conoce `MySQLService`
- UseCase flexible: puede usar MySQL, Mongo u otro servicio
- Testeable: fácil inyectar mocks en tests

### 2. UseCases con Dependencias Opcionales

**Patrón implementado:**
```python
class GetCurrentStocks:
    def __init__(self, mysql_service: Optional[MySQLService] = None):
        # Si no se pasa mysql_service, lo creamos internamente
        self.mysql_service = mysql_service if mysql_service is not None else MySQLService()
        self.market_service = MarketStatusService()
```

**Ventajas:**
- Default funcional: funciona sin pasar nada
- Flexible para tests: permite inyectar mocks
- Cada UseCase maneja sus propias dependencias

### 3. Controladores Minimalistas

**Patrón estándar:**
```python
class GetCurrentStocksController(ApiController):
    def __init__(self):
        qb = get_query_bus()
        super().__init__(query_bus=qb)

    def get(self, request):
        portfolio_id = request.GET.get("portfolio_id")
        query = GetCurrentStocksQuery(portfolio_id=portfolio_id)
        response = self.ask(query)
        return JsonResponse(response, safe=False)

    def register_exceptions(self) -> dict:
        return {
            ValueError: 400,
        }
```

**Características:**
- Sin registro manual de handlers (auto-registro)
- Sin try-except redundante (ApiController ya maneja)
- Mapeo de excepciones específicas (ValueError → 400)

### 4. QueryBus Global con Auto-registro

**Instancia compartida:**
```python
# En query_bus.py
_shared_query_bus = QueryBus()  # Singleton

def get_query_bus() -> QueryBus:
    return _shared_query_bus
```

**Auto-registro:**
- El QueryBus inspecciona los handlers al inicializarse
- Descubre automáticamente qué Query maneja cada Handler
- Busca handlers en `core/application/UseCase` y `stock/application/queries`

**Handler Loader:**
```python
def load_handlers():
    handlers = {}
    base_paths = [
        os.path.join(settings.BASE_DIR, 'myproject', 'core', 'application', 'UseCase'),
        os.path.join(settings.BASE_DIR, 'myproject', 'stock', 'application', 'queries'),
        os.path.join(settings.BASE_DIR, 'myproject', 'stock', 'application', 'commands'),
    ]
    # ... carga handlers de ambos contextos
```

## 📦 Casos de Uso del Contexto Stock

### Queries (9)
1. `GetStock` - Obtener todas las acciones
2. `GetStockBySymbol` - Obtener acción por símbolo
3. `SearchStockBySymbol` - Buscar información de acción usando yfinance (para crear nuevas acciones)
4. `GetCurrentStocks` - Obtener estado actual de acciones
5. `GetStockHistory` - Obtener historial de una acción
6. `GetStocksOverview` - Vista general de acciones en portfolio
7. `GetStockValuation` - Valoración de una acción
8. `GetDailyAnalysis` - Análisis diario de acciones
9. `GetResultsPortafolio` - Resultados de un portfolio

### Commands (2)
1. `CreateStock` - Crear/agregar una nueva acción a la tabla stocks
2. `PostStockSnapshot` - Guardar snapshot de precio de acción

## 🔧 Infraestructura Compartida

### ApiController
- **Ubicación:** `shared/infrastructure/controller/api_controller.py`
- **Propósito:** Clase base para todos los controladores
- **Funcionalidad:**
  - Maneja QueryBus y CommandBus
  - Manejo centralizado de excepciones
  - Métodos helper: `ask()`, `dispatch_command()`

### QueryBus / CommandBus
- **Ubicación:** `shared/infrastructure/bus/`
- **Instancias compartidas:** Singleton pattern
- **Auto-registro:** Descubre handlers automáticamente

## 🎨 Estilos y Convenciones

### Naming
- **Queries:** `GetStockQuery`, `GetCurrentStocksQuery`
- **UseCases:** `GetStock`, `GetCurrentStocks`
- **Handlers:** `GetStockQueryHandler`, `GetCurrentStocksQueryHandler`
- **Controllers:** `GetStockController`, `GetCurrentStocksController`

### Imports
- Imports absolutos: `from myproject.stock.application.queries.get_stock.GetStock import GetStock`
- Tipos explícitos: `def __init__(self, use_case: GetCurrentStocks)`

### Respuestas
- Algunos handlers usan `BaseResponse` (GetStock, GetStockBySymbol)
- Otros retornan directamente el resultado del use_case
- **TODO:** Estandarizar formato de respuestas

## 🔍 Puntos Importantes

### 1. GetStocksOverview - Caso Especial

Este UseCase usa otros UseCases internamente:
```python
class GetStocksOverview:
    def __init__(self, mysql_service: Optional[MySQLService] = None):
        self.current_use_case = GetCurrentStocks(mysql_service)
        self.history_use_case = GetStockHistory(mysql_service)
```

**Comportamiento:**
- Pasa `mysql_service` (puede ser `None`) a los use cases hijos
- Los use cases hijos crean `MySQLService` internamente si reciben `None`
- **Resultado:** Cada use case hijo tiene su propia instancia (podría optimizarse)

### 2. MySQLService - Instancias Múltiples

**Problema actual:**
- Cada UseCase que necesita MySQL crea su propia instancia
- Esto puede generar múltiples conexiones a la BD

**Solución futura propuesta:**
- Crear instancia compartida de MySQLService (como QueryBus)
- Pasar la instancia compartida a los UseCases

### 3. Router Principal

**Ubicación:** `core/application/routing/urls.py`

```python
urlpatterns = [
    path('health/', HealthCheckController.as_view(), name='health_check'),
    path('', include('myproject.stock.routing.urls')),  # Incluye router de Stock
]
```

### 4. Handler Loader

Busca handlers en:
- `core/application/UseCase` (contexto antiguo - para migrar después)
- `stock/application/queries` (contexto Stock - VSA)
- `stock/application/commands` (contexto Stock - VSA)

## 📝 Próximos Pasos Sugeridos

### Corto Plazo
1. **Estandarizar formato de respuestas** - Todos los handlers deberían usar `BaseResponse` o todos retornar directo
2. **Instancia compartida de MySQLService** - Similar a QueryBus para optimizar conexiones
3. **Mover contextos restantes** - Logs, Voice, HealthCheck, etc. a sus propios contextos VSA

### Mediano Plazo
1. **Implementar DDD en el contexto Stock** - Agregar entidades de dominio
2. **Repositorios específicos** - Crear interfaces y abstracciones en `stock/infrastructure/repositories/`
3. **Eventos de dominio** - Para comunicar entre contextos si es necesario

### Largo Plazo
1. **Microservicios** - Separar contextos en servicios independientes si escala
2. **CQRS avanzado** - Read models separados si es necesario

## 🧪 Testing

**Nota importante:** Los handlers son testeables ahora:
- Puedes inyectar mocks de `MySQLService` al crear el UseCase
- El handler solo recibe el use_case, fácil de mockear

**Ejemplo de test:**
```python
def test_get_current_stocks_handler():
    mock_mysql = MockMySQLService()
    use_case = GetCurrentStocks(mysql_service=mock_mysql)
    handler = GetCurrentStocksQueryHandler(use_case)
    # ... test
```

## 📚 Archivos Clave

### Configuración
- `.vscode/settings.json` - Oculta `__pycache__` y `__init__.py` del explorador

### Routing
- `core/application/routing/urls.py` - Router principal
- `stock/routing/urls.py` - Router específico del contexto Stock

### Bus
- `shared/infrastructure/bus/query_bus.py` - QueryBus con auto-registro
- `shared/infrastructure/bus/command_bus.py` - CommandBus

### Handlers
- `shared/application/handlers/handler_loader.py` - Carga handlers de ambos contextos

### Base
- `shared/infrastructure/controller/api_controller.py` - Clase base de controladores
- `shared/domain/bus/query/query_handler.py` - Interfaz base de handlers

## 🎓 Conceptos Clave Aprendidos

1. **Herencia en Python:** `class Child(Parent):` = `class Child extends Parent` en PHP
2. **Optional Types:** `Optional[Type] = None` para parámetros opcionales con tipos
3. **Singleton Pattern:** Instancias compartidas en módulo para QueryBus y CommandBus
4. **Auto-registro:** Inspección de tipos en runtime para descubrir handlers automáticamente
5. **Vertical Slice Architecture:** Organización por feature/contexto en lugar de por capas

## ⚠️ Notas Importantes

- Los archivos antiguos en `core/application/UseCase/Stock/*` todavía existen pero NO se usan
- Los controladores antiguos en `core/infrastructure/controllers/stock/*` fueron eliminados
- El contexto `core` mantiene otros casos de uso (HealthCheck, Logs, Voice, etc.) que no son Stock
- `MySQLService` sigue creándose múltiples veces (podría optimizarse con singleton)

### ⚠️ Error Común: 405 Method Not Allowed

Si recibes un error 405 al crear endpoints, **verifica el orden de las rutas** en `urls.py`. Las rutas específicas deben ir antes de las genéricas. Ver sección "Orden de las Rutas" en `CREAR_CASO_USO.md` para más detalles.
## 🚀 Estado Final

**El contexto Stock está completamente migrado a VSA y funcionando con:**
- ✅ Estructura Vertical Slice
- ✅ Handlers simplificados y desacoplados
- ✅ Controladores minimalistas
- ✅ Auto-registro de handlers
- ✅ Router específico del contexto
- ✅ UseCases con dependencias opcionales

**Listo para continuar el desarrollo y migrar otros contextos siguiendo el mismo patrón.**
