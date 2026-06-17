<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TiendaUNNE | Quiénes Somos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/quienes_somos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="icon" href="{{ asset('img/unne.png') }}" type="image/png">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet">
</head>

<body>

    @include('partials.navbar')

    {{-- Banner Principal --}}
    <section class="hero-quienes-somos position-relative d-flex align-items-center justify-content-center text-center">
        <div class="overlay-hero"></div>
        <div class="container position-relative" style="z-index: 2;">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8">
                    <span class="text-uppercase fw-bold mb-2 d-block" style="color: #FF6600; letter-spacing: 2px;">
                        Nuestra Historia
                    </span>
                    <h1 class="display-3 fw-bold text-white mb-3">Quiénes Somos</h1>
                    <p class="lead text-white-50 mb-0">
                        Llevando el orgullo de la Universidad Nacional del Nordeste a tu día a día.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Sección Misión, Visión y Valores --}}
    <section class="py-5 bg-light">
        <div class="container my-5">

            <div class="text-center mb-5 pb-3">
                <span class="text-uppercase fw-bold mb-2 d-block" style="color: #FF6600; letter-spacing: 2px;">
                    Nuestra Esencia
                </span>
                <h2 class="display-6 fw-bold texto-azul-unne">Misión, Visión y Valores</h2>
            </div>

            {{-- MISIÓN --}}
            <div class="row align-items-center mb-5 pb-4">
                <div class="col-12 col-md-6 mb-4 mb-md-0">
                    <div class="contenedor-imagen-limpia shadow">
                        <img src="{{ asset('img/mision.png') }}" alt="Misión Tienda UNNE" class="img-fluida-quienes">
                    </div>
                </div>
                <div class="col-12 col-md-6 px-lg-5">
                    <div class="d-flex align-items-center mb-3">
                        <span class="material-symbols-rounded me-2 texto-naranja">my_location</span>
                        <h4 class="fw-bold mb-0 texto-azul-unne">Nuestro Propósito</h4>
                    </div>
                    <p class="text-muted" style="line-height: 1.8; font-size: 1.1rem;">
                        Brindar a la comunidad universitaria productos de alta calidad que representen el
                        orgullo y la identidad de la UNNE, facilitando el acceso a través de una plataforma
                        moderna y eficiente.
                    </p>
                </div>
            </div>

            {{-- VISIÓN (Invertido en PC) --}}
            <div class="row align-items-center mb-5 pb-4">
                <div class="col-12 col-md-6 order-2 order-md-1 px-lg-5 text-md-end">
                    <div class="d-flex align-items-center justify-content-md-end mb-3">
                        <h4 class="fw-bold mb-0 texto-azul-unne">Hacia dónde vamos</h4>
                        <span class="material-symbols-rounded ms-2 texto-naranja">visibility</span>
                    </div>
                    <p class="text-muted" style="line-height: 1.8; font-size: 1.1rem;">
                        Ser la tienda universitaria referente en toda la región, destacando por nuestra
                        innovación, compromiso con la sustentabilidad y el fomento del sentido de pertenencia.
                    </p>
                </div>
                <div class="col-12 col-md-6 order-1 order-md-2 mb-4 mb-md-0">
                    <div class="contenedor-imagen-limpia shadow">
                        <img src="{{ asset('img/vision.png') }}" alt="Visión Tienda UNNE" class="img-fluida-quienes">
                    </div>
                </div>
            </div>

            {{-- VALORES --}}
            <div class="row align-items-center">
                <div class="col-12 col-md-6 mb-4 mb-md-0">
                    <div class="contenedor-imagen-limpia shadow">
                        <img src="{{ asset('img/valores.png') }}" alt="Valores Tienda UNNE" class="img-fluida-quienes">
                    </div>
                </div>
                <div class="col-12 col-md-6 px-lg-5">
                    <div class="d-flex align-items-center mb-3">
                        <span class="material-symbols-rounded me-2 texto-naranja">favorite</span>
                        <h4 class="fw-bold mb-0 texto-azul-unne">Nuestros Pilares</h4>
                    </div>
                    <p class="text-muted" style="line-height: 1.8; font-size: 1.1rem;">
                        Identidad institucional, Transparencia en la gestión, Calidad garantizada, Innovación
                        constante y Compromiso total con nuestra comunidad estudiantil y docente.
                    </p>
                </div>
            </div>

        </div>
    </section>

    {{-- Transición Curva SVG --}}
    <svg class="svg-ola" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"
        style="display: block; width: 100%; height: auto; background-color: #f8f9fa;">
        <path fill="#263B6A" fill-opacity="1"
            d="M0,96L60,128C120,160,240,224,360,213.3C480,203,600,117,720,112C840,107,960,181,1080,208C1200,235,1320,213,1380,202.7L1440,192L1440,320L1380,320C1320,320,1200,320,1080,320C960,320,840,320,720,320C600,320,480,320,360,320C240,320,120,320,60,320L0,320Z">
        </path>
    </svg>

    {{-- Sección Nuestro Equipo --}}
    <section class="seccion-equipo-azul">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-white">El equipo detrás de la tienda</h2>
                <div class="mx-auto mt-2" style="width: 80px; height: 4px; background-color: #FF6600;"></div>
            </div>

            <div class="row g-4 justify-content-center">
                <div class="col-12 col-md-5">
                    <div class="card card-equipo-nueva shadow">
                        <div class="card-body">
                            <img src="{{ asset('img/edu.jpg') }}" alt="Eduardo López" class="foto-chica-equipo">
                            <h3 class="h4 nombre-equipo">Eduardo López</h3>
                            <p class="desc-equipo">
                                Técnico en Desarrollo de Software y estudiante de la Licenciatura en Sistemas. Enfocado
                                en la arquitectura y creación de soluciones tecnológicas, cuenta con sólidos
                                conocimientos en desarrollo web, modelado de bases de datos y la integración de
                                herramientas de Inteligencia Artificial. Su base técnica se apoya en el dominio de
                                múltiples paradigmas, destacando la Programación Orientada a Objetos (POO) y la
                                programación funcional para diseñar sistemas eficientes y escalables. En su tiempo
                                libre, disfruta del gaming y la práctica de boxeo.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-5">
                    <div class="card card-equipo-nueva shadow">
                        <div class="card-body">
                            <img src="{{ asset('img/joan.jpg') }}" alt="Joan Mauri" class="foto-chica-equipo">
                            <h3 class="h4 nombre-equipo">Joan Mauri</h3>
                            <p class="desc-equipo">
                                Técnico en Desarrollo de Software y estudiante de la Licenciatura en Sistemas. Orientado
                                a la calidad del código y la implementación de plataformas robustas, domina el
                                ecosistema del desarrollo web y la gestión estructurada de bases de datos. Su perfil
                                combina una fuerte base lógica en Programación Orientada a Objetos (POO) y programación
                                funcional, junto con un interés activo en las nuevas tecnologías aplicadas a la
                                Inteligencia Artificial. En su tiempo libre, encuentra el equilibrio entre la constancia
                                del boxeo y su afición por la gastronomía.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
