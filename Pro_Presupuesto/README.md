<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

---

# 🧾 Sistema de Gestión de Presupuestos

Proyecto académico desarrollado en Laravel con Livewire y Volt. Permite gestionar clientes, productos y presupuestos, con navegación adaptada por rol y lógica encapsulada en servicios reutilizables.

---
<!-- DIA 15 -->
## ⚙️ Requisitos técnicos

- PHP >= 8.2  
- Composer  
- Laravel >= 10  
- Node.js + Vite  
- MySQL o PostgreSQL  
- Extensiones: `pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`

---

## 🚀 Pasos de instalación

1. Clonar el repositorio:

   ```bash
   git clone https://github.com/usuario/proyecto-presupuestos.git
   cd proyecto-presupuestos

2. Instalar dependencias:

composer install
npm install && npm run dev

3. Configurar .env:

cp .env.example .env
php artisan key:generate

4. Crear base de datos y migrar:
php artisan migrate --seed

5. Iniciar servidor:
php artisan serve

## 🚀 Reinstalación en entorno nuevo 
1. Clonar el repositorio
2. Copiar `.env.example` a `.env`
3. Configurar base de datos
4. Ejecutar:
   ```bash
   composer install
   npm install
   php artisan migrate --seed
   php artisan serve

## Entrega Final
Proyecto completo de gestión de presupuestos.
Funcionalidades:
- CRUD completo (Clientes, Productos, Presupuestos)
- Relaciones entre entidades
- Exportación PDF
- Búsqueda, filtros y roles


<!-- Día 17 — Documentación técnica completa -->
🧩 1. Modelos y relaciones
📦 User
Campos: id, name, email, password, role, created_at, updated_at

Relaciones:

hasMany(Presupuesto) ← si se asocia presupuestos al usuario (opcional)

Rol en el sistema:

Controla acceso mediante auth() y auth()->user()->role

Determina qué enlaces se muestran en la navbar (Día 14)

Protege rutas con middleware (Día 17)

Campo role agregado en el Día 11 (admin o user)

📦 Cliente
Campos: id, nombre, email, codigo, created_at, updated_at

Relaciones:

hasMany(Presupuesto)

📦 Producto
Campos: id, nombre, precio, codigo, created_at, updated_at

Relaciones:

hasMany(PresupuestoDetalle)

📦 Presupuesto
Campos: id, cliente_id, fecha, estado, total, created_at, updated_at

Relaciones:

belongsTo(Cliente)

hasMany(PresupuestoDetalle)

Método addItem() para agregar detalles

📦 PresupuestoDetalle
Campos: id, presupuesto_id, producto_id, cantidad, precio_unitario, descuento_aplicado, subtotal

Relaciones:

belongsTo(Presupuesto)

belongsTo(Producto)

📊 Diagrama de relaciones
Código
User ──┐
       │
Cliente ───< Presupuesto ───< PresupuestoDetalle >─── Producto
User controla acceso y navegación

Cliente tiene muchos Presupuestos

Cada Presupuesto tiene muchos Detalles

Cada Detalle pertenece a un Producto

🧠 2. Controladores y servicios
🧾 Controladores
ClienteController: CRUD de clientes, usa ClienteService para generar código (Día 14)

ProductoController: CRUD de productos, validación con FormRequest (Día 13)

PresupuestoController: CRUD de presupuestos, usa PresupuestoService para calcular total (Día 14)

AuthController: login, logout, registro (si está presente)

📦 Servicios
ClienteService: método generarCodigo() para crear códigos únicos (Día 14)

PresupuestoService: método calcularTotal() para sumar subtotales (Día 14)

📊 Diagrama de flujo de servicios
Código
ClienteController ──> ClienteService ──> Código generado
PresupuestoController ──> PresupuestoService ──> Total calculado
🌐 3. Rutas y middleware
📄 routes/web.php
Route::resource('clientes', ClienteController::class)

Route::resource('productos', ProductoController::class)

Route::resource('presupuestos', PresupuestoController::class)

Route::patch('presupuestos/{presupuesto}/estado', [PresupuestoController::class, 'actualizarEstado'])

🛡️ Middleware
auth: protege rutas privadas

role:admin: restringe acceso a clientes y productos

role:user: acceso limitado a presupuestos

📊 Diagrama de navegación por rol
Código
[User]
 └── /presupuestos

[Admin]
 ├── /clientes
 ├── /productos
 └── /presupuestos