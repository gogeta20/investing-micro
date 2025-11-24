# Guía para Crear un Caso de Uso en el Frontend

Esta guía explica cómo crear un caso de uso siguiendo el patrón establecido en el proyecto. Los casos de uso encapsulan las llamadas al backend y permiten alternar entre modo mock y API real.

## 📁 Estructura de Carpetas

Los casos de uso se organizan por tipo de operación (GET, POST, PUT, DELETE) y luego por funcionalidad:

```
src/modules/stock/application/useCase/
├── get/                    # Operaciones GET
│   ├── CurrentState/
│   │   ├── CurrentStateUseCase.ts
│   │   └── mock.json (opcional)
│   ├── GetPortfoliosList/
│   └── GetStockHistory/
├── post/                   # Operaciones POST
│   └── CreatePortfolio/
└── put/                    # Operaciones PUT (si aplica)
```

## 📝 Plantilla Base para Caso de Uso

### Para operaciones GET

```typescript
import HttpClientDjango from "@/core/http/HttpClientDjango";
import { UtilHelper } from "@/core/utilities/UtilHelper";

// 1. Definir interfaces/tipos exportados
export interface MiTipoDatos {
  id: number;
  campo1: string;
  campo2?: number;
}

export interface MiResponse {
  data: MiTipoDatos[];
  // otros campos de respuesta
}

// 2. Parámetros del caso de uso (si aplica)
export interface MiUseCaseParams {
  id?: number;
  filtro?: string;
}

// 3. Función InMemory (Mock)
async function InMemory(params?: MiUseCaseParams): Promise<MiResponse> {
  await UtilHelper.wait(500); // Simular delay de red

  // Retornar estructura mock que coincida con la respuesta real del backend
  return {
    data: [
      {
        id: 1,
        campo1: "valor1",
        campo2: 100,
      },
    ],
  };
}

// 4. Función Api (Llamada real)
async function Api(params?: MiUseCaseParams): Promise<MiResponse> {
  const response = await HttpClientDjango.get<MiResponse>(
    "/api/endpoint/ruta",
    params // Query params si aplica
  );
  return response.data;
}

// 5. Función principal del caso de uso
async function MiUseCase(params?: MiUseCaseParams): Promise<MiResponse> {
  return UtilHelper.checkEnvironment() ? await InMemory(params) : await Api(params);
}

// 6. Exportar
export { MiUseCase };
```

### Para operaciones POST/PUT/DELETE

```typescript
import HttpClientDjango from "@/core/http/HttpClientDjango";
import { UtilHelper } from "@/core/utilities/UtilHelper";

// 1. Definir interfaces
export interface MiRequest {
  campo1: string;
  campo2: number;
}

export interface MiResponse {
  id: number;
  message: string;
  // otros campos
}

// 2. Función InMemory (Mock)
async function InMemory(payload: MiRequest): Promise<MiResponse> {
  await UtilHelper.wait(1000); // Simular delay de red

  return {
    id: Math.floor(Math.random() * 1000) + 1,
    message: "Operación exitosa",
  };
}

// 3. Función Api (Llamada real)
async function Api(payload: MiRequest): Promise<MiResponse> {
  const response = await HttpClientDjango.post<MiResponse>(
    "/api/endpoint/ruta",
    payload
  );
  return response.data;
}

// 4. Función principal
async function MiUseCase(payload: MiRequest): Promise<MiResponse> {
  return UtilHelper.checkEnvironment()
    ? await InMemory(payload)
    : await Api(payload);
}

// 5. Exportar
export { MiUseCase };
```

## 🎯 Pasos Detallados

### Paso 1: Crear la estructura de carpetas

```bash
src/modules/stock/application/useCase/get/MiNuevoCaso/
└── MiNuevoCasoUseCase.ts
```

### Paso 2: Definir los tipos/interfaces

**IMPORTANTE:** Los tipos deben reflejar **exactamente** la estructura que retorna el backend. Si no tienes el endpoint listo, consulta la documentación del backend o pregunta al equipo.

```typescript
// Ejemplo basado en respuesta real del backend
export interface StockCurrent {
  symbol: string;
  name: string;
  price?: number;
  recorded_at?: string;
  error?: string;
  id?: number; // Si el backend lo incluye
}

export interface StockCurrentResponse {
  data: StockCurrent[];
  portfolio_id: number | null;
  updated_at: string;
}
```

### Paso 3: Implementar función InMemory (Mock)

El mock debe retornar la **misma estructura** que el backend:

```typescript
async function InMemory(): Promise<StockCurrentResponse> {
  await UtilHelper.wait(500); // Simular delay

  return {
    data: [
      {
        symbol: "AAPL",
        name: "Apple Inc.",
        price: 175.50,
        recorded_at: new Date().toISOString(),
      },
    ],
    portfolio_id: null,
    updated_at: new Date().toISOString(),
  };
}
```

**⚠️ Nota:** El mock NO es para saber qué esperamos del backend. El mock debe reflejar la estructura real que el backend retorna. Si no conoces la estructura, pregunta al equipo de backend o revisa la documentación de la API.

### Paso 4: Implementar función Api (Real)

```typescript
async function Api(): Promise<StockCurrentResponse> {
  const response = await HttpClientDjango.get<StockCurrentResponse>(
    "/api/stock/current/state"
  );
  return response.data; // HttpClientDjango retorna response.data
}
```

### Paso 5: Crear función principal

```typescript
async function CurrentStateUseCase(): Promise<StockCurrentResponse> {
  return UtilHelper.checkEnvironment() ? await InMemory() : await Api();
}
```

### Paso 6: Exportar

```typescript
export { CurrentStateUseCase };
// Opcional: exportar tipos si se usan en componentes
export type { StockCurrent, StockCurrentResponse };
```

## 📋 Ejemplo Completo: GET con Parámetros

```typescript
import HttpClientDjango from "@/core/http/HttpClientDjango";
import { UtilHelper } from "@/core/utilities/UtilHelper";

export interface StockHistoryItem {
  date: string;
  open: number;
  high: number;
  low: number;
  close: number;
  volume: number;
  price: number;
}

export interface StockHistoryResponse {
  symbol: string;
  period: string;
  data: StockHistoryItem[];
}

export interface GetStockHistoryParams {
  symbol: string;
  period?: string;
}

async function InMemory(params: GetStockHistoryParams): Promise<StockHistoryResponse> {
  await UtilHelper.wait(500);
  return {
    symbol: params.symbol,
    period: params.period || "1mo",
    data: [], // Mock vacío o con datos de ejemplo
  };
}

async function Api(params: GetStockHistoryParams): Promise<StockHistoryResponse> {
  const response = await HttpClientDjango.get<StockHistoryResponse>(
    `/api/stock/${params.symbol}/history`,
    { period: params.period || "1mo" } // Query params
  );
  return response.data;
}

async function GetStockHistoryUseCase(
  params: GetStockHistoryParams
): Promise<StockHistoryResponse> {
  return UtilHelper.checkEnvironment() ? await InMemory(params) : await Api(params);
}

export { GetStockHistoryUseCase };
```

## 📋 Ejemplo Completo: POST

```typescript
import HttpClientDjango from "@/core/http/HttpClientDjango";
import { UtilHelper } from "@/core/utilities/UtilHelper";

export interface CreatePortfolioRequest {
  name?: string;
  stocks: Array<{
    id: number;
    symbol: string;
  }>;
}

export interface CreatePortfolioResponse {
  portfolio_id: number;
  message: string;
  stocks: Array<{
    id: number;
    symbol: string;
  }>;
}

async function InMemory(payload: CreatePortfolioRequest): Promise<CreatePortfolioResponse> {
  await UtilHelper.wait(1000);
  return {
    portfolio_id: Math.floor(Math.random() * 1000) + 1,
    message: "Portafolio creado exitosamente",
    stocks: payload.stocks,
  };
}

async function Api(payload: CreatePortfolioRequest): Promise<CreatePortfolioResponse> {
  const response = await HttpClientDjango.post<CreatePortfolioResponse>(
    "/api/portfolio/create",
    payload
  );
  return response.data;
}

async function CreatePortfolioUseCase(
  payload: CreatePortfolioRequest
): Promise<CreatePortfolioResponse> {
  return UtilHelper.checkEnvironment()
    ? await InMemory(payload)
    : await Api(payload);
}

export { CreatePortfolioUseCase };
```

## 🔧 Uso en Componentes

### Importar y usar en un componente Vue

```vue
<script setup lang="ts">
import {
  CurrentStateUseCase,
  type StockCurrent
} from "@/modules/stock/application/useCase/get/CurrentState/CurrentStateUseCase";
import { ref, onMounted } from "vue";

const stocks = ref<StockCurrent[]>([]);
const loading = ref<boolean>(false);
const error = ref<string | null>(null);

const loadStocks = async () => {
  loading.value = true;
  error.value = null;

  try {
    const response = await CurrentStateUseCase();

    if (Array.isArray(response.data)) {
      stocks.value = response.data;
    } else {
      error.value = "Formato de respuesta inválido";
    }
  } catch (err: any) {
    console.error("Error:", err);
    error.value = err.response?.data?.error || err.message || "Error al cargar datos";
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  loadStocks();
});
</script>
```

### Con parámetros

```vue
<script setup lang="ts">
import {
  GetStockHistoryUseCase
} from "@/modules/stock/application/useCase/get/GetStockHistory/GetStockHistoryUseCase";

const props = defineProps<{
  symbol: string;
}>();

const loadHistory = async () => {
  try {
    const response = await GetStockHistoryUseCase({
      symbol: props.symbol,
      period: "1mo",
    });
    // Usar response...
  } catch (err) {
    // Manejar error...
  }
};
</script>
```

## ⚠️ Puntos Importantes

### 1. Estructura de Respuesta del Backend

**El mock NO es para descubrir la estructura esperada.** Debes conocer la estructura real del backend antes de crear el caso de uso:

- Consulta la documentación de la API
- Pregunta al equipo de backend
- Revisa ejemplos de respuestas reales
- Usa herramientas como Postman para probar el endpoint

### 2. UtilHelper.checkEnvironment()

Esta función determina si usar mock o API real:
- Retorna `true` en modo `extranet` o `preview` → usa mock
- Retorna `false` en otros modos → usa API real

```typescript
// Modo desarrollo/preview → mock
// Modo producción → API real
return UtilHelper.checkEnvironment() ? await InMemory() : await Api();
```

### 3. Tipos TypeScript

- **Siempre** define interfaces para request y response
- **Exporta** los tipos si se usan en componentes
- **Usa** tipos estrictos, evita `any` cuando sea posible

### 4. Manejo de Errores

El caso de uso no maneja errores, solo los propaga. El componente es responsable de manejar errores:

```typescript
try {
  const response = await MiUseCase();
  // Usar response...
} catch (err: any) {
  // El componente maneja el error
  error.value = err.response?.data?.error || err.message;
}
```

### 5. Nomenclatura

- **GET:** `Get[Nombre]UseCase` (ej: `GetPortfoliosListUseCase`)
- **POST:** `Create[Nombre]UseCase` o `[Accion][Nombre]UseCase` (ej: `CreatePortfolioUseCase`)
- **PUT:** `Update[Nombre]UseCase`
- **DELETE:** `Delete[Nombre]UseCase`

## ✅ Checklist para Nuevo Caso de Uso

- [ ] Crear carpeta en la estructura correcta (`get/`, `post/`, etc.)
- [ ] Definir interfaces/tipos exportados
- [ ] Implementar función `InMemory()` con estructura real del backend
- [ ] Implementar función `Api()` con llamada HTTP correcta
- [ ] Crear función principal que alterna entre mock y API
- [ ] Exportar función y tipos necesarios
- [ ] Probar en componente (con mock y con API real)
- [ ] Verificar que los tipos coinciden con la respuesta del backend

## 📚 Ejemplos de Referencia

- **GET simple:** `CurrentStateUseCase.ts`
- **GET con params:** `GetStockHistoryUseCase.ts`
- **POST:** `CreatePortfolioUseCase.ts`
- **GET con query params:** `GetStocksOverviewUseCase.ts`

## 🔗 Relación con Otros Archivos

- **HttpClientDjango:** Cliente HTTP para llamadas al backend Django
- **UtilHelper:** Utilidades, incluyendo `checkEnvironment()` y `wait()`
- **Componentes Vue:** Importan y usan los casos de uso

---

**Recuerda:** El mock debe reflejar la estructura real del backend, no es una herramienta para descubrir qué esperar. Siempre consulta la documentación o al equipo de backend antes de crear el caso de uso.

