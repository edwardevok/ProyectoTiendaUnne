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
        <div class="col-md-3 mb-3 mb-md-0">
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
        <div class="col-md-3 mb-3 mb-md-0">
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
        <div class="col-md-3 mb-3 mb-md-0">
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
        <div class="col-md-3">
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
@endsection
