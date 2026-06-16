<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda UNNE</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('img/unne.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300..900&display=swap" rel="stylesheet">
</head>

<body>

    {{-- Barra de Navegación --}}
    @include('partials.navbar')

    {{-- Marquesina de Promociones --}}
    <div class="marquee-container">
        <div class="marquee-content">
            <span class="marquesina">
                🎓 Si sos estudiante de la UNNE tenés un 25% OFF | 🚚 Envíos a todo el país | 💳 10% OFF pagando con
                Mercado Pago | 🏫 Retirá en el Campus |
                🎓 Si sos estudiante de la UNNE tenés un 25% OFF | 🚚 Envíos a todo el país | 💳 10% OFF pagando con
                Mercado Pago | 🏫 Retirá en el Campus |
            </span>
            <span class="marquesina">
                🎓 Si sos estudiante de la UNNE tenés un 25% OFF | 🚚 Envíos a todo el país | 💳 10% OFF pagando con
                Mercado Pago | 🏫 Retirá en el Campus |
                🎓 Si sos estudiante de la UNNE tenés un 25% OFF | 🚚 Envíos a todo el país | 💳 10% OFF pagando con
                Mercado Pago | 🏫 Retirá en el Campus |
            </span>
        </div>
    </div>

    {{-- Banners Principales (Responsive) --}}
    <section class="banner-container">
        <div class="d-none d-md-block">{{-- oculta y muestra en pantalla medianas - grandes --}}
            <img src="{{ asset('img/banner.png') }}" class="img-fluid w-100" alt="Tienda UNNE Desktop">
        </div>
        <div class="d-block d-md-none">{{-- se muestra como bloque y se oculta para pantallas medianas en adelante --}}
            <img src="{{ asset('img/banner_movil.png') }}" class="img-fluid w-100" alt="Tienda UNNE Móvil">
        </div>
    </section>

    {{-- Sección de Presentación --}}
    <section class="seccion-presentacion py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 text-center d-none d-lg-block">
                    <img src="{{ asset('img/img1.png') }}" alt="Comunidad UNNE" class="img-fluid img-presentacion">
                    {{-- Quitamos 'rounded' y 'shadow-sm' --}}
                </div>
                <div class="col-12 col-lg-7">
                    <h1 class="display-5 fw-bold mb-4">
                        Tienda UNNE es <span id="typewriter" class="d-block d-md-inline"></span>
                    </h1>
                    <div class="presentation-text">
                        <p>
                            La Tienda UNNE nace para fortalecer el sentido de pertenencia de toda la comunidad
                            universitaria, ofreciendo productos que reflejan nuestra identidad. Como universidad a la
                            vanguardia en tecnología y desarrollo sustentable, cada artículo representa innovación,
                            compromiso y cuidado del ambiente. Nuestra tienda virtual reúne indumentaria y accesorios
                            pensados para llevar el orgullo UNNE en el día a día.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Sección de Categorías --}}
    <section class="container-fluid px-0 m-0"> {{-- m-0 elimina márgenes externos --}}
        <div class="row g-0 m-0 p-0"> {{-- g-0 y m-0 aseguran que la fila no tenga desbordes ni espacios --}}

            <div class="col-6 col-lg-3">
                <a href="/productos?categoria=indumentaria" class="categoria-cuadrado"
                    style="background-image: url('{{ asset('img/indumentaria.png') }}');">
                    <div class="categoria-overlay">
                        <span class="material-symbols-rounded mb-2">apparel</span>
                        <h3 class="categoria-titulo">Indumentaria</h3>
                    </div>
                </a>
            </div>

            <div class="col-6 col-lg-3">
                <a href="/productos?categoria=accesorios" class="categoria-cuadrado"
                    style="background-image: url('{{ asset('img/accesorios.png') }}');">
                    <div class="categoria-overlay">
                        <span class="material-symbols-rounded mb-2">personal_bag</span>
                        <h3 class="categoria-titulo">Accesorios</h3>
                    </div>
                </a>
            </div>

            <div class="col-6 col-lg-3">
                <a href="/productos?categoria=libreria" class="categoria-cuadrado"
                    style="background-image: url('{{ asset('img/libreria.png') }}');">
                    <div class="categoria-overlay">
                        <span class="material-symbols-rounded mb-2">ink_pen</span>
                        <h3 class="categoria-titulo">Útiles</h3>
                    </div>
                </a>
            </div>

            <div class="col-6 col-lg-3">
                <a href="/productos?categoria=bazar" class="categoria-cuadrado"
                    style="background-image: url('{{ asset('img/hogar.png') }}');">
                    <div class="categoria-overlay">
                        <span class="material-symbols-rounded mb-2">water_bottle</span>
                        <h3 class="categoria-titulo">Bazar</h3>
                    </div>
                </a>
            </div>

        </div>
    </section>

    {{-- Sección de Productos Destacados --}}
    <section class="py-5 bg-destacados overflow-hidden">
        <div class="container position-relative">
            <h2 class="display-5 fw-bold text-center mb-5">Destacados</h2>

            <div class="embla" id="emblaDestacados">
                <div class="embla__container">

                    @forelse ($destacados as $producto)
                        <div class="embla__slide">
                            <div class="card border-0 product-card bg-transparent text-center h-100 d-flex flex-column">
                                <div class="contenedor-foto-producto shadow-sm mb-3 bg-white rounded p-2">
                                    @if ($producto->image)
                                        <img src="{{ asset('img/' . $producto->image) }}" class="img-fluid"
                                            alt="{{ $producto->name }}">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                            Sin imagen
                                        </div>
                                    @endif
                                </div>
                                <h6 class="text-muted fw-bold">{{ $producto->name }}</h6>
                                <p class="precio-producto text-success fw-bold mb-3">
                                    $ {{ number_format($producto->price, 0, ',', '.') }}
                                </p>

                                <div class="mt-auto">
                                    @auth
                                        <form action="/carrito/agregar/{{ $producto->id }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit"
                                                class="btn btn-naranja w-100 d-flex align-items-center justify-content-center gap-2"
                                                style="padding: 10px;">
                                                <span class="material-symbols-rounded"
                                                    style="font-size: 1.2rem;">shopping_cart</span>
                                                Agregar
                                            </button>
                                        </form>
                                    @else
                                        <a href="/login"
                                            class="btn btn-naranja w-100 d-flex align-items-center justify-content-center gap-2"
                                            style="padding: 10px;">
                                            <span class="material-symbols-rounded"
                                                style="font-size: 1.2rem;">shopping_cart</span>
                                            Agregar
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="embla__slide">
                            <p class="text-muted text-center py-4">Próximamente habrá productos disponibles.</p>
                        </div>
                    @endforelse

                </div>
            </div>

            {{-- Botones de Navegación --}}
            <button class="btn-embla-nav btn-embla-prev" id="btnPrev">
                <span class="material-symbols-rounded">chevron_left</span>
            </button>
            <button class="btn-embla-nav btn-embla-next" id="btnNext">
                <span class="material-symbols-rounded">chevron_right</span>
            </button>

        </div>
    </section>

    @include('partials.footer')

    <script src="https://unpkg.com/typewriter-effect@latest/dist/core.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://unpkg.com/embla-carousel/embla-carousel.umd.js"></script>
    <script src="https://unpkg.com/embla-carousel-auto-scroll/embla-carousel-auto-scroll.umd.js"></script>

    <script>
        // Configuración del Typewriter
        const app = document.getElementById('typewriter');
        const typewriter = new Typewriter(app, {
            loop: true,
            delay: 75
        });
        typewriter
            .pauseFor(500).typeString('<span style="color: #FF6600;">identidad</span>')
            .pauseFor(2000).deleteAll()
            .typeString('<span style="color: #FF6600;">comunidad</span>')
            .pauseFor(2000).deleteAll()
            .typeString('<span style="color: #FF6600;">futuro</span>')
            .pauseFor(2000).start();

        // 1. Inicialización de Embla y el plugin
        const emblaNode = document.getElementById('emblaDestacados');
        const options = {
            loop: true
        };

        // Configuramos la velocidad del AutoScroll
        const autoScrollOptions = {
            speed: 2,
            playOnInit: true,
            stopOnInteraction: false // Lo ponemos en false porque manejaremos la interacción nosotros
        };

        const embla = EmblaCarousel(
            emblaNode,
            options,
            [EmblaCarouselAutoScroll(autoScrollOptions)]
        );

        // 2. Extraemos el plugin de AutoScroll para poder controlarlo
        const autoScroll = embla.plugins().autoScroll;

        // 3. Lógica de los botones con temporizador
        const btnPrev = document.getElementById('btnPrev');
        const btnNext = document.getElementById('btnNext');
        let resumeTimeout; // Variable para guardar nuestro reloj

        const handleManualScroll = (direction) => {
            // A. Frenamos la cinta transportadora
            autoScroll.stop();

            // B. Hacemos el salto manual a la tarjeta anterior o siguiente
            if (direction === 'prev') embla.scrollPrev();
            if (direction === 'next') embla.scrollNext();

            // C. Si el usuario hace varios clics rápidos, reiniciamos el reloj para que no arranque antes de tiempo
            clearTimeout(resumeTimeout);

            // D. Programamos el arranque automático (play) en 3000 milisegundos (3 segundos)
            resumeTimeout = setTimeout(() => {
                autoScroll.play();
            }, 3000);
        };

        // Conectamos los botones a nuestra función
        btnPrev.addEventListener('click', () => handleManualScroll('prev'));
        btnNext.addEventListener('click', () => handleManualScroll('next'));
    </script>
</body>

</html>
