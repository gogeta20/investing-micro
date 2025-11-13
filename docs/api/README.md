# Investing Micro API - Postman Collection

Colección de Postman para testear los endpoints del backend Django.

## 📁 Estructura

```
investing (colección raíz)
├── Variables de entorno
│   ├── base_url: http://localhost
│   ├── port: 8000
│   └── api_base: {{base_url}}:{{port}}/api
└── python (carpeta Django)
    ├── Health Check
    ├── Get All Stocks
    ├── Get Stock by Symbol
    ├── Get Stock History
    ├── Get Current Stocks State
    ├── Get Stocks Overview (⚠️ puede no funcionar)
    ├── Get Portfolio Results
    └── Post Stock Snapshot
```

## 🚀 Cómo usar

1. **Importar la colección en Postman:**
   - Abre Postman
   - Click en "Import"
   - Selecciona el archivo `investing-postman-collection.json`

2. **Configurar variables (opcional):**
   - Las variables están configuradas en la colección raíz
   - Puedes modificar `base_url` y `port` si cambias el entorno
   - Por defecto: `http://localhost:8000/api`

3. **Testear endpoints:**
   - Todos los endpoints usan las variables `{{api_base}}`
   - Los parámetros están pre-configurados con ejemplos
   - Puedes modificar los valores según necesites

## 📋 Endpoints disponibles

### Health Check
- **GET** `/api/health/`
- Verifica el estado del servicio

### Stocks
- **GET** `/api/stocks/`
  - Obtiene todas las acciones

- **GET** `/api/stocks/:symbol`
  - Obtiene una acción por símbolo
  - Ejemplo: `/api/stocks/AAPL`

- **GET** `/api/stocks/:symbol/history`
  - Obtiene el historial de una acción
  - Parámetros opcionales: `from`, `to` (fechas)
  - Ejemplo: `/api/stocks/AAPL/history?from=2025-01-01&to=2025-01-10`

- **GET** `/api/stocks/current/state`
  - Obtiene el estado actual de las acciones
  - Parámetro: `portfolio_id` (query param)

- **GET** `/api/stocks/overview/list`
  - ⚠️ **NOTA**: Este endpoint puede no estar funcionando correctamente
  - Obtiene vista general de acciones
  - Parámetro: `portfolio_id` (query param)

### Portfolio
- **GET** `/api/portfolio/:id_portafolio/results`
  - Obtiene los resultados de un portafolio
  - Parámetro: `id_portafolio` (path param)

### Snapshot
- **POST** `/api/stock/snapshot/save`
  - Guarda un snapshot de una acción
  - Body (JSON):
    ```json
    {
        "symbol": "AAPL",
        "price": 150.00,
        "recorded_at": "2025-01-10T12:00:00Z"
    }
    ```

## 🔧 Variables de entorno

Las variables están configuradas en la colección raíz:

- `base_url`: URL base del servidor (por defecto: `http://localhost`)
- `port`: Puerto del servidor (por defecto: `8000`)
- `api_base`: URL completa de la API (por defecto: `{{base_url}}:{{port}}/api`)

Puedes crear un entorno en Postman para diferentes configuraciones (local, staging, production).

## 📝 Notas

- El endpoint `Get Stocks Overview` puede no estar funcionando correctamente y será eliminado después
- Todos los endpoints devuelven JSON
- Los endpoints que requieren parámetros tienen ejemplos pre-configurados
- Para cambiar el entorno, modifica las variables en la colección o crea un entorno en Postman
