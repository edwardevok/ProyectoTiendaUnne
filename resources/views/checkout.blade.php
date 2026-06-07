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
    <title>TiendaUNNE | Finalizar Compra</title>
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
        <h2 class="fw-bold mb-4" style="color: #021A54;">Finalizar Compra</h2>

        <form action="/checkout/procesar" method="POST">
            @csrf

            <div class="row g-4">
                {{-- Columna Izquierda: Datos de Entrega y Pago Simuladp --}}
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3" style="color: #021A54;">1. Forma de Entrega</h5>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">¿Cómo querés recibir tu pedido?</label>
                                <select name="delivery_type" id="delivery_type" class="form-select border shadow-sm p-2"
                                    style="border-radius: 8px;" onchange="toggleAddressField()">
                                    <option value="campus" selected>Retiro en Campus UNNE (Gratis - campusUNNE)</option>
                                    <option value="domicilio">Envío a Domicilio (A convenir)</option>
                                </select>
                            </div>

                            {{-- Campo de dirección condicional (oculto por defecto) --}}
                            <div class="mb-3 d-none" id="address_container">
                                <label class="form-label fw-bold text-muted">Dirección de Entrega</label>
                                <input type="text" name="address" id="address"
                                    class="form-control border shadow-sm p-2"
                                    placeholder="Ej: Av. Las Heras 727, Resistencia" style="border-radius: 8px;">
                                @error('address')
                                    <small class="text-danger fw-bold">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3" style="color: #021A54;">2. Método de Pago (Simulado)</h5>
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="simulado"
                                        checked>
                                    <label class="form-check-input-label fw-bold text-dark" for="simulado">
                                        Simulación de pasarela de pago (Prueba de Entorno)
                                    </label>
                                    <p class="text-muted small mb-0 mt-1">Al hacer clic en el botón de abajo, se
                                        procesará la orden como aprobada de forma automática sin solicitar tarjetas
                                        reales.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Columna Derecha: Resumen del Pedido --}}
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4" style="color: #021A54;">Resumen del Pedido</h5>

                            @php $total = 0; @endphp
                            <ul class="list-group list-group-flush mb-4">
                                @foreach ($cart as $id => $details)
                                    @php $total += $details['price'] * $details['quantity']; @endphp
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 py-3">
                                        <div>
                                            <span class="fw-bold d-block text-dark">{{ $details['name'] }}</span>
                                            <small class="text-muted">Cantidad: {{ $details['quantity'] }} x $
                                                {{ number_format($details['price'], 0, ',', '.') }}</small>
                                        </div>
                                        <span class="fw-bold text-secondary">$
                                            {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="d-flex justify-content-between mb-4">
                                <span class="fw-bold fs-4">Total a Pagar</span>
                                <span class="fw-bold fs-4 text-primary">$
                                    {{ number_format($total, 0, ',', '.') }}</span>
                            </div>

                            <button type="submit" class="btn btn-orange w-100 py-3 fs-5 rounded-3 mb-2">
                                Confirmar y Simular Pago
                            </button>
                            <a href="/carrito"
                                class="btn btn-link text-muted text-decoration-none w-100 text-center fw-bold small">Volver
                                al carrito</a>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>

    {{-- Footer --}}
    @include('partials.footer')

    <script>
        // Función interactiva para mostrar/ocultar el campo de dirección
        function toggleAddressField() {
            const deliveryType = document.getElementById('delivery_type').value;
            const addressContainer = document.getElementById('address_container');
            const addressInput = document.getElementById('address');

            if (deliveryType === 'domicilio') {
                addressContainer.classList.remove('d-none');
                addressInput.setAttribute('required', 'required');
            } else {
                addressContainer.classList.add('d-none');
                addressInput.removeAttribute('required');
                addressInput.value = ''; // Limpiamos si escribió algo
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
