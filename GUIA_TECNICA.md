# TiendaUNNE — Guía Técnica Completa
### Cómo funciona el proyecto a nivel universitario

---

## Índice

1. [El patrón MVC](#1-el-patrón-mvc)
2. [El ciclo de vida de una Request](#2-el-ciclo-de-vida-de-una-request)
3. [Las Rutas — routes/web.php](#3-las-rutas)
4. [Los Controllers](#4-los-controllers)
5. [Los Modelos y Eloquent ORM](#5-los-modelos-y-eloquent-orm)
6. [Las Relaciones entre Modelos](#6-las-relaciones-entre-modelos)
7. [La Base de Datos y las Migraciones](#7-la-base-de-datos-y-las-migraciones)
8. [El Middleware](#8-el-middleware)
9. [La Autenticación y las Sesiones](#9-la-autenticación-y-las-sesiones)
10. [El Carrito de Compras](#10-el-carrito-de-compras)
11. [Los Pedidos y Transacciones DB](#11-los-pedidos-y-transacciones-db)
12. [Formularios, CSRF y Validaciones](#12-formularios-csrf-y-validaciones)
13. [Las Vistas — Blade Templates](#13-las-vistas--blade-templates)
14. [Flujo completo: Panel Admin](#14-flujo-completo-panel-admin)
15. [Resumen para el examen](#15-resumen-para-el-examen)

---

## 1. El patrón MVC

### Técnico

Laravel implementa el patrón **MVC (Model-View-Controller)**. Este patrón separa la aplicación en tres capas con responsabilidades distintas:

- **Model**: representa y gestiona los datos (habla con la base de datos)
- **View**: es la presentación (el HTML que ve el usuario)
- **Controller**: es el intermediario (recibe la request, le pide datos al Model, se los manda a la View)

```
Usuario → Controller → Model → Base de Datos
                    ↓
                   View → HTML → Usuario
```

### En criollo

Imaginá una pizzería. El **Model** es la cocina (sabe cómo hacer las pizzas y dónde están los ingredientes). La **View** es la mesa donde te traen la pizza (lo que ves). El **Controller** es el mozo (vos le pedís algo, él va a la cocina, y te trae lo que pediste). Vos nunca vas a la cocina directamente.

---

## 2. El ciclo de vida de una Request

### Técnico

Cuando el usuario hace algo (entra a una URL, hace click en un botón), sucede esto en orden:

```
Usuario → Navegador → Servidor (Laravel) → routes/web.php → Middleware → Controller → Model → View → HTML
```

1. El navegador manda una **HTTP Request** (GET, POST, PUT, DELETE)
2. Laravel la recibe en `public/index.php` (el único punto de entrada)
3. El framework busca en `routes/web.php` qué controller debe manejarla
4. Pasa por los **Middleware** (filtros de seguridad)
5. Llega al **Controller** que ejecuta la lógica
6. El Controller consulta al **Model** si necesita datos
7. El Model habla con la **Base de Datos** y devuelve los datos
8. El Controller manda los datos a la **View** (Blade)
9. La View genera el HTML y Laravel lo manda de vuelta al navegador

### En criollo

Es como cuando llamás a un restaurant:
1. Vos llamás (HTTP Request)
2. Te atiende la recepcionista (routes/web.php) que sabe a quién derivarte
3. Antes de atenderte chequean si sos cliente válido (Middleware)
4. Te atiende el responsable del área (Controller)
5. El responsable consulta al almacén (Model → Base de datos)
6. Te responden con la información (View → HTML)

---

## 3. Las Rutas

**Archivo:** `routes/web.php`

### Técnico

Las rutas mapean una **URL + método HTTP** a un **Controller@método**.

```php
// GET /productos → llama a ProductController::catalogo()
Route::get('/productos', [ProductController::class, 'catalogo']);

// POST /login → llama a AuthController::login()
Route::post('/login', [AuthController::class, 'login']);

// PUT /perfil/actualizar → llama a ProfileController::update()
Route::put('/perfil/actualizar', [ProfileController::class, 'update']);

// DELETE /carrito/quitar/{id} → llama a CartController::remove()
Route::delete('/carrito/quitar/{id}', [CartController::class, 'remove']);
```

**Grupos con Middleware** — rutas protegidas:

```php
// Solo usuarios logueados pueden acceder
Route::middleware(['auth'])->group(function () {
    Route::get('/carrito', [CartController::class, 'index']);
    Route::post('/checkout/procesar', [OrderController::class, 'store']);
});

// Solo admins logueados pueden acceder
Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index']);
    Route::get('/admin/pedidos',   [OrderController::class, 'adminIndex']);
});
```

**Los métodos HTTP tienen semántica definida:**

| Método | Acción | Ejemplo en el proyecto |
|--------|--------|----------------------|
| GET    | Pedir/ver datos | Ver el catálogo, ver el carrito |
| POST   | Crear/enviar datos | Login, registrarse, enviar contacto |
| PUT    | Actualizar datos | Actualizar perfil, cambiar estado de pedido |
| DELETE | Eliminar datos | Quitar producto del carrito, suspender usuario |

### En criollo

El archivo de rutas es como el índice de un libro. Dice: *"si alguien pide la página /productos, mandalo al capítulo ProductController, sección catalogo"*. Los grupos con middleware son como secciones del libro con candado: solo podés leerlas si tenés la llave.

---

## 4. Los Controllers

**Carpeta:** `app/Http/Controllers/`

### Técnico

Un Controller es una clase PHP que agrupa métodos relacionados a un recurso. Cada método recibe un objeto `Request` (con todos los datos que mandó el usuario) y devuelve una `Response` (redirect o view).

Después del refactor, cada controller hace **exactamente tres cosas**:

1. Validar la entrada del usuario
2. Delegar la lógica al modelo
3. Devolver una respuesta

```php
// OrderController::store() — ejemplo del patrón limpio
public function store(Request $request)
{
    // 1. VALIDAR — rechaza si los datos son incorrectos
    $request->validate([
        'delivery_type' => 'required|in:campus,domicilio',
        'address'       => 'required_if:delivery_type,domicilio|nullable|string|max:255',
    ]);

    // 2. DELEGAR AL MODELO — toda la lógica va acá
    $orden = Order::crearDesdeCarrito(
        session()->get('cart'),
        $request->delivery_type,
        $request->address,
        Auth::id()
    );

    // 3. RESPONDER — redirect con mensaje
    session()->forget('cart');
    return redirect('/productos')->with('success', "Pedido #{$orden->id} realizado.");
}
```

**Controllers del proyecto y su responsabilidad:**

| Controller | Responsabilidad |
|---|---|
| `AuthController` | Login, registro, logout |
| `CartController` | Agregar, quitar, vaciar el carrito |
| `OrderController` | Checkout, procesar compra, gestión admin de pedidos |
| `ProductController` | Catálogo público y CRUD admin de productos |
| `UserController` | CRUD admin de usuarios y admins |
| `CategoryController` | CRUD admin de categorías |
| `MessageController` | Formulario de contacto y gestión admin de consultas |
| `ProfileController` | Ver y actualizar perfil del cliente |
| `DashboardController` | Métricas y estadísticas del panel admin |

### En criollo

El controller es el mozo de la pizzería. No cocina, no toma reservas, no hace la caja. Solo recibe tu pedido, lo lleva a la cocina y te trae la pizza. Si pedís algo que no está en el menú (validación falla), te dice "no tenemos eso" sin ni siquiera ir a la cocina.

---

## 5. Los Modelos y Eloquent ORM

**Carpeta:** `app/Models/`

### Técnico

Un **Model** en Laravel representa una tabla de la base de datos. Laravel incluye **Eloquent ORM** (Object-Relational Mapper), que permite trabajar con la BD usando objetos PHP en lugar de escribir SQL a mano.

Cada **instancia del modelo = una fila de la tabla**.

```php
// SIN Eloquent (SQL crudo):
$resultado = DB::select("SELECT * FROM products WHERE id = 5 AND deleted_at IS NULL");

// CON Eloquent (orientado a objetos):
$producto = Product::find(5);
echo $producto->name;    // accede a la columna "name"
echo $producto->price;   // accede a la columna "price"
$producto->stock -= 1;
$producto->save();       // UPDATE products SET stock = stock-1 WHERE id = 5
```

**`$fillable`** — Protección contra Mass Assignment:

```php
// Solo estos campos pueden asignarse en masa (ej: Product::create($request->all()))
protected $fillable = ['name', 'price', 'stock', 'category_id', 'image', 'is_active'];

// 'role' NO está en fillable de User porque no queremos que alguien
// pueda mandarse role=admin desde un formulario malicioso
```

**Casts** — Conversión automática de tipos:

```php
protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime', // convierte string a objeto Carbon
        'password'          => 'hashed',   // hashea automáticamente al guardar
    ];
}
```

**Scopes** — Filtros reutilizables:

```php
// Definido en Product.php:
public function scopeActivos($query)
{
    return $query->where('is_active', 1);
}

// Usado en cualquier parte del proyecto:
Product::activos()->get();         // SELECT * FROM products WHERE is_active = 1
Product::activos()->where(...)->paginate(15); // se puede encadenar
```

**SoftDeletes** — Borrado lógico:

```php
use SoftDeletes; // activar el trait en el modelo

$producto->delete();              // UPDATE products SET deleted_at = NOW() WHERE id=5
                                  // NO borra el registro de la BD

$producto->restore();             // UPDATE products SET deleted_at = NULL WHERE id=5

Product::all();                   // SELECT * FROM products WHERE deleted_at IS NULL
Product::withTrashed()->get();    // SELECT * FROM products (incluye eliminados)
Product::onlyTrashed()->get();    // SELECT * FROM products WHERE deleted_at IS NOT NULL
```

**Métodos de negocio en los modelos** (patrón Fat Model):

```php
// En lugar de tener esta lógica en el controller, vive en el modelo
// Product.php
public function desactivar(): void
{
    $this->is_active = 0;
    $this->save();
    $this->delete(); // SoftDelete
}

public static function filtrarCarrito(array $cart): array
{
    $idsActivos = self::whereIn('id', array_keys($cart))
        ->where('is_active', 1)
        ->pluck('id')
        ->toArray();
    return array_filter($cart, fn($id) => in_array($id, $idsActivos), ARRAY_FILTER_USE_KEY);
}
```

### En criollo

Eloquent es un traductor. Vos le hablás en PHP y él habla SQL con la base de datos. Es como tener un intérprete: vos decís `Product::find(5)` y el intérprete le dice a la BD `SELECT * FROM products WHERE id = 5`.

Los SoftDeletes son como mandar algo a la papelera de reciclaje. El archivo sigue ahí pero no lo ves. Podés recuperarlo cuando quieras. Cuando "eliminás" un producto o suspendés un usuario en esta app, en realidad solo les ponés una fecha en la columna `deleted_at`. No se borran nunca de verdad.

---

## 6. Las Relaciones entre Modelos

### Técnico

Las relaciones entre tablas se definen directamente en los modelos. Eloquent genera automáticamente el SQL de los JOINs.

```php
// HasMany — Un User TIENE MUCHOS Orders
// User.php
public function orders() {
    return $this->hasMany(Order::class);
    // SQL: SELECT * FROM orders WHERE user_id = {$this->id}
}

// BelongsTo — Un Order PERTENECE A UN User
// Order.php
public function user() {
    return $this->belongsTo(User::class)->withTrashed();
    // withTrashed() → también carga usuarios suspendidos (para no perder historial)
    // SQL: SELECT * FROM users WHERE id = {$this->user_id}
}

// Un Order TIENE MUCHOS OrderItems
public function items() {
    return $this->hasMany(OrderItem::class);
}

// Un OrderItem PERTENECE A UN Product
// OrderItem.php
public function product() {
    return $this->belongsTo(Product::class);
}
```

**Diagrama de relaciones:**

```
users ──(1:N)──► orders ──(1:N)──► order_items ──(N:1)──► products ──(N:1)──► categories
  │
  └──(1:N)──► messages
```

**Eager Loading** con `with()` — evita el problema N+1:

```php
// MAL — Problema N+1 (si hay 10 pedidos, hace 11 queries):
$pedidos = Order::all();
foreach ($pedidos as $p) {
    echo $p->user->name; // cada iteración hace 1 SELECT extra
}
// Resultado: 1 query para pedidos + 10 queries para users = 11 queries

// BIEN — Eager Loading (siempre 2 queries sin importar cuántos pedidos haya):
$pedidos = Order::with('user')->get();
// Query 1: SELECT * FROM orders
// Query 2: SELECT * FROM users WHERE id IN (1, 2, 3, ...)
foreach ($pedidos as $p) {
    echo $p->user->name; // ya está en memoria, no hace query
}

// Se puede anidar:
Order::with(['user', 'items.product'])->get();
// Carga: pedidos + users + items de cada pedido + producto de cada item
```

### En criollo

Las relaciones son como decirle a Laravel: *"cuando me traigas un pedido, traeme también automáticamente al usuario que lo hizo, y los productos que tiene"*. Sin relaciones, tendrías que hacer esa búsqueda a mano cada vez.

El `with()` (eager loading) es para ser eficiente: en lugar de ir al almacén 10 veces para buscar 10 pedidos, vas una sola vez y traés todo junto. Sin esto, por cada pedido que mostrás en pantalla, hacés una consulta extra a la BD — con 100 pedidos serían 101 consultas innecesarias.

---

## 7. La Base de Datos y las Migraciones

**Carpeta:** `database/migrations/`

### Técnico

En lugar de crear tablas manualmente, Laravel usa **Migraciones**: archivos PHP que describen la estructura y se ejecutan con `php artisan migrate`.

```php
// database/migrations/2026_05_30_035754_create_orders_table.php
Schema::create('orders', function (Blueprint $table) {
    $table->id();                              // columna id autoincremental (PK)
    $table->foreignId('user_id')               // FK → users.id
          ->constrained()
          ->onDelete('cascade');
    $table->decimal('total', 10, 2);           // número con 10 dígitos y 2 decimales
    $table->enum('delivery_type', ['campus', 'domicilio']);
    $table->string('address')->nullable();      // puede ser NULL
    $table->enum('status', ['pendiente', 'en_preparacion', 'enviado', 'entregado']);
    $table->timestamp('dispatched_at')->nullable();
    $table->timestamps();                      // agrega created_at y updated_at
});
```

**Tablas del proyecto y su función:**

| Tabla | Filas | Para qué |
|---|---|---|
| `users` | Clientes y admins | Autenticación y perfiles |
| `products` | Productos de la tienda | Catálogo, stock |
| `categories` | Categorías de productos | Clasificación |
| `orders` | Cabecera de cada pedido | Total, estado, dirección |
| `order_items` | Líneas de cada pedido | Qué productos y en qué cantidad |
| `messages` | Consultas de contacto | Soporte al cliente |
| `sessions` | Sesiones activas | Login y carrito |
| `jobs` | Cola de trabajos | Tareas en background |

**Comandos de migración:**

```bash
php artisan migrate              # crea/actualiza tablas
php artisan migrate:rollback     # deshace la última migración
php artisan migrate:fresh        # borra todo y vuelve a crear (dev)
php artisan db:seed              # ejecuta los Seeders
```

**Seeders** — datos iniciales:

```php
// database/seeders/CategoriaSeeder.php
Category::create(['name' => 'Indumentaria']);
Category::create(['name' => 'Accesorios']);
Category::create(['name' => 'Librería y Estudio']);
Category::create(['name' => 'Hogar y Utilidad']);
```

### En criollo

Las migraciones son como los planos de una construcción. En lugar de construir la base de datos a mano cada vez, tenés los planos guardados en archivos PHP. Si alguien nuevo entra al equipo, ejecuta `php artisan migrate` y le crea la base de datos exacta. Si cometés un error, podés hacer `php artisan migrate:rollback` y deshacer.

Los Seeders son como el día en que abrís el negocio y cargás el inventario inicial antes de abrir al público.

---

## 8. El Middleware

**Carpeta:** `app/Http/Middleware/`

### Técnico

Un Middleware es código que se ejecuta **antes** de que la request llegue al controller. Actúa como un filtro o guardia. Se pueden encadenar múltiples middlewares.

**Middleware `auth`** (incluido en Laravel):
- Chequea si hay una sesión activa
- Si no está logueado → redirige a `/login`

**Middleware `IsAdmin`** (creado en el proyecto):

```php
// app/Http/Middleware/IsAdmin.php
public function handle(Request $request, Closure $next): Response
{
    // ¿Está logueado Y es admin?
    if (!Auth::check() || Auth::user()->role !== 'admin') {
        abort(403); // HTTP 403 Forbidden — acceso denegado
    }

    // Pasó los controles → dejar pasar al controller
    return $next($request);
}
```

**Cómo se aplican en las rutas:**

```php
// Sin middleware → cualquiera puede acceder
Route::get('/productos', [ProductController::class, 'catalogo']);

// Con 'auth' → solo logueados
Route::middleware(['auth'])->group(function () {
    Route::get('/perfil', [ProfileController::class, 'index']);
});

// Con 'auth' + IsAdmin → solo admins logueados
Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index']);
});
```

**Flujo de un request con middleware:**

```
GET /admin/dashboard
        │
        ▼
   Middleware 'auth'
   ¿Está logueado?
   NO → redirect /login
   SÍ → ↓
        │
        ▼
   Middleware IsAdmin
   ¿Es admin?
   NO → abort(403)
   SÍ → ↓
        │
        ▼
   DashboardController::index()
```

### En criollo

El middleware es el guardia de seguridad en la puerta. `auth` chequea si tenés credenciales (¿estás logueado?). `IsAdmin` chequea si además tenés el carnet de administrador. Si no tenés lo que piden, te mandan de vuelta. El controller ni se entera de que exististe.

---

## 9. La Autenticación y las Sesiones

### Técnico

Laravel maneja la sesión en la base de datos (tabla `sessions`). Flujo completo de login:

```php
// AuthController::login()
$user = User::where('email', $request->email)->first();

// Verificación de credenciales
// Hash::check() compara texto plano contra hash bcrypt sin exponer la contraseña
if (!$user || !Hash::check($request->password, $user->password)) {
    return back()->withErrors(['email' => 'Credenciales incorrectas.']);
}

// Verificación de cuenta activa
if (!$user->is_active) {
    return back()->withErrors(['email' => 'Tu cuenta está desactivada.']);
}

Auth::login($user);                  // guarda user_id en la sesión
$request->session()->regenerate();   // nuevo ID de sesión (previene session fixation)
```

**Cómo funciona la sesión (esquema):**

```
Primera request (login):
Navegador ──POST /login──► Servidor
                            └─ Crea registro en tabla sessions:
                               { id: "abc123", payload: { user_id: 5 }, ... }
                            ◄── Set-Cookie: laravel_session=abc123

Requests siguientes:
Navegador ──GET /perfil──► Servidor
Cookie: laravel_session=abc123
                            └─ Busca en sessions WHERE id='abc123'
                               Encuentra user_id=5
                               Auth::user() → devuelve User con id=5
                            ◄── HTML del perfil de ese usuario
```

**Hash de contraseñas (bcrypt):**

```
Contraseña real: "MiPassword123"
                      ↓ Hash::make()
Hash guardado:  "$2y$12$abc123XYZ..."  ← esto se guarda en la BD, nunca la original

Al verificar: Hash::check("MiPassword123", "$2y$12$abc123XYZ...") → true/false
```

El hash es **unidireccional**: podés verificar si coincide, pero es imposible obtener la contraseña original a partir del hash.

**User enumeration — problema que corregimos:**

```php
// ANTES (inseguro — permite saber qué emails están registrados):
if (!$user) {
    return back()->withErrors(['email' => 'El correo NO está registrado.']); // ← reveló info
}
if (!Hash::check($request->password, $user->password)) {
    return back()->withErrors(['password' => 'La contraseña es incorrecta.']); // ← diferente mensaje
}

// DESPUÉS (seguro — mismo mensaje para ambos casos):
if (!$user || !Hash::check($request->password, $user->password)) {
    return back()->withErrors(['email' => 'Las credenciales no coinciden con nuestros registros.']);
}
```

### En criollo

El login es como entrar a un club con credencial. Te revisamos si la credencial es válida, y si sí, te damos una pulsera (la cookie de sesión). Cada vez que pedís algo adentro del club, mostrás la pulsera y sabemos quién sos sin que tengas que mostrar la credencial de nuevo.

El hash de contraseña es como guardar la huella digital en lugar de la contraseña real: podés verificar si es la misma persona, pero si alguien roba la base de datos no puede saber cuál era la contraseña original.

El problema de user enumeration es cuando el sistema da pistas distintas ("el email no existe" vs "la contraseña es incorrecta"). Un atacante puede usarlo para descubrir qué emails están registrados. La solución: siempre el mismo mensaje, sin importar qué falló.

---

## 10. El Carrito de Compras

### Técnico

El carrito **no se guarda en la base de datos** — vive en la **sesión de PHP** del servidor.

**Estructura del carrito en sesión:**

```php
session()->get('cart') // devuelve:
[
    15 => [                          // 15 = product_id (es la clave del array)
        'name'     => 'Remera UNNE',
        'quantity' => 2,
        'price'    => 5000.00,
        'image'    => 'remera.jpg'
    ],
    22 => [
        'name'     => 'Mate UNNE',
        'quantity' => 1,
        'price'    => 3500.00,
        'image'    => 'mate.jpg'
    ]
]
```

**Operaciones sobre el carrito:**

```php
// Leer
$cart = session()->get('cart', []);  // [] si está vacío

// Agregar producto
$cart[$id] = ['name' => $producto->name, 'quantity' => $nuevaCantidad, ...];
session()->put('cart', $cart);

// Quitar un producto
unset($cart[$id]);
session()->put('cart', $cart);

// Vaciar todo
session()->forget('cart');  // al finalizar la compra
```

**Filtrado del carrito — limpieza de productos eliminados:**

```php
// Product::filtrarCarrito() en el modelo
public static function filtrarCarrito(array $cart): array
{
    $idsActivos = self::whereIn('id', array_keys($cart))
        ->where('is_active', 1)
        ->pluck('id')
        ->toArray();
    // Si el producto fue eliminado o desactivado, no aparece en $idsActivos
    // array_filter retorna solo los items cuyo ID sí está activo
    return array_filter($cart, fn($id) => in_array($id, $idsActivos), ARRAY_FILTER_USE_KEY);
}
```

**Seguridad — Price Tampering (vulnerabilidad corregida):**

```php
// ANTES (inseguro): precio venía de la sesión
// Un usuario podría modificar $_SESSION['cart'][5]['price'] = 0.01
foreach ($cart as $details) {
    $total += $details['price'] * $details['quantity']; // ← precio manipulable
}

// DESPUÉS (seguro): precio siempre desde la BD
foreach ($cart as $id => $details) {
    $producto = Product::lockForUpdate()->find($id); // ← precio real
    $total += $producto->price * $details['quantity'];
}
```

### En criollo

El carrito es como una nota pegada en el servidor que dice "este navegador tiene estos productos elegidos". No es permanente: si cerrás el navegador, se va. Es como el carrito físico de un supermercado — mientras estás adentro lo tenés, pero si te vas lo dejás.

La vulnerabilidad de price tampering es cuando alguien edita su sesión para poner `price = 0` y comprar gratis. La solución: nunca confiar en el precio que viene del navegador, siempre buscarlo en la base de datos en el momento del pago.

---

## 11. Los Pedidos y Transacciones DB

### Técnico

Al confirmar una compra ocurren múltiples operaciones que deben ser **atómicas** (todas o ninguna):

1. Crear el registro en `orders`
2. Crear N registros en `order_items`
3. Descontar stock de cada producto en `products`

Si el paso 3 falla (no hay stock), los pasos 1 y 2 deben revertirse. Se usa una **transacción de base de datos**:

```php
// Order::crearDesdeCarrito() en el modelo
DB::transaction(function () use ($cart, $deliveryType, $address, $userId) {

    $total = 0;
    $itemsValidados = [];

    foreach ($cart as $id => $details) {
        // lockForUpdate() bloquea la fila mientras dura la transacción
        // Evita race conditions (dos usuarios comprando el último producto al mismo tiempo)
        $producto = Product::lockForUpdate()->find($id);

        if (!$producto) {
            throw new \Exception("Producto retirado del catálogo."); // → ROLLBACK
        }
        if ($producto->stock < $details['quantity']) {
            throw new \Exception("Sin stock suficiente.");           // → ROLLBACK
        }

        $total += $producto->price * $details['quantity'];
        $itemsValidados[] = ['producto' => $producto, ...];
    }

    // Solo si TODOS los productos pasaron las validaciones → creamos la orden
    $orden = Order::create(['user_id' => $userId, 'total' => $total, ...]);

    foreach ($itemsValidados as $item) {
        $orden->items()->create([...]);                         // INSERT en order_items
        $item['producto']->decrement('stock', $item['quantity']); // UPDATE stock
    }

    return $orden;
    // Si llega acá → COMMIT (todo se guarda permanentemente)

}); // Si lanzó una excepción → ROLLBACK automático (nada se guardó)
```

**Estados del pedido:**

```
pendiente → en_preparacion → listo_para_retirar → entregado
                          → enviado → entregado
```

Al cambiar a `enviado` o `entregado`, se registra automáticamente `dispatched_at = NOW()`.

### En criollo

Una transacción es como firmar un contrato con escribano. O lo firman las dos partes y vale, o si alguien no firma se rompe todo y es como si nunca hubieran ido. En tu tienda: o se crea el pedido, se registran los productos Y se baja el stock (todo junto), o si algo falla, no queda nada a medias. Sin transacciones podrías tener un pedido creado pero con el stock sin descontar — un desastre.

El `lockForUpdate()` es como cuando en la caja del supermercado el cajero bloquea el precio mientras te está cobrando. Si dos personas están comprando el último producto exactamente al mismo tiempo, solo una lo logra — la otra recibe el error de "sin stock".

---

## 12. Formularios, CSRF y Validaciones

### Técnico

**CSRF (Cross-Site Request Forgery):**

Laravel agrega automáticamente protección CSRF. Cada formulario debe incluir `@csrf`:

```blade
<form method="POST" action="/checkout/procesar">
    @csrf
    {{-- Genera: <input type="hidden" name="_token" value="xK8mP2..."> --}}
</form>
```

Laravel verifica que el token llegue en cada POST/PUT/DELETE. Si no coincide → rechaza la request. Protege contra ataques donde un sitio externo intenta hacer requests en nombre del usuario logueado.

**Validaciones en el controller:**

```php
$request->validate([
    'name'      => 'required|string|max:255',
    'email'     => 'required|email:filter|max:255|unique:users',
    //              ↑ no vacío  ↑ formato  ↑ máx chars  ↑ no repetido en BD
    'password'  => 'required|min:8|confirmed',
    //                        ↑ largo  ↑ debe coincidir con password_confirmation
    'price'     => 'required|numeric|min:0',
    'category_id' => 'required|exists:categories,id', // debe existir en la tabla
]);
// Si falla → automáticamente redirige ATRÁS con los errores en $errors
// Si pasa → continúa la ejecución normalmente
```

**Reglas de validación usadas en el proyecto:**

| Regla | Significa |
|---|---|
| `required` | No puede estar vacío |
| `string` | Debe ser texto |
| `email:filter` | Formato de email válido (validación estricta) |
| `max:255` | Máximo 255 caracteres |
| `min:8` | Mínimo 8 caracteres |
| `unique:users,email` | No puede existir ya en la tabla users, columna email |
| `exists:categories,id` | Debe existir en la tabla categories, columna id |
| `in:campus,domicilio` | Solo puede ser uno de estos valores |
| `required_if:campo,valor` | Requerido solo si otro campo tiene cierto valor |
| `nullable` | Puede ser null/vacío |
| `confirmed` | Debe coincidir con campo_confirmation |
| `different:otro_campo` | Debe ser distinto al campo indicado |
| `image` | Debe ser un archivo de imagen |
| `max:2048` | Tamaño máximo en KB (para archivos) |

**Mostrar errores en la vista:**

```blade
<input type="email" name="email" value="{{ old('email') }}">
@error('email')
    <span class="text-danger">{{ $message }}</span>
@enderror
```

### En criollo

El CSRF es como poner un sello único en cada formulario. Cuando el formulario llega al servidor, chequea que el sello sea el correcto. Evita que un sitio malicioso pueda enviar formularios en tu nombre sin que te des cuenta.

La validación es el filtro antes de guardar datos. En lugar de guardar basura en la base de datos y arreglarlo después, lo cortamos de entrada: si el email no tiene `@`, si la contraseña es muy corta, si el campo está vacío — se rechaza antes de tocar la BD y el usuario ve un mensaje de error claro.

---

## 13. Las Vistas — Blade Templates

**Carpeta:** `resources/views/`

### Técnico

Blade es el motor de templates de Laravel. Los archivos `.blade.php` son HTML con sintaxis especial que se compila a PHP puro y se guarda en caché.

```blade
{{-- Comentario (no aparece en el HTML final) --}}

{{ $variable }}         {{-- Escapa HTML — convierte < > en &lt; &gt; (previene XSS) --}}
{!! $variable !!}       {{-- NO escapa — peligroso si el valor viene del usuario --}}

@if ($user->esAdmin())
    <a href="/admin">Panel Admin</a>
@elseif ($condicion)
    ...
@else
    ...
@endif

@foreach ($productos as $producto)
    <p>{{ $producto->name }}: ${{ $producto->price }}</p>
@empty
    <p>No hay productos.</p>  {{-- si $productos está vacío --}}
@endforeach

@forelse ($items as $item)   {{-- como foreach pero con @empty integrado --}}
    <p>{{ $item->name }}</p>
@empty
    <p>Lista vacía.</p>
@endforelse

@auth     {{-- solo si está logueado --}}
    Hola {{ Auth::user()->name }}
@endauth

@guest    {{-- solo si NO está logueado --}}
    <a href="/login">Iniciar sesión</a>
@endguest

{{-- Sistema de layouts: herencia de plantillas --}}
@extends('layouts.admin')       {{-- usa admin.blade.php como plantilla base --}}

@section('title', 'Pedidos')    {{-- reemplaza el @yield('title') del layout --}}

@section('content')             {{-- reemplaza el @yield('content') del layout --}}
    <h1>Lista de pedidos</h1>
    ...
@endsection

{{-- Incluir parciales reutilizables --}}
@include('partials.navbar')
@include('partials.footer')
```

**Pasar datos del controller a la vista:**

```php
// Controller:
return view('admin.pedidos', compact('pedidos', 'pedidosEntregados'));
// Equivalente a:
return view('admin.pedidos', ['pedidos' => $pedidos, 'pedidosEntregados' => $pedidosEntregados]);
```

### En criollo

Blade es un HTML con superpoderes. Podés poner condiciones (`@if`), repeticiones (`@foreach`), y mostrar variables de PHP directamente. El `{{ }}` es seguro (escapa caracteres peligrosos como `<script>`), el `{!! !!}` es peligroso (muestra HTML crudo — solo usar con datos del sistema, nunca de usuarios).

Los layouts evitan copiar y pegar la navbar y el footer en cada página: lo definís una vez y todas las vistas lo heredan. Si querés cambiar la navbar, la cambiás en un solo lugar y se actualiza en toda la app.

---

## 14. Flujo Completo: Panel Admin

Ejemplo paso a paso de cuando un admin entra a `/admin/pedidos`:

```
1. NAVEGADOR
   GET /admin/pedidos
   Cookie: laravel_session=abc123
        │
        ▼
2. public/index.php (único punto de entrada de Laravel)
        │
        ▼
3. routes/web.php
   → Encuentra: Route::middleware(['auth', IsAdmin::class])
                     Route::get('/admin/pedidos', [OrderController::class, 'adminIndex'])
        │
        ▼
4. Middleware 'auth'
   → Busca en tabla sessions WHERE id='abc123'
   → Encuentra user_id=7
   → Auth::user() = User{id:7, role:'admin'}
   → ¿Está logueado? SÍ → pasar al siguiente middleware
        │
        ▼
5. Middleware IsAdmin
   → Auth::user()->role === 'admin' → VERDADERO
   → ¿Es admin? SÍ → pasar al controller
        │
        ▼
6. OrderController::adminIndex(Request $request)
   → Llama: Order::filtrados(null, null, null)
   → Eloquent genera SQL:
       SELECT orders.*, users.*, order_items.*, products.*
       FROM orders
       LEFT JOIN users ON orders.user_id = users.id
       ...
       WHERE orders.status != 'entregado'
       ORDER BY orders.created_at DESC
   → Resultado: colección de objetos Order con relaciones cargadas
   → return view('admin.pedidos', compact('pedidos', 'pedidosEntregados'))
        │
        ▼
7. Blade: resources/views/admin/pedidos.blade.php
   → @extends('layouts.admin') → toma el layout base
   → @foreach ($pedidos as $pedido)
       {{ $pedido->id }}
       {{ $pedido->user->name }}
       {{ $pedido->total }}
   @endforeach
   → Genera HTML final
        │
        ▼
8. RESPUESTA
   HTTP 200 OK
   Content-Type: text/html
   [HTML con la tabla de pedidos]
        │
        ▼
9. NAVEGADOR muestra la página de pedidos al admin
```

---

## 15. Resumen para el examen

### Conceptos clave

| Concepto | Qué es | Para qué sirve en el proyecto |
|---|---|---|
| **MVC** | Patrón de arquitectura Model-View-Controller | Organiza y separa responsabilidades |
| **Route** | Mapeo de URL+método HTTP → Controller@método | Define qué código ejecuta cada URL |
| **Controller** | Clase PHP que recibe requests y devuelve responses | Valida, delega y responde |
| **Model** | Clase PHP que representa una tabla de BD | Lógica de negocio + acceso a datos |
| **Eloquent ORM** | Mapeador objeto-relacional de Laravel | PHP en vez de SQL para la BD |
| **Migration** | Script PHP que define la estructura de tablas | Versionado y portabilidad de la BD |
| **Seeder** | Script que inserta datos iniciales | Categorías, usuario admin de prueba |
| **Middleware** | Filtro que se ejecuta antes del controller | Autenticación (`auth`) y autorización (`IsAdmin`) |
| **Sesión** | Estado del usuario guardado en el servidor | Mantiene el login y el carrito activos |
| **Cookie** | Token enviado al navegador para identificar la sesión | `laravel_session` |
| **CSRF Token** | Token único por sesión en formularios | Protege contra request forgery |
| **SoftDelete** | Borrado lógico con columna `deleted_at` | Desactivar productos/usuarios sin perder historial |
| **Transacción DB** | Bloque de operaciones atómicas (todo o nada) | Garantiza consistencia al crear pedidos |
| **Eager Loading** | Carga de relaciones con `with()` | Evita el problema N+1 de queries |
| **Hash bcrypt** | Encriptación unidireccional de contraseñas | Contraseñas seguras en la BD |
| **Blade** | Motor de templates de Laravel | HTML dinámico con datos de PHP |
| **$fillable** | Array de columnas asignables masivamente | Protección contra mass assignment |
| **Scope** | Filtro reutilizable en un modelo | `Product::activos()`, `Order::pendientes()` |
| **Fat Model** | Patrón donde la lógica vive en el modelo | Controllers finos, modelos con métodos de negocio |

### Preguntas frecuentes de examen

**¿Qué es Eloquent?**
Es el ORM (Object-Relational Mapper) de Laravel. Permite interactuar con la base de datos usando objetos PHP en lugar de escribir SQL directamente. Cada clase modelo corresponde a una tabla, y cada instancia corresponde a una fila.

**¿Qué son los SoftDeletes?**
Un patrón de borrado lógico donde en lugar de eliminar el registro de la BD, se le asigna una fecha en la columna `deleted_at`. Eloquent automáticamente excluye estos registros de todas las consultas. Se pueden restaurar limpiando esa columna.

**¿Para qué sirve el Middleware?**
Para interceptar las requests HTTP antes de que lleguen al controller. Se usa para verificar autenticación, autorización, o aplicar cualquier lógica transversal sin repetirla en cada controller.

**¿Qué es una transacción de base de datos?**
Un bloque de operaciones que se ejecutan como una unidad atómica: o todas tienen éxito (COMMIT) o todas se revierten (ROLLBACK). Garantiza la integridad de los datos cuando múltiples tablas deben actualizarse en conjunto.

**¿Por qué el carrito vive en la sesión y no en la BD?**
Porque es datos temporales específicos del usuario y la visita. No necesita persistencia permanente, y guardarlo en sesión es más rápido y simple. Si el usuario no finaliza la compra, el carrito desaparece sin dejar basura en la BD.

**¿Qué es el problema N+1 y cómo se resuelve?**
Ocurre cuando por cada fila de una consulta principal se hace una consulta adicional. Si tenés 50 pedidos y mostrás el nombre del usuario de cada uno, sin eager loading son 51 queries (1 + 50). Con `with('user')` son siempre 2 queries, sin importar cuántos pedidos haya.

**¿Qué es CSRF y por qué es importante?**
Cross-Site Request Forgery es un ataque donde un sitio malicioso hace que el navegador del usuario logueado envíe requests a tu app sin que el usuario lo sepa. El token CSRF previene esto porque el sitio malicioso no puede conocer el token único de la sesión.

---

*Documento generado automáticamente con Claude Code para el proyecto TiendaUNNE.*
*Fecha: Junio 2026*
