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