# Guía para Crear una Nueva Página en el Proyecto

Esta guía te ayudará a crear una nueva página siguiendo los estándares y el tema del proyecto.

## 📁 Estructura del Proyecto

```
src/
├── pages/           # Vistas/páginas principales
│   ├── stock/       # Páginas relacionadas con acciones
│   └── ...
├── components/      # Componentes reutilizables
├── core/            # Funcionalidad core (router, http, layout, stores)
│   ├── router/      # Configuración de rutas
│   ├── http/        # Clientes HTTP (Django, Rust, Symfony)
│   └── layout/      # Componentes de layout
└── assets/          # Estilos, imágenes, etc.
    └── scss/
        └── variables.scss  # Variables del tema Tokyo Night
```

## 🎨 Tema: Tokyo Night Soft

Este proyecto usa el tema **Tokyo Night Soft** inspirado en VSCode. Es **OBLIGATORIO** respetar este tema en todas las páginas y componentes.

### Variables CSS Principales

Todas las variables están definidas en `src/assets/scss/variables.scss`:

#### Fondos (Backgrounds)
- `--tokyo-bg`: `#1a1b26` - Fondo principal
- `--tokyo-bg-secondary`: `#24283b` - Fondo secundario (tarjetas, tablas)
- `--tokyo-bg-tertiary`: `#2f3549` - Fondo terciario (headers, elementos hover)

#### Textos (Foregrounds)
- `--tokyo-fg`: `#c0caf5` - Texto principal
- `--tokyo-fg-secondary`: `#a9b1d6` - Texto secundario (subtítulos)
- `--tokyo-fg-dim`: `#565f89` - Texto atenuado (deshabilitado)

#### Colores de Acento
- `--tokyo-blue`: `#7aa2f7` - Color principal para links, botones primarios
- `--tokyo-purple`: `#bb9af7` - Púrpura
- `--tokyo-green`: `#9ece6a` - Verde (éxito, valores positivos)
- `--tokyo-yellow`: `#e0af68` - Amarillo (advertencias)
- `--tokyo-red`: `#f7768e` - Rojo (errores, valores negativos)
- `--tokyo-cyan`: `#7dcfff` - Cyan (hover en links)
- `--tokyo-orange`: `#ff9e64` - Naranja

#### Variables de Layout
- `--header-height`: `55px` - Altura del header fijo
- `--border-radius`: `0.375rem` - Radio de borde estándar
- `--content-padding`: `1rem` - Padding estándar

### Uso de Variables CSS

**✅ CORRECTO:**
```scss
.my-component {
  background-color: var(--tokyo-bg);
  color: var(--tokyo-fg);
  border: 1px solid var(--tokyo-bg-tertiary);

  &:hover {
    background-color: var(--tokyo-bg-tertiary);
  }
}
```

**❌ INCORRECTO:**
```scss
.my-component {
  background-color: #ffffff;  // ❌ No uses colores hardcodeados
  color: #000000;              // ❌ Usa las variables del tema
}
```

## 📝 Paso a Paso: Crear una Nueva Página

### Paso 1: Crear el Componente de Vista

Crea un nuevo archivo Vue en `src/pages/` o `src/pages/{categoria}/`:

**Ejemplo: `src/pages/stock/MiNuevaVista.vue`**

```vue
<script setup lang="ts">
// Si necesitas componentes
import MiComponente from "@/components/MiComponente.vue";

// Si necesitas acceso a la ruta
import { useRoute } from "vue-router";
import { computed } from "vue";

const route = useRoute();
// Para parámetros de ruta: const id = computed(() => route.params.id as string);
</script>

<template>
  <div class="mi-nueva-vista">
    <div class="mi-nueva-vista-container">
      <!-- Header opcional con enlace de vuelta -->
      <div class="header-section">
        <router-link to="/ruta-anterior" class="back-link">
          <i class="pi pi-arrow-left"></i> Volver
        </router-link>
        <h1 class="vista-title">Mi Nueva Vista</h1>
        <p class="vista-subtitle">Descripción de la vista</p>
      </div>

      <!-- Contenido principal -->
      <div class="content">
        <!-- Aquí va tu contenido -->
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
.mi-nueva-vista {
  min-height: calc(100vh - var(--header-height));
  padding: 2rem;
  background-color: var(--tokyo-bg);
}

.mi-nueva-vista-container {
  max-width: 1400px;
  margin: 0 auto;
}

.header-section {
  margin-bottom: 2rem;
}

.back-link {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  color: var(--tokyo-blue);
  text-decoration: none;
  font-size: 0.95rem;
  margin-bottom: 1rem;
  transition: color 0.2s ease;

  &:hover {
    color: var(--tokyo-cyan);
    text-decoration: underline;
  }

  i {
    font-size: 0.9rem;
  }
}

.vista-title {
  font-size: 2rem;
  font-weight: 600;
  color: var(--tokyo-fg);
  margin-bottom: 0.5rem;
}

.vista-subtitle {
  color: var(--tokyo-fg-secondary);
  margin-bottom: 1rem;
  font-size: 0.95rem;
}

.content {
  // Estilos del contenido
}
</style>
```

### Paso 2: Agregar la Ruta en el Router

Edita `src/core/router/index.ts`:

```typescript
// 1. Importa tu componente
import MiNuevaVista from '@/pages/stock/MiNuevaVista.vue';

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    component: () => import("@/core/layout/BaseLayout.vue"),
    children: [
      // ... otras rutas ...

      // 2. Agrega tu nueva ruta
      {
        path: "/mi-nueva-ruta",
        component: MiNuevaVista,
      },

      // Para rutas con parámetros dinámicos:
      {
        path: "/mi-ruta/:id",
        component: MiNuevaVista,
      },
    ],
  },
];
```

### Paso 3: Crear Componentes de Tabla (si aplica)

Si necesitas mostrar datos en una tabla, crea un componente en `src/components/`:

**Ejemplo: `src/components/MiTabla.vue`**

```vue
<template>
  <div class="mi-tabla-container">
    <div v-if="loading" class="loading-container">
      <p>Cargando datos...</p>
    </div>

    <div v-else-if="error" class="error-container">
      <p class="error-message">{{ error }}</p>
      <button @click="loadData" class="retry-button">Reintentar</button>
    </div>

    <div v-else>
      <DataTable
        :value="data"
        tableStyle="min-width: 60rem"
        paginator
        :rows="20"
        :rowsPerPageOptions="[10, 20, 50, 100]"
        class="p-datatable-sm">
        <Column field="campo1" header="Campo 1" sortable></Column>
        <Column field="campo2" header="Campo 2" sortable></Column>
      </DataTable>
    </div>
  </div>
</template>

<script setup lang="ts">
import HttpClientDjango from "@/core/http/HttpClientDjango";
import Column from "primevue/column";
import DataTable from "primevue/datatable";
import { onMounted, ref } from "vue";

interface MiTipo {
  campo1: string;
  campo2: number;
}

const props = defineProps<{
  parametro?: string;
}>();

const data = ref<MiTipo[]>([]);
const loading = ref<boolean>(false);
const error = ref<string | null>(null);

const loadData = async () => {
  loading.value = true;
  error.value = null;

  try {
    const response = await HttpClientDjango.get<{ data: MiTipo[] }>("/api/endpoint");
    data.value = response.data.data;
  } catch (err: any) {
    console.error("Error:", err);
    error.value = err.response?.data?.error || err.message || "Error al cargar datos";
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  loadData();
});
</script>

<style scoped lang="scss">
.mi-tabla-container {
  padding: 1rem;
}

.loading-container,
.error-container {
  text-align: center;
  padding: 2rem;
  color: var(--tokyo-fg);
}

.error-message {
  color: var(--tokyo-red);
  margin-bottom: 1rem;
}

.retry-button {
  padding: 0.5rem 1rem;
  background-color: var(--tokyo-blue);
  color: var(--tokyo-bg);
  border: none;
  border-radius: var(--border-radius);
  cursor: pointer;
  font-weight: 500;
  transition: background-color 0.2s ease;

  &:hover {
    background-color: var(--primary-hover);
  }
}

// Estilos para PrimeVue DataTable con tema Tokyo Night
:deep(.p-datatable) {
  background-color: var(--tokyo-bg-secondary);
  color: var(--tokyo-fg);
  border: 1px solid var(--tokyo-bg-tertiary);
  border-radius: var(--border-radius);
}

:deep(.p-datatable-thead > tr > th) {
  background-color: var(--tokyo-bg-tertiary);
  color: var(--tokyo-fg);
  border-bottom: 1px solid var(--tokyo-bg-tertiary);
  font-weight: 600;
}

:deep(.p-datatable-tbody > tr) {
  background-color: var(--tokyo-bg-secondary);
  color: var(--tokyo-fg);
  border-bottom: 1px solid var(--tokyo-bg-tertiary);

  &:hover {
    background-color: var(--tokyo-bg-tertiary);
  }
}

:deep(.p-paginator) {
  background-color: var(--tokyo-bg-secondary);
  color: var(--tokyo-fg);
  border-top: 1px solid var(--tokyo-bg-tertiary);
}

:deep(.p-paginator .p-paginator-page.p-highlight) {
  background-color: var(--tokyo-blue);
  color: var(--tokyo-bg);
}
</style>
```

## 🎯 Patrones y Convenciones

### Nomenclatura

- **Vistas/Páginas**: PascalCase con sufijo `View` (ej: `StocksView.vue`, `SingleStockView.vue`)
- **Componentes**: PascalCase descriptivo (ej: `AllStocksTable.vue`, `StockValuationModal.vue`)
- **Rutas**: kebab-case (ej: `/stocks`, `/stock/:symbol`)

### Estructura de Clases CSS

- Usa nombres descriptivos en kebab-case
- Prefijo con el nombre del componente: `.mi-vista-container`, `.mi-vista-title`
- Usa variables CSS del tema, nunca valores hardcodeados

### Estados de Carga y Error

Siempre incluye:
1. **Estado de carga** (`loading`)
2. **Estado de error** (`error`) con botón de reintento
3. **Estado de éxito** con los datos

```vue
<div v-if="loading">Cargando...</div>
<div v-else-if="error">
  <p>{{ error }}</p>
  <button @click="loadData">Reintentar</button>
</div>
<div v-else>
  <!-- Datos -->
</div>
```

### Links y Navegación

- Usa `router-link` para navegación interna
- Estilo consistente para links:
  - Color: `var(--tokyo-blue)`
  - Hover: `var(--tokyo-cyan)`
  - Transición: `0.2s ease`

```vue
<router-link to="/ruta" class="my-link">
  Texto del link
</router-link>
```

```scss
.my-link {
  color: var(--tokyo-blue);
  text-decoration: none;
  transition: color 0.2s ease;

  &:hover {
    color: var(--tokyo-cyan);
    text-decoration: underline;
  }
}
```

### Botones

```scss
.my-button {
  padding: 0.5rem 1rem;
  background-color: var(--tokyo-blue);
  color: var(--tokyo-bg);
  border: none;
  border-radius: var(--border-radius);
  cursor: pointer;
  font-weight: 500;
  transition: background-color 0.2s ease;

  &:hover {
    background-color: var(--primary-hover);
  }

  &:active {
    background-color: var(--primary-active);
  }
}
```

### Formateo de Datos

```typescript
// Precios
const formatPrice = (price: number | undefined): string => {
  if (!price) return "0.00";
  return price.toFixed(2);
};

// Fechas
const formatDate = (dateString: string | undefined): string => {
  if (!dateString) return "N/A";
  try {
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return "N/A";
    return date.toLocaleDateString("es-ES", {
      year: "numeric",
      month: "2-digit",
      day: "2-digit",
    });
  } catch (e) {
    return "N/A";
  }
};

// Volúmenes
const formatVolume = (volume: number | undefined): string => {
  if (!volume) return "0";
  return volume.toLocaleString("es-ES");
};
```

## 📚 Ejemplos Reales del Proyecto

### Vista Simple: `StocksView.vue`
```vue
<template>
  <div class="stocks-view">
    <div class="stocks-container">
      <h1 class="stocks-title">Tabla de Acciones</h1>
      <p class="stocks-subtitle">Vista general de las acciones</p>
      <AllStocksTable />
    </div>
  </div>
</template>
```

### Vista con Parámetros: `SingleStockView.vue`
```vue
<script setup lang="ts">
import { useRoute } from "vue-router";
import { computed } from "vue";

const route = useRoute();
const symbol = computed(() => route.params.symbol as string);
</script>

<template>
  <div class="single-stock-view">
    <div class="single-stock-container">
      <router-link to="/stocks" class="back-link">
        <i class="pi pi-arrow-left"></i> Volver
      </router-link>
      <h1>Historial de {{ symbol }}</h1>
      <SingleStockTable :symbol="symbol" />
    </div>
  </div>
</template>
```

## 🔧 Clientes HTTP Disponibles

El proyecto tiene varios clientes HTTP configurados:

- **HttpClientDjango**: `@/core/http/HttpClientDjango` - Para APIs Django
- **HttpClientRust**: `@/core/http/HttpClientRust` - Para APIs Rust
- **HttpClientSymfony**: `@/core/http/HttpClientSymfony` - Para APIs Symfony

**Uso:**
```typescript
import HttpClientDjango from "@/core/http/HttpClientDjango";

// GET
const response = await HttpClientDjango.get<TipoRespuesta>("/api/endpoint", { params });

// POST
const response = await HttpClientDjango.post<TipoRespuesta>("/api/endpoint", data);
```

## ✅ Checklist para Nueva Página

- [ ] Crear archivo `.vue` en `src/pages/`
- [ ] Usar estructura de contenedor estándar (`max-width: 1400px`, `margin: 0 auto`)
- [ ] Aplicar tema Tokyo Night (variables CSS, no colores hardcodeados)
- [ ] Incluir estados de carga y error
- [ ] Agregar ruta en `src/core/router/index.ts`
- [ ] Si usa tablas, incluir estilos para PrimeVue con tema Tokyo Night
- [ ] Nombres descriptivos y consistentes
- [ ] Links de navegación con estilos consistentes
- [ ] Transiciones suaves (`0.2s ease`)

## 🎨 Recordatorio Final

**SIEMPRE:**
- ✅ Usa variables CSS del tema (`var(--tokyo-*)`)
- ✅ Respeta el esquema de colores Tokyo Night
- ✅ Usa transiciones suaves
- ✅ Incluye estados de carga y error
- ✅ Sigue las convenciones de nomenclatura

**NUNCA:**
- ❌ Colores hardcodeados (`#ffffff`, `rgb(255,255,255)`)
- ❌ Fondos blancos o claros
- ❌ Textos negros o muy oscuros
- ❌ Saltarte los estados de carga/error

---

**Referencias:**
- Variables del tema: `src/assets/scss/variables.scss`
- Ejemplo de vista: `src/pages/stock/StocksView.vue`
- Ejemplo de tabla: `src/components/AllStocksTable.vue`
- Router: `src/core/router/index.ts`
