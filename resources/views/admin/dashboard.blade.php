@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Dashboard</h2>
            <p class="text-muted mb-0">El centro de control de TiendaUNNE.</p>
        </div>
    </div>

    {{-- Fila de Tarjetas de Estadísticas --}}
    <div class="row mb-4">
        {{-- Tarjeta 1 --}}
        <div class="col-6 col-md-3 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-bold">Pedidos activos</small>
                        <h3 class="fw-bold mb-0 mt-1">{{ $pedidosPendientes }}</h3>
                    </div>
                    <span class="fs-2">📦</span>
                </div>
            </div>
        </div>

        {{-- Tarjeta 2 --}}
        <div class="col-6 col-md-3 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-bold">Ticket medio</small>
                        <h3 class="fw-bold mb-0 mt-1">$ {{ number_format($ticketMedio, 0, ',', '.') }}</h3>
                    </div>
                    <span class="fs-2">💳</span>
                </div>
            </div>
        </div>

        {{-- Tarjeta 3 --}}
        <div class="col-6 col-md-3 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-bold">Usuarios registrados</small>
                        <h3 class="fw-bold mb-0 mt-1">{{ $usuariosRegistrados }}</h3>
                    </div>
                    <span class="fs-2 text-primary">👤</span>
                </div>
            </div>
        </div>

        {{-- Tarjeta 4 --}}
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-bold">Pedidos entregados</small>
                        <h3 class="fw-bold mb-0 mt-1">{{ $pedidosEntregados }}</h3>
                    </div>
                    <span class="fs-2">🚚</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Fila Principal: Tablas y Listas --}}
    <div class="row mb-5">
        {{-- Columna Izquierda: Últimos Pedidos --}}
        <div class="col-md-8 mb-4 mb-md-0">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Últimos pedidos</h5>
                        <a href="/admin/pedidos" class="text-decoration-none fw-bold text-primary">Ver todos</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead class="border-bottom">
                                <tr>
                                    <th class="text-muted small pb-3">Pedido</th>
                                    <th class="text-muted small pb-3">Cliente</th>
                                    <th class="text-muted small pb-3">Total</th>
                                    <th class="text-muted small pb-3">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ultimosPedidos as $pedido)
                                    <tr class="border-bottom">
                                        <td class="py-3 fw-bold">#{{ $pedido->id }}</td>
                                        <td class="py-3">{{ $pedido->user->name ?? 'Usuario Eliminado' }}
                                            {{ $pedido->user->last_name ?? '' }}</td>
                                        <td class="py-3 fw-bold text-dark">$
                                            {{ number_format($pedido->total, 0, ',', '.') }}</td>
                                        <td class="py-3">
                                            @if ($pedido->status == 'pendiente')
                                                <span
                                                    class="badge bg-warning bg-opacity-25 text-warning-emphasis border border-warning-subtle">Pendiente</span>
                                            @elseif($pedido->status == 'en_preparacion')
                                                <span
                                                    class="badge bg-info bg-opacity-10 text-info border border-info-subtle">Preparando</span>
                                            @elseif($pedido->status == 'listo_para_retirar')
                                                <span class="badge"
                                                    style="background-color: #E8DAEF; color: #6C3483; border: 1px solid #D2B4DE;">Para
                                                    retirar</span>
                                            @elseif($pedido->status == 'enviado')
                                                <span
                                                    class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle">Enviado</span>
                                            @elseif($pedido->status == 'entregado')
                                                <span
                                                    class="badge bg-success bg-opacity-10 text-success border border-success-subtle">Entregado</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">Aún no hay pedidos
                                            registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Columna Derecha: Top 5 Productos --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Top 5 productos vendidos</h5>

                    @forelse($topProductos as $index => $producto)
                        <div
                            class="d-flex justify-content-between align-items-center bg-light rounded-3 p-3 mb-{{ $loop->last ? '0' : '3' }}">
                            <div>
                                <strong class="d-block text-dark text-truncate"
                                    style="max-width: 170px;">{{ $producto->name }}</strong>
                                <small class="text-muted">Ranking #{{ $index + 1 }} ({{ $producto->total_vendido }}
                                    unid.)</small>
                            </div>
                            <span class="fs-4">
                                @if ($index == 0)
                                    🏆
                                @elseif($index == 1)
                                    🥈
                                @elseif($index == 2)
                                    🥉
                                @else
                                    🔥
                                @endif
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            Aún no hay productos vendidos para armar el ranking.
                        </div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>

    {{-- Gráfico de torta: Categorías más vendidas --}}
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="fw-bold mb-0">Categorías más vendidas</h5>
                            <small class="text-muted">Distribución de unidades vendidas por categoría</small>
                        </div>
                    </div>

                    @if ($categoriasMasVendidas->sum('total_vendido') > 0)
                        <div class="row align-items-center">
                            {{-- Gráfico --}}
                            <div class="col-md-5 d-flex justify-content-center">
                                <div style="position: relative; width: 280px; height: 280px;">
                                    <canvas id="graficoCategorias"></canvas>
                                </div>
                            </div>
                            {{-- Leyenda --}}
                            <div class="col-md-7 mt-4 mt-md-0">
                                <div class="row g-3">
                                    @php
                                        $colores = ['#4e79a7','#f28e2b','#e15759','#76b7b2','#59a14f','#edc948','#b07aa1','#ff9da7','#9c755f','#bab0ac'];
                                        $totalVendido = $categoriasMasVendidas->sum('total_vendido');
                                    @endphp
                                    @foreach ($categoriasMasVendidas as $i => $cat)
                                        @if ($cat->total_vendido > 0)
                                            @php $pct = $totalVendido > 0 ? round($cat->total_vendido / $totalVendido * 100, 1) : 0; @endphp
                                            <div class="col-sm-6">
                                                <div class="d-flex align-items-center gap-3 bg-light rounded-3 p-3">
                                                    <div class="flex-shrink-0 rounded-circle"
                                                        style="width:16px;height:16px;background-color:{{ $colores[$i % count($colores)] }};"></div>
                                                    <div class="flex-grow-1 min-w-0">
                                                        <div class="fw-bold text-dark text-truncate">{{ $cat->name }}</div>
                                                        <small class="text-muted">{{ $cat->total_vendido }} unid. &mdash; {{ $pct }}%</small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            Aún no hay ventas registradas para mostrar el gráfico.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    @if ($categoriasMasVendidas->sum('total_vendido') > 0)
    (function () {
        const labels  = @json($categoriasMasVendidas->where('total_vendido', '>', 0)->pluck('name')->values());
        const data    = @json($categoriasMasVendidas->where('total_vendido', '>', 0)->pluck('total_vendido')->values());
        const colores = ['#4e79a7','#f28e2b','#e15759','#76b7b2','#59a14f','#edc948','#b07aa1','#ff9da7','#9c755f','#bab0ac'];

        new Chart(document.getElementById('graficoCategorias'), {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colores.slice(0, labels.length),
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const pct   = total > 0 ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
                                return ` ${ctx.parsed} unid. (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });
    })();
    @endif
</script>
@endpush
