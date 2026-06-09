<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Tienda UNNE</title>

    {{-- Carga de Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Variables de colores institucionales */
        .text-unne-blue {
            color: #021a54;
        }

        .btn-unne-orange {
            background-color: #ff6600;
            color: white;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-unne-orange:hover {
            background-color: #e65c00;
            color: white;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">

    {{-- Barra de Navegación --}}
    @include('partials.navbar')

    {{-- Contenedor principal --}}
    <main class="flex-grow-1 d-flex align-items-center justify-content-center my-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-5">

                    {{-- Tarjeta del Login --}}
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-5 text-center">

                            {{-- Logo de la Universidad --}}
                            <img src="{{ asset('img/unne.png') }}" alt="Logo Tienda UNNE" class="img-fluid mb-4"
                                style="max-height: 80px;">

                            <h3 class="fw-bold mb-4 text-unne-blue">Iniciar Sesión</h3>

                            <form action="/login" method="POST">
                                @csrf

                                {{-- Campo de Correo Electrónico --}}
                                <div class="form-floating mb-3 text-start">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" placeholder="tu@email.com"
                                        value="{{ old('email') }}" required>
                                    <label for="email" class="text-muted">Correo Electrónico</label>

                                    @error('email')
                                        <div class="invalid-feedback fw-bold">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- Campo de Contraseña (AHORA CON MANEJO DE ERRORES) --}}
                                <div class="form-floating mb-4 text-start">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Contraseña" required>
                                    <label for="password" class="text-muted">Contraseña</label>

                                    @error('password')
                                        <div class="invalid-feedback fw-bold">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>


                                <button class="btn btn-unne-orange w-100 py-3 fw-bold rounded-3 fs-5" type="submit">
                                    Ingresar
                                </button>
                            </form>

                            {{-- Opción para ir al Registro --}}
                            <div class="mt-4 pt-3 border-top">
                                <p class="mb-1 text-muted">¿Todavía no tenés cuenta?</p>
                                <a href="/registro" class="text-unne-blue fw-bold text-decoration-none">
                                    Sumate a TiendaUNNE
                                </a>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    @include('partials.footer')

    {{-- Scripts de Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
