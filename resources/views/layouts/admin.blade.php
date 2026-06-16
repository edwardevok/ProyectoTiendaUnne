<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Tienda UNNE</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }

        /* ── SIDEBAR DESKTOP ── */
        .sidebar {
            height: 100vh;
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #ffffff;
            border-right: 1px solid #e0e0e0;
            padding-top: 20px;
            z-index: 1045;
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

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #0d6efd;
            color: white;
        }

        /* Contenido principal — desktop */
        .main-content {
            margin-left: 260px;
            padding: 40px;
        }

        /* ── TOPBAR MOBILE ── */
        .topbar-mobile {
            display: none;
            position: sticky;
            top: 0;
            z-index: 1040;
            background-color: #ffffff;
            border-bottom: 1px solid #e0e0e0;
            padding: 12px 16px;
        }

        /* ── OFFCANVAS SIDEBAR (mobile) ── */
        /* Reutilizamos los mismos estilos de la sidebar fija */
        .offcanvas-sidebar .offcanvas-body {
            padding: 0;
        }

        .offcanvas-sidebar .sidebar-brand {
            padding: 20px 20px 0;
            display: block;
        }

        .offcanvas-sidebar a {
            color: #4b5563;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            font-weight: 600;
            margin: 5px 15px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .offcanvas-sidebar a:hover,
        .offcanvas-sidebar a.active {
            background-color: #0d6efd;
            color: white;
        }

        /* ── RESPONSIVE BREAKPOINTS ── */
        @media (max-width: 991.98px) {
            .sidebar {
                display: none;  /* Ocultamos la sidebar fija en mobile */
            }

            .main-content {
                margin-left: 0;
                padding: 20px 16px;
            }

            .topbar-mobile {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
        }
    </style>
</head>

<body>

    {{-- ═══════════════════════════════════════════════
         TOPBAR MOBILE (solo visible en pantallas chicas)
         ═══════════════════════════════════════════════ --}}
    <nav class="topbar-mobile">
        <span class="fw-bold" style="color: #021a54; font-size: 1.1rem;">Tienda UNNE</span>
        <button class="btn btn-outline-secondary btn-sm" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z" />
            </svg>
        </button>
    </nav>

    {{-- ═══════════════════════════════════════════════
         SIDEBAR FIJA (solo visible en desktop ≥992px)
         ═══════════════════════════════════════════════ --}}
    <div class="sidebar d-none d-lg-flex flex-column justify-content-between">
        <div>
            <div class="sidebar-brand">
                Tienda UNNE <br>
                <small class="text-muted fw-normal" style="font-size: 0.8rem;">Panel de administración</small>
            </div>

            <a href="/admin/dashboard" class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="/admin/productos"  class="{{ Request::is('admin/productos*')  ? 'active' : '' }}">Productos</a>
            <a href="/admin/usuarios"   class="{{ Request::is('admin/usuarios*')   ? 'active' : '' }}">Usuarios</a>
            <a href="/admin/categorias" class="{{ Request::is('admin/categorias*') ? 'active' : '' }}">Categorías</a>
            <a href="/admin/consultas"  class="{{ Request::is('admin/consultas*')  ? 'active' : '' }}">Consultas</a>
            <a href="/admin/pedidos"    class="{{ Request::is('admin/pedidos*')    ? 'active' : '' }}">Pedidos</a>
        </div>

        <div class="p-3 mx-3 mb-3 bg-light rounded-3 border">
            <small class="text-muted d-block">Administrador</small>
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

    {{-- ═══════════════════════════════════════════════
         OFFCANVAS SIDEBAR (mobile — se abre con el ☰)
         ═══════════════════════════════════════════════ --}}
    <div class="offcanvas offcanvas-start offcanvas-sidebar" tabindex="-1"
         id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel"
         style="width: 280px;">

        <div class="offcanvas-header border-bottom">
            <span class="fw-bold" id="sidebarOffcanvasLabel" style="color: #021a54; font-size: 1.1rem;">
                Tienda UNNE<br>
                <small class="text-muted fw-normal" style="font-size: 0.75rem;">Panel de administración</small>
            </span>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
        </div>

        <div class="offcanvas-body d-flex flex-column justify-content-between p-0">
            <div class="pt-2">
                <a href="/admin/dashboard" class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="/admin/productos"  class="{{ Request::is('admin/productos*')  ? 'active' : '' }}">Productos</a>
                <a href="/admin/usuarios"   class="{{ Request::is('admin/usuarios*')   ? 'active' : '' }}">Usuarios</a>
                <a href="/admin/categorias" class="{{ Request::is('admin/categorias*') ? 'active' : '' }}">Categorías</a>
                <a href="/admin/consultas"  class="{{ Request::is('admin/consultas*')  ? 'active' : '' }}">Consultas</a>
                <a href="/admin/pedidos"    class="{{ Request::is('admin/pedidos*')    ? 'active' : '' }}">Pedidos</a>
            </div>

            <div class="p-3 mx-3 mb-3 bg-light rounded-3 border">
                <small class="text-muted d-block">Administrador</small>
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
    </div>

    {{-- Contenido principal --}}
    <div class="main-content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
