<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TiendaUNNE | Contacto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/contacto.css') }}">
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
    {{-- NavBar --}}
    @include('partials.navbar')
    {{-- NavBar end --}}

    <!-- Banner de bienvenida -->
    {{-- Cuerpo --}}
    <section class="hero-quienes-somos position-relative d-flex align-items-center justify-content-center text-center">

        {{-- Capa oscura para que el texto resalte (Overlay) --}}
        <div class="overlay-hero"></div>

        {{-- Contenido del Banner --}}
        <div class="container position-relative" style="z-index: 2;">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8">
                    {{-- Un pequeño subtítulo naranja para darle elegancia --}}
                    <h1 class="display-3 fw-bold text-white mb-3">¡Escribinos!</h1>
                    <p class="lead text-white-50 mb-0">
                        Estamos para escucharte. Aquí resolvemos tus dudas sobre envíos, cambios o reclamos de la Tienda
                        UNNE.
                    </p>
                </div>
            </div>
        </div>

    </section>

    <!-- Mapa y cards de direcciones -->

    <section class="container mb-5 mt-5">
        <div class="row g-4">

            {{-- Columna del Mapa --}}
            <div class="col-md-7">
                <div class="card h-100 shadow-sm border-0 overflow-hidden" style="min-height: 500px;">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3540.123456789!2d-58.8341!3d-27.4692!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjfCsDI4JzA5LjEiUyA1OMKwNTAnMDIuOCJX!5e0!3m2!1ses!2sar!4v1620000000000"
                        width="100%" height="100%" style="border:0; min-height: 500px;" allowfullscreen=""
                        loading="lazy">
                    </iframe>
                </div>
            </div>

            {{-- Columna de Información Legal y Ubicaciones --}}
            <div class="col-md-5">
                <div class="card h-100 shadow-sm border-0 p-4" style="background-color: #f8f9fa;">
                    <div class="card-body">
                        <h2 class="fw-bold mb-4" style="color: #021A54;">Información de Contacto</h2>

                        {{-- Sección Nueva: Información Legal (Consigna del profesor) --}}
                        <div class="mb-4 p-3 bg-white border rounded shadow-sm">
                            <h5 class="fw-bold mb-3" style="color: #FF6600; font-size: 1.1rem;">Datos Legales</h5>
                            <ul class="list-unstyled text-secondary small mb-0">
                                <li class="mb-2"><i class="bi bi-building me-2"></i><strong>Razón Social:</strong>
                                    Tienda UNNE S.A.</li>
                                <li class="mb-2"><i class="bi bi-person-badge me-2"></i><strong>Titular:</strong> Lic.
                                    Nombre del Titular</li>
                                <li class="mb-2"><i class="bi bi-bank me-2"></i><strong>Domicilio Legal:</strong> 25
                                    de Mayo 868, Corrientes, Argentina</li>
                                <li class="mb-2"><i class="bi bi-telephone me-2"></i><strong>Teléfono
                                        Central:</strong> +54 379 442-0000</li>
                                <li class="mb-0"><i class="bi bi-envelope me-2"></i><strong>Email Comercial:</strong>
                                    administracion@tienda.unne.edu.ar</li>
                            </ul>
                        </div>

                        <hr class="my-4">

                        {{-- Sección Existente: Ubicaciones --}}
                        <h5 class="fw-bold text-primary mb-3" style="font-size: 1.1rem;">Nuestras Sucursales</h5>

                        <div class="mb-3">
                            <h6 class="fw-bold text-dark mb-1">Campus Sargento Cabral</h6>
                            <p class="mb-1 text-secondary small"><i class="bi bi-geo-alt"></i> Juan Bautista Cabral
                                2131, Corrientes</p>
                            <p class="text-muted small mb-0"><i class="bi bi-clock"></i> L-V: 08:00 - 20:00 hs</p>
                        </div>

                        <div class="mb-0">
                            <h6 class="fw-bold text-dark mb-1">Campus Deodoro Roca</h6>
                            <p class="mb-1 text-secondary small"><i class="bi bi-geo-alt"></i> Av. Libertad 5470,
                                Corrientes</p>
                            <p class="text-muted small mb-0"><i class="bi bi-clock"></i> L-V: 09:00 - 18:00 hs</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>



    {{-- Ponte en contacto --}}
    <section class="container mb-5 pb-5 mt-0 pt-0 text-center">

        <h2 class="fw-bold mb-3" style="color: #021A54;">Ponte en contacto</h2>
        <p class="text-secondary mx-auto mb-5" style="max-width: 600px;">
            ¿Tienes alguna duda sobre tu pedido o una consulta general de la Tienda UNNE?
            Completa el formulario y te responderemos a la brevedad.
        </p>

        {{-- Mensaje de éxito de Laravel --}}
        @if (session('success'))
            <div class="alert alert-success text-center mb-4 mx-auto shadow-sm rounded-3" style="max-width: 600px;">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        {{-- CASO 1: SI EL USUARIO ESTÁ LOGUEADO --}}
        @auth
            <form id="formContacto" action="/contacto" method="POST" class="text-start mx-auto p-4 shadow-sm rounded"
                style="max-width: 600px; background-color: #f8f9fa; border-radius: 12px;">
                @csrf

                {{-- Campo Asunto --}}
                <div class="mb-3">
                    <label for="asunto" class="form-label text-secondary fw-bold">Asunto *</label>
                    <input type="text" class="form-control" id="asunto" name="asunto"
                        placeholder="Ej. Problema con mi pedido / Duda de envíos" required>
                </div>

                {{-- Campo Mensaje --}}
                <div class="mb-4">
                    <label for="mensaje" class="form-label text-secondary fw-bold">Mensaje / Consulta *</label>
                    <textarea class="form-control" id="mensaje" name="mensaje" rows="5"
                        placeholder="Escribe detalladamente tu consulta aquí..." required></textarea>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-lg px-5 py-3 fw-bold text-white shadow w-100"
                        style="background-color: #FF6600; border-radius: 50px; border: none; transition: 0.3s;">
                        Enviar Mensaje
                    </button>
                </div>
            </form>
        @endauth

        {{-- CASO 2: SI EL USUARIO ES UN INVITADO (NO LOGUEADO) --}}
        @guest
            <div class="text-center mx-auto p-5 shadow-sm border"
                style="max-width: 600px; background-color: #f8f9fa; border-radius: 12px;">
                <span class="material-symbols-rounded mb-3" style="font-size: 4rem; color: #021A54;">lock_open</span>
                <h4 class="fw-bold mb-2" style="color: #021A54;">¿Deseas enviarnos un mensaje?</h4>
                <p class="text-secondary mb-4 mx-auto" style="max-width: 400px;">
                    Para mantener la seguridad de la tienda y darte una atención personalizada, necesitas iniciar sesión.
                </p>
                <a href="/login" class="btn btn-lg px-5 py-2 fw-bold text-white shadow-sm"
                    style="background-color: #021A54; border-radius: 50px; border: none; transition: 0.3s;">
                    Iniciar Sesión
                </a>
            </div>
        @endguest

    </section>

    <div class="modal fade" id="modalAgradecimiento" tabindex="-1" aria-labelledby="modalAgradecimientoLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                {{-- Encabezado con los colores de la Tienda --}}
                <div class="modal-header text-white"
                    style="background-color: #021A54; border-bottom: 4px solid #FF6600;">
                    <h5 class="modal-title fw-bold" id="modalAgradecimientoLabel">¡Mensaje Recibido!</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                {{-- Cuerpo del Modal --}}
                <div class="modal-body text-center p-5">
                    {{-- Usando el icono de Material Symbols que ya tenías en tu head --}}
                    <span class="material-symbols-rounded mb-3" style="font-size: 5rem; color: #FF6600;">
                        check_circle
                    </span>
                    <h3 class="fw-bold mb-3" style="color: #021A54;">¡Gracias, <span id="modalNombreUsuario"></span>!
                    </h3>
                    <p class="text-secondary fs-5 mb-0">
                        Nos pondremos en contacto a la brevedad respondiendo a tu correo:<br>
                        <strong class="text-dark" id="modalMailUsuario"></strong>
                    </p>
                </div>

                {{-- Footer del Modal --}}
                <div class="modal-footer justify-content-center border-0 pb-4 pt-0">
                    <button type="button" class="btn px-4 py-2 text-white fw-bold"
                        style="background-color: #021A54; border-radius: 50px;" data-bs-dismiss="modal">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    </div>


    {{-- Footer --}}
    @include('partials.footer')
    {{-- Footer end --}}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
