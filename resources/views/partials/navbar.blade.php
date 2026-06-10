<nav class="navbar navbar-expand-lg py-2 sticky-top" style="background-color: #021A54;" data-bs-theme="dark">
    <div class="container-fluid">

        <a class="navbar-brand" href="{{ url('/index') }}">
            <img src="{{ asset('img/logo_unne.png') }}" alt="Logo UNNE" style="height: 65px; object-fit: contain;">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            {{-- Enlaces de navegación principales --}}
            <ul class="navbar-nav ms-auto text-center">
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ Request::is('index') || Request::is('/') ? 'active fw-bold text-white' : '' }}"
                        href="/index">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ Request::is('quienes-somos') ? 'active fw-bold text-white' : '' }}"
                        href="/quienes-somos">Quiénes Somos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ Request::is('productos') ? 'active fw-bold text-white' : '' }}"
                        href="/productos">Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ Request::is('comercializacion') ? 'active fw-bold text-white' : '' }}"
                        href="/comercializacion">Comercialización</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ Request::is('contacto') ? 'active fw-bold text-white' : '' }}"
                        href="/contacto">Contacto</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ Request::is('terminos') ? 'active fw-bold text-white' : '' }}"
                        href="/terminos">Términos y Condiciones</a>
                </li>
            </ul>

            {{-- Sección derecha de la barra (Carrito + Usuario) --}}
            <div class="d-flex ms-lg-4 mt-3 mt-lg-0 justify-content-center align-items-center gap-3">

                @auth
                    {{-- SOLO VISIBLE SI ESTÁ LOGUEADO: Ícono del Carrito --}}
                    <a href="/carrito" class="btn btn-outline-light position-relative"
                        style="border-radius: 8px; border: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor"
                            class="bi bi-cart3" viewBox="0 0 16 16">
                            <path
                                d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </svg>

                        {{-- CÁLCULO DE UNIDADES TOTALES EN EL CARRITO --}}
                        @php
                            $totalUnidades = array_sum(array_column(session('cart', []), 'quantity'));
                        @endphp

                        @if ($totalUnidades > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $totalUnidades }}
                                <span class="visually-hidden">productos en el carrito</span>
                            </span>
                        @endif
                    </a>

                    {{-- Saludo apuntando al Perfil y botón de Salir --}}
                    <a href="/perfil" class="text-white fw-bold text-decoration-none me-2 hover-opacity"
                        style="transition: opacity 0.2s;" onmouseover="this.style.opacity='0.8'"
                        onmouseout="this.style.opacity='1'">
                        Hola, {{ Auth::user()->name }}
                    </a>

                    <form action="/logout" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm fw-bold" style="border-radius: 8px;">
                            Cerrar Sesión
                        </button>
                    </form>
                @else
                    {{-- SI NO ESTÁ LOGUEADO: Solo mostramos el botón de Ingresar --}}
                    <a href="/login" class="btn fw-bold d-flex align-items-center gap-2"
                        style="background-color: #ff6600; color: white; border-radius: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                            class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                            <path fill-rule="evenodd"
                                d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                        </svg>
                        Ingresar
                    </a>
                @endauth

            </div>

        </div>
    </div>
</nav>
