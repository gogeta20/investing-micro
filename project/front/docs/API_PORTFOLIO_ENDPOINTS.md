# Especificación de Endpoints para Portafolios

Documentación técnica para implementar los endpoints de backend necesarios para la funcionalidad de creación y gestión de portafolios.

## 📋 Endpoints Requeridos

### 1. Crear Portafolio

**Endpoint:** `POST /api/portfolio/create`

**Descripción:** Crea un nuevo portafolio asociando acciones seleccionadas.

#### Request Body

```json
{
  "name": "Portafolio Principal",  // Opcional: Nombre del portafolio
  "stocks": [
    {
      "id": 1,                      // ID de la acción (requerido)
      "symbol": "AAPL"              // Símbolo de la acción (requerido)
    },
    {
      "id": 2,
      "symbol": "MSFT"
    },
    {
      "id": 3,
      "symbol": "GOOGL"
    }
  ]
}
```

**Estructura TypeScript:**
```typescript
interface CreatePortfolioRequest {
  name?: string;                    // Opcional
  stocks: Array<{
    id: number;                     // Requerido: ID único de la acción
    symbol: string;                 // Requerido: Símbolo de la acción (AAPL, MSFT, etc.)
  }>;
}
```

**Validaciones esperadas:**
- `stocks` debe ser un array con al menos un elemento
- Cada elemento en `stocks` debe tener `id` y `symbol`
- `id` debe ser un número válido
- `symbol` debe ser una cadena no vacía
- `name` es opcional, si no se envía, puede generarse automáticamente (ej: "Portafolio #1")

#### Response (Éxito - 200/201)

```json
{
  "portfolio_id": 1,                // ID del portafolio creado
  "message": "Portafolio creado exitosamente",
  "stocks": [
    {
      "id": 1,
      "symbol": "AAPL"
    },
    {
      "id": 2,
      "symbol": "MSFT"
    },
    {
      "id": 3,
      "symbol": "GOOGL"
    }
  ]
}
```

**Estructura TypeScript:**
```typescript
interface CreatePortfolioResponse {
  portfolio_id: number;
  message: string;
  stocks: Array<{
    id: number;
    symbol: string;
  }>;
}
```

#### Response (Error - 400/500)

```json
{
  "error": "Descripción del error",
  "message": "Mensaje detallado del error"  // Opcional
}
```

**Códigos de estado HTTP:**
- `201 Created`: Portafolio creado exitosamente
- `400 Bad Request`: Datos inválidos (ej: array de stocks vacío, IDs inválidos)
- `500 Internal Server Error`: Error del servidor

#### Ejemplo de Request (cURL)

```bash
curl -X POST http://localhost:8000/api/portfolio/create \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Portafolio Tech",
    "stocks": [
      {"id": 1, "symbol": "AAPL"},
      {"id": 2, "symbol": "MSFT"},
      {"id": 3, "symbol": "GOOGL"}
    ]
  }'
```

---

### 2. Listar Portafolios

**Endpoint:** `GET /api/portfolio/list`

**Descripción:** Obtiene la lista de todos los portafolios creados con sus acciones asociadas.

#### Query Parameters

Ninguno requerido.

#### Response (Éxito - 200)

```json
{
  "data": [
    {
      "id": 1,
      "name": "Portafolio Principal",
      "stocks_count": 5,
      "created_at": "2025-01-15T10:30:00Z",
      "stocks": [
        {
          "id": 1,
          "symbol": "AAPL"
        },
        {
          "id": 2,
          "symbol": "MSFT"
        },
        {
          "id": 3,
          "symbol": "GOOGL"
        },
        {
          "id": 4,
          "symbol": "AMZN"
        },
        {
          "id": 5,
          "symbol": "TSLA"
        }
      ]
    },
    {
      "id": 2,
      "name": "Portafolio Conservador",
      "stocks_count": 3,
      "created_at": "2025-01-16T14:20:00Z",
      "stocks": [
        {
          "id": 6,
          "symbol": "JNJ"
        },
        {
          "id": 7,
          "symbol": "PG"
        },
        {
          "id": 8,
          "symbol": "KO"
        }
      ]
    }
  ]
}
```

**Estructura TypeScript:**
```typescript
interface Portfolio {
  id: number;
  name?: string;                    // Opcional: Si no tiene nombre, usar "Portafolio #ID"
  stocks_count?: number;            // Opcional: Cantidad de acciones (puede calcularse del array)
  created_at?: string;              // Opcional: Fecha de creación en ISO 8601
  stocks: Array<{
    id: number;
    symbol: string;
  }>;
}

interface PortfoliosListResponse {
  data: Portfolio[];
}
```

**Notas:**
- Si un portafolio no tiene `name`, el frontend mostrará "Portafolio #ID"
- `stocks_count` es opcional, el frontend puede calcularlo desde `stocks.length`
- `created_at` debe estar en formato ISO 8601
- Si no hay portafolios, retornar `{"data": []}`

#### Response (Error - 500)

```json
{
  "error": "Error al cargar los portafolios",
  "message": "Descripción detallada del error"  // Opcional
}
```

#### Ejemplo de Request (cURL)

```bash
curl -X GET http://localhost:8000/api/portfolio/list
```

---

## 🔗 Relación con Otros Endpoints

### Endpoint de Acciones Actuales

El frontend utiliza el endpoint existente para obtener las acciones disponibles:

**Endpoint:** `GET /api/stock/current/state`

Este endpoint se usa en la creación de portafolios para listar las acciones que pueden seleccionarse. La respuesta debe incluir al menos:
- `symbol`: Símbolo de la acción
- `name`: Nombre de la acción
- `id`: ID único de la acción (necesario para el POST de creación)

**Ejemplo de respuesta requerida:**
```json
{
  "portfolio_id": null,
  "updated_at": "2025-01-17T10:00:00Z",
  "data": [
    {
      "id": 1,
      "symbol": "AAPL",
      "name": "Apple Inc.",
      "price": 175.50,
      "recorded_at": "2025-01-17T10:00:00Z"
    },
    {
      "id": 2,
      "symbol": "MSFT",
      "name": "Microsoft Corporation",
      "price": 380.25,
      "recorded_at": "2025-01-17T10:00:00Z"
    }
  ]
}
```

**Importante:** El campo `id` en cada acción es **requerido** para el proceso de creación de portafolio, ya que se incluye en el request body junto con el `symbol`.

---

### Endpoint de Overview de Portafolio

Ya existe y se usa para ver el detalle de un portafolio:

**Endpoint:** `GET /api/stocks/overview/list?portfolio_id={id}`

Este endpoint no requiere cambios.

---

## 📝 Flujo de Datos

### Creación de Portafolio

```
1. Frontend carga acciones disponibles
   GET /api/stock/current/state
   ↓
   Usuario selecciona acciones con checkboxes

2. Usuario completa nombre (opcional) y hace clic en "Crear Portafolio"
   ↓
   Frontend prepara payload con IDs y símbolos seleccionados

3. Frontend envía request
   POST /api/portfolio/create
   Body: { name?: string, stocks: [{id, symbol}] }
   ↓
   Backend crea portafolio y relaciones
   ↓
   Backend responde con portfolio_id y stocks asociados

4. Frontend redirige a /portfolio para ver la lista actualizada
```

### Listado de Portafolios

```
1. Usuario navega a /portfolio
   ↓
   Frontend solicita lista
   GET /api/portfolio/list
   ↓
   Backend responde con array de portafolios
   ↓
   Frontend muestra tarjetas con información de cada portafolio
```

---

## ⚠️ Consideraciones Importantes

1. **IDs de Acciones**: Es crítico que el endpoint `/api/stock/current/state` retorne el campo `id` para cada acción, ya que se utiliza en el POST de creación.

2. **Validación de Símbolos**: El backend debe validar que los símbolos enviados existan y sean válidos antes de crear las relaciones.

3. **Validación de IDs**: El backend debe validar que los IDs enviados correspondan a acciones existentes.

4. **Duplicados**: El backend debe manejar casos donde:
   - Se intenta crear un portafolio con acciones duplicadas
   - Se intenta crear relaciones que ya existen

5. **Nombres**: Si no se envía `name`, el backend puede:
   - Generar automáticamente: "Portafolio #ID"
   - Dejar como null/undefined y el frontend lo manejará

6. **Relaciones**: El backend debe crear:
   - Un registro de portafolio
   - Múltiples registros de relación entre portafolio y acciones (tabla intermedia)

7. **Fechas**: Usar formato ISO 8601 para `created_at` (ej: `"2025-01-15T10:30:00Z"`)

---

## 🧪 Casos de Prueba Sugeridos

### Crear Portafolio

1. **Caso exitoso con nombre**
   - Request con `name` y múltiples stocks
   - Verificar que se crea con el nombre especificado

2. **Caso exitoso sin nombre**
   - Request sin `name` y múltiples stocks
   - Verificar que se crea (backend o frontend maneja el nombre)

3. **Caso error: array vacío**
   - Request con `stocks: []`
   - Debe retornar error 400

4. **Caso error: ID inválido**
   - Request con ID que no existe
   - Debe retornar error 400/404

5. **Caso error: símbolo inválido**
   - Request con símbolo que no existe
   - Debe retornar error 400

6. **Caso error: faltan campos requeridos**
   - Request sin `stocks`
   - Debe retornar error 400

### Listar Portafolios

1. **Caso exitoso con portafolios**
   - Debe retornar array con todos los portafolios

2. **Caso exitoso sin portafolios**
   - Debe retornar `{"data": []}`

3. **Caso error del servidor**
   - Simular error interno
   - Debe retornar error 500 con mensaje descriptivo

---

## 🔄 Integración con Frontend

El frontend ya tiene implementada la lógica para:
- ✅ Construir el payload con `id` y `symbol`
- ✅ Enviar el POST a `/api/portfolio/create`
- ✅ Manejar la respuesta y redirigir
- ✅ Solicitar la lista de portafolios
- ✅ Mostrar errores y estados de carga

**El código del frontend está preparado y solo espera que los endpoints estén disponibles.**

Una vez implementados los endpoints, simplemente descomentar el código real en:
- `src/pages/stock/CreatePortfolioView.vue` (líneas marcadas con `TODO`)
- `src/components/PortfoliosList.vue` (líneas marcadas con `TODO`)

Y comentar/eliminar el código mock correspondiente.

---

## 📞 Notas Finales

- Los endpoints deben seguir las convenciones REST
- Usar códigos de estado HTTP apropiados
- Mensajes de error descriptivos pero seguros (no exponer detalles internos)
- Validar todos los datos de entrada
- Considerar límites (ej: máximo de acciones por portafolio)
- Documentar cualquier limitación o requisito adicional
