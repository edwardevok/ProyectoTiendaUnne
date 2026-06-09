<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

// ==========================================
// 1. RUTAS PÚBLICAS (Accesibles para todos)
// ==========================================
Route::get('/', function () {
    return redirect('/index');
});
Route::get('/index', function () { return view('index'); });
Route::get('/quienes-somos', function () { return view('quienes_somos'); });
Route::get('/comercializacion', function () { return view('comercializacion'); });
Route::get('/terminos', function () { return view('terminos'); });
Route::get('/productos', [ProductController::class, 'catalogo']);
Route::get('/paginaenconstruccion', function () { return view('paginaenconstruccion'); });
Route::get('/contacto', function () { return view('contacto'); }); 


// ==========================================
// 2. RUTAS DE AUTENTICACIÓN (Login/Registro)
// ==========================================
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::get('/registro', function () { return view('auth.register'); });

Route::post('/registro', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ==========================================
// 3. RUTAS PROTEGIDAS PARA CLIENTES (Logueados)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // Contacto (Solo logueados)
    Route::post('/contacto', [MessageController::class, 'storeFrontEnd']);
    
    // Rutas del Carrito de Compras
    Route::get('/carrito', [CartController::class, 'index']);
    Route::post('/carrito/agregar/{id}', [CartController::class, 'add']);
    Route::delete('/carrito/quitar/{id}', [CartController::class, 'remove']);
    Route::post('/carrito/vaciar', [CartController::class, 'clear']);
    
    // Rutas para procesar los Pedidos (Checkout)
    Route::get('/checkout', [OrderController::class, 'checkout']);
    Route::post('/checkout/procesar', [OrderController::class, 'store']);

    // Rutas para el Perfil del Cliente y Seguimiento
    Route::get('/perfil', [ProfileController::class, 'index']);
    Route::put('/perfil/actualizar', [ProfileController::class, 'update']);

});


// ==========================================
// 4. RUTAS DE ADMINISTRACIÓN (PROTEGIDAS)
// ==========================================
Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])->group(function () {
    
    // Dashboard Principal
    Route::get('/admin/dashboard', [DashboardController::class, 'index']);

    // Pedidos
    Route::get('/admin/pedidos', [OrderController::class, 'adminIndex']);
    Route::put('/admin/pedidos/{id}/estado', [OrderController::class, 'updateStatus']);

    // Productos
    Route::get('/admin/productos', [ProductController::class, 'index']);
    Route::get('/admin/productos/crear', [ProductController::class, 'create']);
    Route::post('/admin/productos', [ProductController::class, 'store']);
    Route::delete('/admin/productos/{producto}', [ProductController::class, 'destroy']); 
    Route::put('/admin/productos/{id}/restaurar', [ProductController::class, 'restore']); // <-- NUEVA: Restaurar Producto
    Route::get('/admin/productos/{producto}/editar', [ProductController::class, 'edit']);
    Route::put('/admin/productos/{producto}', [ProductController::class, 'update']);

    // Usuarios
    Route::get('/admin/usuarios', [UserController::class, 'index']);
    Route::post('/admin/usuarios', [UserController::class, 'store']);
    Route::put('/admin/usuarios/{id}', [UserController::class, 'update']);
    Route::delete('/admin/usuarios/{id}', [UserController::class, 'destroy']);
    Route::put('/admin/usuarios/{id}/restaurar', [UserController::class, 'restore']); // <-- NUEVA: Restaurar Usuario

    // Categorias
    Route::get('/admin/categorias', [CategoryController::class, 'index']);
    Route::post('/admin/categorias', [CategoryController::class, 'store']);
    Route::put('/admin/categorias/{id}', [CategoryController::class, 'update']);
    Route::delete('/admin/categorias/{id}', [CategoryController::class, 'destroy']);

    // Consultas
    Route::get('/admin/consultas', [MessageController::class, 'index']);
    Route::put('/admin/consultas/{id}/estado', [MessageController::class, 'updateStatus']);
    Route::post('/admin/consultas/{id}/responder', [MessageController::class, 'sendReply']);

});