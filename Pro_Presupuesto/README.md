# Proyect_Presupuesto

## Descripción  
**Proyect_Presupuesto** es un sistema desarrollado en **Laravel** para la gestión integral de presupuestos.  
Permite administrar clientes, productos y presupuestos, con cálculo automático de totales, control de estados, exportación a PDF y un panel de indicadores.

El objetivo principal es optimizar la creación, seguimiento y administración de presupuestos dentro de una organización.

---

## Características principales  
- **Gestión de clientes:** alta, edición, baja y búsqueda de clientes.  
- **Gestión de productos:** catálogo con código, descripción, precio y stock.  
- **Gestión de presupuestos:**  
  - Carga dinámica de ítems (producto, cantidad, precio, descuento).  
  - Cálculo automático de subtotal, descuento y total.  
  - Almacenamiento de precios históricos para consistencia.  
- **Estados de presupuesto:** Pendiente → Aceptado/Rechazado → Facturado/Cancelado.  
- **Exportación a PDF:** generación automática del documento con DomPDF.  
- **Dashboard:** estadísticas de presupuestos, clientes y montos totales.  
- **Autenticación y roles:** control de acceso con middleware (`auth`, `admin`).

---

## Tecnologías utilizadas  
- **Framework:** Laravel 10.x  
- **Lenguaje:** PHP 8.x  
- **Base de datos:** MySQL / MariaDB  
- **Frontend:** Blade + TailwindCSS / Bootstrap  
- **Generación de PDF:** `barryvdh/laravel-dompdf`  
- **Control de autenticación:** Laravel Breeze / Jetstream  
- **ORM:** Eloquent  
- **Control de versiones:** Git  

---

## Instalación

### 1. Clonar el repositorio  
```bash
git clone https://github.com/lookita/Proyect_Presupuesto.git
cd Proyect_Presupuesto
```
### 2. Instalar dependencias
```bash
composer install
npm install
```
### 3. Configurar variables de entorno
Copia el archivo .env.example a .env y configura tus credenciales:

```bash
cp .env.example
```
Edita las siguientes variables según tu entorno local:

```
APP_NAME="Proyect Presupuesto"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=presupuestos
DB_USERNAME=root
DB_PASSWORD=
```
### 4. Generar la clave de la aplicación
```
php artisan key:generate
```
### 5. Migrar y poblar la base de datos
```bash
php artisan migrate --seed
```
Los seeders crean datos de prueba: clientes, productos y un usuario administrador.

### 6. Levantar el servidor local
```bash
php artisan serve
```
Visita en tu navegador:
👉 http://localhost:8000

##  Roles y permisos
### Rol	Permisos principales

| Rol | Permisos Principales |
| :--- | :--- |
| **Administrador** | Crear, editar y eliminar clientes, productos y presupuestos. Acceso total al sistema. |
| **Usuario estándar** | Crear presupuestos, visualizar clientes y productos. No puede eliminar registros. |

Los roles se gestionan mediante middleware (isAdmin) y validaciones en las vistas Blade.

## Estructura de carpetas
```
app/
 ├── Http/
 │    ├── Controllers/
 │    │     ├── ClienteController.php
 │    │     ├── ProductoController.php
 │    │     ├── PresupuestoController.php
 │    │     └── Auth/
 │    └── Requests/
 ├── Models/
 │    ├── Cliente.php
 │    ├── Producto.php
 │    └── Presupuesto.php
 └── Services/
      └── PresupuestoService.php
resources/
 ├── views/
 │    ├── clientes/
 │    ├── productos/
 │    ├── presupuestos/
 │    └── dashboard.blade.php
routes/
 ├── web.php
 └── api.php
database/
 ├── migrations/
 ├── seeders/
 └── factories/
 ```
## Funcionalidades clave
#### Presupuestos
- Carga de cliente, fecha, productos y descuentos. Cálculo de totales y descuentos.
- Persistencia de precios históricos.
- Eliminación en cascada de detalles al borrar un presupuesto.

#### Clientes
- Código autogenerado (CLI-YYYYMMDD-XXXX).
- Validaciones en servidor y cliente.
- CRUD completo.

#### Productos
- Precio y stock administrables.
- Control de uso en presupuestos históricos.

#### PDF
- Plantilla en resources/views/presupuestos/pdf.blade.php.
- Generación vía Barryvdh\DomPDF\Facade\Pdf.

## Dashboard
- KPIs: número total de presupuestos, clientes y montos facturados.

- Resumen de estados (pendientes, aceptados, rechazados).

- Filtros rápidos por período o cliente.

## Buenas prácticas
- No eliminar registros con relaciones activas: usar softDeletes.

- Validar datos en FormRequest antes de guardar.

- Mantener actualizados los seeders para ambientes de prueba.

- Evitar exponer rutas sensibles: proteger con middleware.

- Controlar los cálculos tanto en JS (UX) como en backend (seguridad).

## Comandos útiles

| Acción | Comando |
| :--- | :--- |
| Ejecutar migraciones | `php artisan migrate` |
| Ejecutar seeders | `php artisan db:seed` |
| Limpiar caché | `php artisan optimize:clear` |
| Generar clave de app | `php artisan key:generate` |
| Levantar servidor local | `php artisan serve` |
| Ejecutar tests | `php artisan test` |

## Tests
Para ejecutar los tests automáticos:

```
php artisan test
```
## Contribuciones
Las contribuciones son bienvenidas:

1. Realizá un **fork** del repositorio.
2. Creá una rama (*feature/nueva-funcionalidad*).
3. Hacé commit con un mensaje claro.
4. Abrí un **Pull Request** explicando los cambios.