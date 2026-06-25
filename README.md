# 🛒 Tienda UNNE

Tienda online de merchandising de la Universidad Nacional del Nordeste (UNNE), desarrollada como proyecto académico con **Laravel 13**.

La aplicación incluye un catálogo público de productos organizados por categorías, carrito de compras, gestión de pedidos y un **panel de administración** completo para administrar productos, categorías, usuarios, pedidos y consultas.

---

## ✨ Características

- **Catálogo público** con productos por categoría (Indumentaria, Accesorios, Librería, Bazar).
- **Carrito de compras** y registro de pedidos (retiro en campus o envío a domicilio).
- **Autenticación de usuarios** (clientes y administradores).
- **Panel de administración** (`/admin`) protegido por rol, con ABM de:
  - Productos
  - Categorías
  - Usuarios
  - Pedidos
  - Consultas/mensajes del público

## 🧰 Tecnologías

- **Backend:** PHP 8.3+ / Laravel 13
- **Frontend:** Blade + Vite
- **Base de datos:** SQLite (no requiere instalar ningún servidor de base de datos)

---

## 🚀 Instalación y puesta en marcha

> El proyecto viene preparado para usar **SQLite**, por lo que **no necesitás instalar MySQL ni MariaDB**. SQLite ya viene incluido con PHP.

### Requisitos previos

- **PHP 8.3 o superior** (con la extensión `pdo_sqlite`, habilitada por defecto)
- **Composer**
- **Node.js + npm**

> 💡 La forma más sencilla de tener PHP listo en Windows/Mac es instalar [Laravel Herd](https://herd.laravel.com/), que incluye PHP y Composer.

### Pasos

1. **Clonar el repositorio**
   ```bash
   git clone <URL-DEL-REPOSITORIO>
   cd tienda_unne
   ```

2. **Instalar dependencias de PHP**
   ```bash
   composer install
   ```

3. **Instalar dependencias de frontend y compilar**
   ```bash
   npm install
   npm run build
   ```

4. **Crear el archivo de configuración** (copiar la plantilla)
   - En **Windows (CMD/PowerShell)**:
     ```bash
     copy .env.example .env
     ```
   - En **Mac/Linux**:
     ```bash
     cp .env.example .env
     ```

5. **Generar la clave de la aplicación**
   ```bash
   php artisan key:generate
   ```

6. **Crear el archivo de base de datos SQLite (vacío)**
   - En **Windows (CMD/PowerShell)**:
     ```bash
     type nul > database\database.sqlite
     ```
   - En **Mac/Linux**:
     ```bash
     touch database/database.sqlite
     ```

7. **Crear las tablas y cargar los datos** (productos, categorías y usuario admin)
   ```bash
   php artisan migrate --seed
   ```

8. **Levantar el servidor**
   ```bash
   php artisan serve
   ```
   Luego abrir en el navegador: **http://localhost:8000**

   > Si usás **Laravel Herd**, el sitio queda disponible automáticamente en `http://tienda_unne.test` y podés saltear este paso.

---

## 🔑 Acceso al Panel de Administración

Una vez instalado, ya existe un usuario administrador cargado automáticamente:

| Campo | Valor |
|-------|-------|
| **URL de ingreso** | http://localhost:8000/login |
| **Correo** | `admin@tienda.com` |
| **Contraseña** | `admin1234` |

Al iniciar sesión con estas credenciales serás redirigido automáticamente al panel: **http://localhost:8000/admin/dashboard**

> El panel está protegido: solo los usuarios con rol `admin` pueden acceder. Los visitantes pueden registrarse y comprar como clientes.

---

## 📦 Datos incluidos

Al ejecutar `php artisan migrate --seed` se cargan automáticamente:

- **4 categorías:** Indumentaria, Accesorios, Librería y Bazar.
- **36 productos** con sus precios, stock, descripciones e **imágenes** (las imágenes ya vienen incluidas en el repositorio, en `public/img/`).
- **1 usuario administrador** (ver credenciales arriba).

Esto significa que, apenas instalado, el sitio ya se ve con el catálogo completo y listo para usar.

---

## ❓ Problemas frecuentes

- **"could not find driver" al migrar:** asegurate de tener habilitada la extensión `pdo_sqlite` en tu PHP (en Herd ya viene activa).
- **No aparecen estilos:** ejecutá `npm run build` (paso 3).
- **Las imágenes no se ven:** verificá que el paso de `migrate --seed` se haya ejecutado sin errores; las imágenes se referencian desde `public/img/`.
- **Empezar de cero:** para borrar todo y recargar los datos, ejecutá `php artisan migrate:fresh --seed`.
