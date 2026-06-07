<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="icon" href="{{ asset('img/unne.png') }}" type="image/png">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet">
    <title>TiendaUNNE | Mi Perfil</title>
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8f9fa;
        }

        .btn-orange {
            background-color: #FF6600;
            color: white;
            font-weight: bold;
        }

        .btn-orange:hover {
            background-color: #e65c00;
            color: white;
        }
    </style>
</head>

<body>
    @include('partials.navbar')

    <div class="container my-5" style="min-height: 70vh;">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                <strong>¡Hecho!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            {{-- Columna Izquierda: Datos Personales --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4" style="color: #021A54;">Mis Datos</h5>
                        <form action="/perfil/actualizar" method="POST">
                            @csrf @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-muted">Nombre</label>
                                    <input type="text" name="name" class="form-control p-2"
                                        value="{{ $user->name }}" required style="border-radius: 8px;">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold text-muted">Apellido</label>
                                    <input type="text" name="last_name" class="form-control p-2"
                                        value="{{ $user->last_name }}" style="border-radius: 8px;">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control p-2"
                                    value="{{ $user->email }}" required style="border-radius: 8px;">
                            </div>
                            <button type="submit" class="btn btn-orange w-100 py-2 rounded-3">Guardar Cambios</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Columna Derecha: Seguimiento y Consultas --}}
            <div class="col-md-8">
                {{-- Pedidos con Scroll --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4" style="color: #021A54;">Seguimiento de mis Pedidos</h5>
                        @if ($pedidos->count() > 0)
                            <div class="accordion accordion-flush" id="accordionPedidos"
                                style="max-height: 400px; overflow-y: auto;">
                                @foreach ($pedidos as $pedido)
                                    <div class="accordion-item border-bottom py-2">
                                        <button class="accordion-button collapsed px-0 bg-transparent" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#col{{ $pedido->id }}">
                                            <div class="d-flex w-100 justify-content-between align-items-center pe-3">
                                                <div>
                                                    <span class="fw-bold text-dark">Pedido #{{ $pedido->id }}</span>
                                                    <small
                                                        class="text-muted d-block">{{ $pedido->created_at->format('d/m/Y') }}</small>
                                                </div>
                                                <span
                                                    class="badge bg-primary bg-opacity-10 text-primary">{{ strtoupper($pedido->status) }}</span>
                                            </div>
                                        </button>
                                        <div id="col{{ $pedido->id }}" class="accordion-collapse collapse"
                                            data-bs-parent="#accordionPedidos">
                                            <div class="accordion-body bg-light rounded mt-2">
                                                @foreach ($pedido->items as $item)
                                                    <p class="mb-0">{{ $item->quantity }}x
                                                        {{ $item->product->name ?? 'Producto' }}</p>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No tenés pedidos aún.</p>
                        @endif
                    </div>
                </div>

                {{-- Mis Consultas con Scroll (CORREGIDO) --}}
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4" style="color: #021A54;">Mis Consultas</h5>
                        <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                            <table class="table align-middle">
                                <thead class="text-muted small sticky-top bg-white">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Mensaje</th>
                                        <th>Estado</th>
                                        <th>Respuesta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($messages as $msg)
                                        <tr>
                                            <td class="small">{{ $msg->created_at->format('d/m/Y') }}</td>

                                            {{-- Corregido: Usamos 'body' --}}
                                            <td class="small" title="{{ $msg->body }}">
                                                {{ Str::limit($msg->body, 40) }}</td>

                                            {{-- Corregido: Usamos 'status' y verificamos si dice 'Resuelto' --}}
                                            <td>
                                                <span
                                                    class="badge {{ strtolower($msg->status) == 'resuelto' ? 'bg-success' : 'bg-warning' }}">
                                                    {{ ucfirst($msg->status) }}
                                                </span>
                                            </td>

                                            {{-- Corregido: Usamos 'reply' --}}
                                            <td class="small text-muted">{{ $msg->reply ?? 'Esperando...' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No realizaste consultas.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
