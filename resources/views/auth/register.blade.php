<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - Tienda UNNE</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
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

    @include('partials.navbar')

    <main class="flex-grow-1 d-flex align-items-center justify-content-center my-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-6">

                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-5 text-center">

                            <img src="{{ asset('img/unne.png') }}" alt="Logo Tienda UNNE" class="img-fluid mb-4"
                                style="max-height: 80px;">

                            <h3 class="fw-bold mb-4 text-unne-blue">Crear Cuenta</h3>

                            {{-- ALERTA DE ERRORES: Aquí se mostrarán los mensajes en rojo --}}
                            @if ($errors->any())
                                <div class="alert alert-danger text-start">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="/registro" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 text-start">
                                            {{-- Agregamos value="{{ old('name') }}" para no perder el dato --}}
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ old('name') }}" placeholder="Ej. Juan" required>
                                            <label for="name" class="text-muted">Nombre</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 text-start">
                                            <input type="text" class="form-control" id="last_name" name="last_name"
                                                value="{{ old('last_name') }}" placeholder="Ej. Pérez" required>
                                            <label for="last_name" class="text-muted">Apellido</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-floating mb-3 text-start">
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email') }}" placeholder="tu@email.com" required>
                                    <label for="email" class="text-muted">Correo Electrónico</label>
                                </div>

                                {{-- Contraseña con texto de ayuda --}}
                                <div class="form-floating mb-1 text-start">
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Contraseña" required>
                                    <label for="password" class="text-muted">Contraseña</label>
                                </div>
                                <div class="text-start mb-3 ms-2">
                                    <small class="text-muted" style="font-size: 0.85rem;">
                                        * Mínimo 8 caracteres, al menos una letra y una mayúscula.
                                    </small>
                                </div>

                                {{-- Confirmar Contraseña --}}
                                <div class="form-floating mb-4 text-start">
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" placeholder="Confirmar Contraseña" required>
                                    <label for="password_confirmation" class="text-muted">Confirmar Contraseña</label>
                                </div>

                                <button class="btn btn-unne-orange w-100 py-3 fw-bold rounded-3 fs-5" type="submit">
                                    Registrarme
                                </button>
                            </form>

                            <div class="mt-4 pt-3 border-top">
                                <p class="mb-1 text-muted">¿Ya tenés una cuenta?</p>
                                <a href="/login" class="text-unne-blue fw-bold text-decoration-none">
                                    Ingresá a TiendaUNNE
                                </a>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
