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
    <title>TiendaUNNE | Mi Carrito</title>
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
    {{-- NavBar --}}
    @include('partials.navbar')

    <div class="container my-5" style="min-height: 60vh;">
        <h2 class="fw-bold mb-4" style="color: #021A54;">Tu Carrito de Compras</h2>

        {{-- Mensaje de éxito al agregar o quitar productos --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Comprobamos si hay productos en el carrito --}}
        @if (count($cart) > 0)
            <div class="row g-4">
                {{-- Columna Izquierda: Lista de productos --}}
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table align-middle text-center mb-0">
                                    <thead class="text-muted border-bottom">
                                        <tr>
                                            <th class="text-start pb-3">Producto</th>
                                            <th class="pb-3">Precio</th>
                                            <th class="pb-3">Cantidad</th>
                                            <th class="pb-3">Subtotal</th>
                                            <th class="pb-3">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $total = 0; @endphp

                                        @foreach ($cart as $id => $details)
                                            @php $total += $details['price'] * $details['quantity']; @endphp
                                            <tr>
                                                <td class="text-start py-3">
                                                    <div class="d-flex align-items-center gap-3">
                                                        @if ($details['image'])
                                                            <img src="{{ asset('img/' . $details['image']) }}"
                                                                alt="{{ $details['name'] }}"
                                                                style="width: 60px; height: 60px; object-fit: cover;"
                                                                class="rounded-3 shadow-sm">
                                                        @else
                                                            <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted"
                                                                style="width: 60px; height: 60px;">IMG</div>
                                                        @endif
                                                        <span class="fw-bold">{{ $details['name'] }}</span>
                                                    </div>
                                                </td>
                                                <td class="py-3 fw-bold text-secondary">$
                                                    {{ number_format($details['price'], 0, ',', '.') }}</td>
                                                <td class="py-3 fw-bold">{{ $details['quantity'] }}</td>
                                                <td class="py-3 fw-bold text-primary">$
                                                    {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}
                                                </td>
                                                <td class="py-3">
                                                    {{-- Botón para eliminar un producto específico --}}
                                                    <form action="/carrito/quitar/{{ $id }}" method="POST"
                                                        class="m-0">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-danger fw-bold rounded-3">Quitar</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Botón para vaciar todo el carrito --}}
                    <div class="mt-3">
                        <form action="/carrito/vaciar" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link text-danger text-decoration-none fw-bold"
                                onclick="return confirm('¿Estás seguro de vaciar el carrito?');">
                                Vaciar carrito completo
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Columna Derecha: Resumen de la compra --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Resumen de compra</h5>

                            <div class="d-flex justify-content-between mb-3 text-muted">
                                <span>Subtotal</span>
                                <span>$ {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3 text-muted">
                                <span>Envío</span>
                                <span>A convenir</span>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-between mb-4">
                                <span class="fw-bold fs-5">Total</span>
                                <span class="fw-bold fs-5 text-primary">$
                                    {{ number_format($total, 0, ',', '.') }}</span>
                            </div>

                            <a href="/checkout"
                                class="btn btn-orange w-100 py-2 fs-5 rounded-3 text-center text-decoration-none">
                                Iniciar Pago
                            </a>
                            <a href="/productos" class="btn btn-outline-dark w-100 mt-2 py-2 fw-bold rounded-3">Seguir
                                comprando</a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Diseño cuando el carrito está vacío --}}
            <div class="card border-0 shadow-sm rounded-4 text-center py-5">
                <div class="card-body">
                    <h4 class="text-muted fw-bold mb-3">Tu carrito está vacío</h4>
                    <p class="text-muted mb-4">¿No sabés qué comprar? ¡Miles de productos te esperan!</p>
                    <a href="/productos" class="btn btn-orange px-5 py-2 fw-bold rounded-3">Ver productos</a>
                </div>
            </div>
        @endif

    </div>

    {{-- Footer --}}
    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
