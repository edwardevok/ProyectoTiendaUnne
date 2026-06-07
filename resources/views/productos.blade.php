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
                {{-- Adaptamos el nombre de la categoría para que el JS del filtro la entienda (ej: "Indumentaria" -> "indumentaria") --}}
                @php
                    $categoriaFiltro = $producto->category ? Str::slug($producto->category->name) : 'otros';
                @endphp

                <div class="col-12 col-md-4 col-lg-3 product-card" data-category="{{ $categoriaFiltro }}">
                    <div class="card h-100 border-0 shadow-sm d-flex flex-column">

                        {{-- Verificamos si tiene imagen en la base de datos --}}
                        @if ($producto->image)
                            <img src="{{ asset('img/' . $producto->image) }}" class="card-img-top p-2 square-img"
                                alt="{{ $producto->name }}" style="object-fit: cover; aspect-ratio: 1/1;">
                        @else
                            {{-- Si no le cargaste imagen, mostramos un recuadro gris sutil --}}
                            <div class="card-img-top p-2 square-img bg-light d-flex align-items-center justify-content-center text-muted"
                                style="aspect-ratio: 1/1;">
                                Sin imagen
                            </div>
                        @endif

                        <div class="card-body text-center d-flex flex-column">
                            <h6 class="fw-bold">{{ $producto->name }}</h6>
                            <p class="text-primary mb-3 fw-bold">$ {{ number_format($producto->price, 0, ',', '.') }}
                            </p>

                            {{-- Lógica de permisos y stock para el botón --}}
                            @auth
                                <form action="/carrito/agregar/{{ $producto->id }}" method="POST" class="mt-auto w-100">
                                    @csrf

                                    @if ($producto->stock > 0)
                                        {{-- Selector de cantidad (no le dejamos pedir más del stock total) --}}
                                        <div class="input-group input-group-sm mb-2">
                                            <span class="input-group-text bg-light text-muted fw-bold">Cant.</span>
                                            <input type="number" name="quantity" class="form-control text-center"
                                                value="1" min="1" max="{{ $producto->stock }}">
                                        </div>
                                        <button type="submit" class="btn btn-orange btn-sm w-100">Agregar al
                                            carrito</button>
                                    @else
                                        {{-- Si el stock es 0, bloqueamos el botón --}}
                                        <button type="button" class="btn btn-secondary btn-sm w-100 mt-auto"
                                            disabled>Agotado</button>
                                    @endif

                                </form>
                            @else
                                <a href="/login" class="btn btn-secondary btn-sm w-100 mt-auto fw-bold">Ingresar para
                                    comprar</a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                {{-- Si la base de datos está vacía, mostramos este mensaje en vez de una pantalla en blanco --}}
                <div class="col-12 text-center py-5">
                    <h4 class="text-muted fw-bold">Próximamente habrá productos disponibles.</h4>
                    <p class="text-muted">Estamos preparando el stock para vos.</p>
                </div>
            @endforelse

        </div>
    </section>


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
            // Leer la URL para ver si trae una categoría (ej: ?categoria=indumentaria)
            const parametrosURL = new URLSearchParams(window.location.search);
            const categoriaSolicitada = parametrosURL.get('categoria');

            if (categoriaSolicitada) {
                // Buscar el botón del menú desplegable que corresponde a esa categoría
                // Buscamos el elemento <a> que en su onclick contenga el nombre de la categoría
                const elementoMenu = document.querySelector(`.dropdown-item[onclick*="'${categoriaSolicitada}'"]`);

                if (elementoMenu) {
                    // Si lo encuentra, simula que el usuario hizo clic en él
                    filterCategory(categoriaSolicitada, elementoMenu);
                }
            }
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
