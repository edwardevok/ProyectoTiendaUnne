<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/productos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="icon" href="{{ asset('img/unne.png') }}" type="image/png">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet">
    <title>TiendaUNNE | Productos</title>
</head>

<body>
    {{-- NavBar --}}
    @include('partials.navbar')

    {{-- Banner --}}
    <section class="hero-quienes-somos position-relative d-flex align-items-center justify-content-center text-center">

        {{-- Capa oscura para que el texto resalte (Overlay) --}}
        <div class="overlay-hero"></div>

        {{-- Contenido del Banner --}}
        <div class="container position-relative" style="z-index: 2;">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8">
                    {{-- Un pequeño subtítulo naranja para darle elegancia --}}
                    <span class="text-uppercase fw-bold mb-2 d-block" style="color: #FF6600; letter-spacing: 2px;">
                        Tienda Oficial
                    </span>
                    <h1 class="display-3 fw-bold text-white mb-3">Nuestros Productos</h1>
                    <p class="lead text-white-50 mb-0">
                        Llevando el orgullo de la Universidad Nacional del Nordeste a tu día a día.
                    </p>
                </div>
            </div>
        </div>

    </section>

    {{-- Cuerpo --}}
    <section class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h2 class="fw-bold" style="color: #021A54;">Catálogo UNNE</h2>
            <div class="dropdown">
                <button class="btn btn-outline-dark dropdown-toggle fw-bold" type="button" id="filterDropdown"
                    data-bs-toggle="dropdown">
                    Todas las categorías
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    {{-- Botón fijo para ver todas --}}
                    <li><a class="dropdown-item active" href="#" onclick="filterCategory('all', this)">Todas</a>
                    </li>

                    {{-- MAGIA DE LARAVEL: Dibujamos las categorías dinámicamente --}}
                    @foreach ($categorias as $categoria)
                        @php
                            // Convertimos el nombre (ej: "Librería y Estudio") a formato filtro ("libreria-y-estudio")
                            $catSlug = Str::slug($categoria->name);
                        @endphp
                        <li>
                            <a class="dropdown-item" href="#"
                                onclick="filterCategory('{{ $catSlug }}', this)">
                                {{ $categoria->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        {{-- SISTEMA DE AVISOS (Éxito o Error de Stock) --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <strong>¡Genial!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <strong>¡Atención!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row g-4" id="products-grid">

            {{-- MAGIA DE LARAVEL: El bucle que genera las tarjetas dinámicamente --}}
            @forelse ($productos as $producto)
                @php
                    $categoriaFiltro = $producto->category ? Str::slug($producto->category->name) : 'otros';
                @endphp

                <div class="col-12 col-md-4 col-lg-3 product-card" data-category="{{ $categoriaFiltro }}">
                    <div class="card h-100 border-0 shadow-sm d-flex flex-column">

                        {{-- Imagen clickeable → abre el modal --}}
                        @if ($producto->image)
                            <img src="{{ asset('img/' . $producto->image) }}" class="card-img-top p-2 square-img"
                                alt="{{ $producto->name }}"
                                style="object-fit: cover; aspect-ratio: 1/1; cursor: pointer;"
                                data-bs-toggle="modal" data-bs-target="#modalProducto{{ $producto->id }}">
                        @else
                            <div class="card-img-top p-2 square-img bg-light d-flex align-items-center justify-content-center text-muted"
                                style="aspect-ratio: 1/1; cursor: pointer;"
                                data-bs-toggle="modal" data-bs-target="#modalProducto{{ $producto->id }}">
                                Sin imagen
                            </div>
                        @endif

                        <div class="card-body text-center d-flex flex-column">
                            <h6 class="fw-bold">{{ $producto->name }}</h6>
                            <p class="text-primary mb-1 fw-bold">$ {{ number_format($producto->price, 0, ',', '.') }}</p>

                            {{-- Link para ver descripción --}}
                            <a href="#" class="text-muted small mb-3 text-decoration-none"
                               data-bs-toggle="modal" data-bs-target="#modalProducto{{ $producto->id }}">
                               Ver descripción
                            </a>

                            {{-- Lógica de permisos y stock para el botón --}}
                            @auth
                                <form action="/carrito/agregar/{{ $producto->id }}" method="POST" class="mt-auto w-100">
                                    @csrf
                                    @if ($producto->stock > 0)
                                        <div class="input-group input-group-sm mb-2">
                                            <span class="input-group-text bg-light text-muted fw-bold">Cant.</span>
                                            <input type="number" name="quantity" class="form-control text-center"
                                                value="1" min="1" max="{{ $producto->stock }}">
                                        </div>
                                        <button type="submit" class="btn btn-orange btn-sm w-100">Agregar al carrito</button>
                                    @else
                                        <button type="button" class="btn btn-secondary btn-sm w-100 mt-auto" disabled>Agotado</button>
                                    @endif
                                </form>
                            @else
                                <a href="/login" class="btn btn-secondary btn-sm w-100 mt-auto fw-bold">Ingresar para comprar</a>
                            @endauth
                        </div>
                    </div>
                </div>

                {{-- ── MODAL DE DETALLE DEL PRODUCTO ── --}}
                <div class="modal fade" id="modalProducto{{ $producto->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content border-0 shadow rounded-4">

                            <div class="modal-header border-0 pb-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>

                            <div class="modal-body p-4 pt-0">
                                <div class="row g-4 align-items-center">

                                    {{-- Imagen --}}
                                    <div class="col-md-5 text-center">
                                        @if ($producto->image)
                                            <img src="{{ asset('img/' . $producto->image) }}"
                                                alt="{{ $producto->name }}"
                                                class="img-fluid rounded-3 shadow-sm"
                                                style="max-height: 300px; object-fit: cover; width: 100%;">
                                        @else
                                            <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted"
                                                style="height: 220px;">Sin imagen</div>
                                        @endif
                                    </div>

                                    {{-- Info --}}
                                    <div class="col-md-7">
                                        @if ($producto->category)
                                            <span class="badge bg-primary bg-opacity-10 text-primary mb-2">
                                                {{ $producto->category->name }}
                                            </span>
                                        @endif

                                        <h4 class="fw-bold text-dark mb-1">{{ $producto->name }}</h4>
                                        <p class="fw-bold fs-4 mb-3" style="color: #0d6efd;">
                                            $ {{ number_format($producto->price, 0, ',', '.') }}
                                        </p>

                                        <p class="text-muted mb-4" style="line-height: 1.7;">
                                            {{ $producto->description ?? 'Este producto no tiene descripción disponible.' }}
                                        </p>

                                        {{-- Stock --}}
                                        @if ($producto->stock > 0)
                                            <small class="text-success fw-bold">
                                                ✓ Stock disponible ({{ $producto->stock }} unidades)
                                            </small>
                                        @else
                                            <small class="text-danger fw-bold">✗ Sin stock</small>
                                        @endif

                                        {{-- Botón dentro del modal --}}
                                        <div class="mt-3">
                                            @auth
                                                @if ($producto->stock > 0)
                                                    <form action="/carrito/agregar/{{ $producto->id }}" method="POST">
                                                        @csrf
                                                        <div class="input-group mb-2">
                                                            <span class="input-group-text bg-light fw-bold">Cantidad</span>
                                                            <input type="number" name="quantity" class="form-control"
                                                                value="1" min="1" max="{{ $producto->stock }}">
                                                        </div>
                                                        <button type="submit" class="btn btn-orange w-100 fw-bold">
                                                            Agregar al carrito
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-secondary w-100 fw-bold" disabled>Agotado</button>
                                                @endif
                                            @else
                                                <a href="/login" class="btn btn-secondary w-100 fw-bold">Ingresar para comprar</a>
                                            @endauth
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                {{-- ── FIN MODAL ── --}}

            @empty
                {{-- Si la base de datos está vacía, mostramos este mensaje en vez de una pantalla en blanco --}}
                <div class="col-12 text-center py-5">
                    <h4 class="text-muted fw-bold">Próximamente habrá productos disponibles.</h4>
                    <p class="text-muted">Estamos preparando el stock para vos.</p>
                </div>
            @endforelse

        </div>
    </section>


    {{-- Modal de pedido confirmado --}}
    @if (session('pedido_exitoso'))
        <div class="modal fade" id="pedidoExitosoModal" tabindex="-1" aria-hidden="true"
            data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-body text-center px-5 pt-5 pb-4">

                        {{-- Ícono de check --}}
                        <div class="mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="#021A54"
                                class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                <path
                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                            </svg>
                        </div>

                        <h4 class="fw-bold mb-2" style="color: #021A54;">
                            ¡Pedido confirmado!
                        </h4>
                        <p class="text-muted mb-0">
                            Tu pedido está <strong>en preparación</strong>.<br>
                            Podés seguir el estado desde tu perfil.
                        </p>

                    </div>
                    <div class="modal-footer border-0 justify-content-center pb-5 gap-2">
                        <a href="/perfil" class="btn btn-primary rounded-3 px-4">
                            Ver mis pedidos
                        </a>
                        <button type="button" class="btn btn-outline-secondary rounded-3 px-4"
                            data-bs-dismiss="modal">
                            Seguir comprando
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal de notificación del carrito --}}
    <div class="modal fade" id="cartModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-body p-4 pt-5 text-center">
                    <div id="cartModalIcon" class="mb-3" style="font-size: 3rem;"></div>
                    <p id="cartModalMessage" class="mb-0 fw-bold fs-5"></p>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4 gap-2">
                    <button type="button" class="btn btn-secondary rounded-3"
                        data-bs-dismiss="modal">Seguir comprando</button>
                    <a href="/carrito" class="btn btn-primary rounded-3">Ver carrito</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    @include('partials.footer')


    <script>
        // 1. Tu función original de filtrado (sin cambios)
        function filterCategory(category, element) {
            // UI: Cambiar item activo
            document.querySelectorAll('.dropdown-item').forEach(item => item.classList.remove('active'));
            element.classList.add('active');
            document.getElementById('filterDropdown').innerText = element.innerText;

            // Lógica de filtrado
            const cards = document.querySelectorAll('.product-card');
            cards.forEach(card => {
                if (category === 'all' || card.getAttribute('data-category') === category) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // 2. NUEVO: Código que se ejecuta apenas carga la página
        document.addEventListener('DOMContentLoaded', function() {
            const parametrosURL = new URLSearchParams(window.location.search);
            const categoriaSolicitada = parametrosURL.get('categoria');

            if (!categoriaSolicitada) return;

            // Busca el dropdown-item cuyo slug en onclick coincide exactamente con el parámetro de la URL.
            // Usamos match() sobre el atributo onclick en lugar de [onclick*="..."] para evitar falsos
            // positivos con slugs compuestos como 'libreria-y-estudio' vs 'libreria'.
            let elementoMenu = null;
            document.querySelectorAll('.dropdown-item[onclick]').forEach(function(item) {
                const m = item.getAttribute('onclick').match(/filterCategory\('([^']+)'/);
                if (m && m[1] === categoriaSolicitada) {
                    elementoMenu = item;
                }
            });

            if (elementoMenu) {
                filterCategory(categoriaSolicitada, elementoMenu);
            }
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @if (session('pedido_exitoso'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new bootstrap.Modal(document.getElementById('pedidoExitosoModal')).show();
        });
    </script>
    @endif

    <script>
        document.querySelectorAll('form[action^="/carrito/agregar/"]').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                fetch(this.action, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: new FormData(this),
                })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    document.getElementById('cartModalIcon').textContent =
                        data.type === 'success' ? '✅' : '❌';
                    document.getElementById('cartModalMessage').textContent = data.message;

                    if (data.type === 'success') {
                        var badge = document.getElementById('cart-badge');
                        if (badge) {
                            badge.firstChild.textContent = data.totalUnidades;
                            badge.classList.remove('d-none');
                        }
                    }

                    new bootstrap.Modal(document.getElementById('cartModal')).show();
                });
            });
        });
    </script>
    {{-- SCRIPT PARA MANTENER LA POSICIÓN DEL SCROLL (SIN ANIMACIÓN) --}}
    <script>
        window.addEventListener("beforeunload", function() {
            sessionStorage.setItem('scrollPosition', window.scrollY);
        });

        document.addEventListener("DOMContentLoaded", function() {
            let savedScrollPosition = sessionStorage.getItem('scrollPosition');

            if (savedScrollPosition) {
                // Forzamos el comportamiento "instant" para evitar el efecto de viaje visual
                window.scrollTo({
                    top: parseInt(savedScrollPosition),
                    left: 0,
                    behavior: "instant"
                });
                sessionStorage.removeItem('scrollPosition');
            }
        });
    </script>
</body>

</html>
