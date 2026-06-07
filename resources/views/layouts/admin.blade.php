<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Tienda UNNE</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Fondo general gris claro como en tu captura */
        body {
            background-color: #f4f6f9;
        }

        /* Estilos de la barra lateral */
        .sidebar {
            height: 100vh;
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #ffffff;
            border-right: 1px solid #e0e0e0;
            padding-top: 20px;
        }

        .sidebar-brand {
            font-size: 1.25rem;
            font-weight: 800;
            color: #021a54;
            padding: 0 20px;
            margin-bottom: 30px;
        }

        .sidebar a {
            color: #4b5563;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            font-weight: 600;
            margin: 5px 15px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        /* Efecto al pasar el mouse o estar activo (Azul brillante) */
        .sidebar a:hover,
        .sidebar a.active {
            background-color: #0d6efd;
            color: white;
        }

        /* Contenido principal a la derecha */
        .main-content {
            margin-left: 260px;
            padding: 40px;
        }
    </style>
</head>

<body>

    {{-- Barra Lateral Izquierda --}}
    <div class="sidebar d-flex flex-column justify-content-between">
        <div>
            <div class="sidebar-brand">
                Tienda UNNE <br>
                <small class="text-muted fw-normal" style="font-size: 0.8rem;">Panel de administración</small>
            </div>

            {{-- Enlaces de navegación dinámicos --}}
            <a href="/admin/dashboard" class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="/admin/productos" class="{{ Request::is('admin/productos*') ? 'active' : '' }}">Productos</a>
            <a href="/admin/usuarios" class="{{ Request::is('admin/usuarios*') ? 'active' : '' }}">Usuarios</a>
            <a href="/admin/categorias" class="{{ Request::is('admin/categorias*') ? 'active' : '' }}">Categorías</a>
            <a href="/admin/consultas" class="{{ Request::is('admin/consultas*') ? 'active' : '' }}">Consultas</a>
            <a href="/admin/pedidos" class="{{ Request::is('admin/pedidos*') ? 'active' : '' }}">Pedidos</a>
        </div>

        {{-- Tarjeta de usuario en la parte inferior --}}
        <div class="p-3 mx-3 mb-3 bg-light rounded-3 border">
            <small class="text-muted d-block">Administrador</small>

            {{-- Mostramos el nombre del administrador logueado con text-truncate --}}
            <div class="fw-bold text-dark text-truncate"
                title="{{ Auth::user()->name ?? 'Admin' }} {{ Auth::user()->last_name ?? '' }}">
                {{ Auth::user()->name ?? 'Admin' }} {{ Auth::user()->last_name ?? '' }}
            </div>

            <form action="/logout" method="POST" class="mt-2 mb-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger w-100 fw-bold"
                    style="border-radius: 6px;">Salir</button>
            </form>
        </div>
    </div>

    {{-- Contenedor donde se inyectará cada pantalla específica --}}
    <div class="main-content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
