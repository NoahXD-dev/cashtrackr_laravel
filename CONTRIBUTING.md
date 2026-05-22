# Guía de Convención de Commits

Este proyecto sigue la especificación de **Conventional Commits** (Commits Convencionales) para mantener un historial de git limpio, legible y fácil de seguir.

## Estructura del Mensaje de Commit

Cada mensaje de commit debe estructurarse de la siguiente manera:

```txt
<tipo>(<alcance/contexto opcional>): <descripción breve en minúsculas>

[cuerpo del mensaje opcional con detalles más extensos]

[pie de página opcional para referenciar issues o breaking changes]
```

---

## 1. Tipos de Commit (`<tipo>`)

El tipo de commit describe **qué** tipo de cambio se está introduciendo:

| Tipo | Descripción | Ejemplo |
| :--- | :--- | :--- |
| **`feat`** | Una nueva característica o funcionalidad para el usuario. | `feat(auth): agregar inicio de sesión con Google` |
| **`fix`** | Solución a un error o bug en el código. | `fix(database): corregir timeout de conexión a postgres` |
| **`docs`** | Cambios exclusivamente en la documentación (README, wikis, comentarios de código). | `docs: actualizar instrucciones de instalación en el readme` |
| **`style`** | Cambios estéticos o de formato que no afectan la lógica (espacios, formateo, punto y coma). | `style: aplicar formateo de prettier en vistas blade` |
| **`refactor`** | Reestructuración de código que no corrige errores ni añade funciones. | `refactor: simplificar controlador de transacciones` |
| **`perf`** | Cambios orientados a mejorar el rendimiento del código. | `perf(query): optimizar consulta de listado de gastos` |
| **`test`** | Agregar pruebas unitarias/funcionales que faltaban o corregir pruebas existentes. | `test: añadir pruebas para registro de usuarios` |
| **`build`** | Cambios que afectan el sistema de construcción o dependencias externas (npm, composer). | `build: actualizar dependencias en composer.json` |
| **`ci`** | Cambios en los archivos y scripts de configuración de integración/despliegue continuo (workflows, docker). | `ci: configurar GitHub Actions para pruebas automatizadas` |
| **`chore`** | Tareas de mantenimiento general del repositorio que no modifican el código de producción. | `chore: agregar archivo gitignore para archivos temporales` |
| **`revert`** | Revertir un commit anterior. | `revert: feat(auth): agregar inicio de sesión con Google` |

---

## 2. Alcance o Contexto (`<alcance>`) - Opcional

Es un sustantivo entre paréntesis que ayuda a especificar el área del código afectada por el cambio.
* **Ejemplos:** `feat(auth): ...`, `fix(router): ...`, `docs(api): ...`

---

## 3. Descripción (`<descripción>`)

* Debe ser una oración breve en tiempo imperativo o presente.
* No debe iniciar con mayúscula.
* No debe terminar con punto al final.
* Debe ser clara y al grano.

---

## 4. Cambios Importantes / Rompedores (`BREAKING CHANGE`)

Si un cambio rompe la compatibilidad con versiones anteriores o requiere cambios en otros lados de la aplicación, se debe indicar de dos maneras:

1. **Añadiendo un signo de exclamación `!`** después del tipo o alcance.
2. **Explicándolo en el pie del commit** iniciando con `BREAKING CHANGE:`.

### Ejemplo:
```txt
feat(api)!: cambiar la estructura de respuesta del endpoint de transacciones

BREAKING CHANGE: La propiedad 'amount' ahora se devuelve como entero (en centavos) en lugar de float.
```

---

## 5. Buenas Prácticas Generales

* **Sé descriptivo pero conciso:** Intenta que la primera línea no supere los 72 caracteres.
* **Commits pequeños y atómicos:** Realiza commits enfocados a una sola tarea. Es mejor hacer 3 commits pequeños que 1 gigantesco con múltiples cambios no relacionados.
* **Utiliza el inglés o español consistentemente:** Elige un idioma para tus mensajes de commit y mantén esa consistencia en todo el proyecto.
