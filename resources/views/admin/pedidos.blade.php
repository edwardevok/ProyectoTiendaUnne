@extends('layouts.admin')

@section('title', 'Pedidos')

@section('content')
    {{-- Encabezado de la página --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Pedidos</h2>
            <p class="text-muted mb-0">El centro de control de TiendaUNNE.</p>
        </div>
    </div>

    {{-- Alerta de éxito al cambiar un estado --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <strong>¡Actualizado!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Tarjeta Principal (Solo Activos) --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">

            {{-- Buscador y Filtros dinámicos --}}
            <form action="/admin/pedidos" method="GET" class="d-flex flex-wrap gap-3 mb-4">
                <input type="text" name="search" class="form-control border shadow-sm"
                    placeholder="Buscar pedido por Nro (#)" style="width: 250px; border-radius: 8px;"
                    value="{{ request('search') }}">

                <select name="status" class="form-select border shadow-sm" style="width: 200px; border-radius: 8px;"
                    onchange="this.form.submit()">
                    <option value="Todos los estados" {{ request('status') == 'Todos los estados' ? 'selected' : '' }}>Todos
                        los estados activos</option>
                    <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="en_preparacion" {{ request('status') == 'en_preparacion' ? 'selected' : '' }}>En
                        Preparación</option>
                    <option value="listo_para_retirar" {{ request('status') == 'listo_para_retirar' ? 'selected' : '' }}>
                        Listo para retirar</option>
                    <option value="enviado" {{ request('status') == 'enviado' ? 'selected' : '' }}>Enviado</option>
                </select>

                <select name="sort_total" class="form-select border shadow-sm" style="width: 200px; border-radius: 8px;"
                    onchange="this.form.submit()">
                    <option value="" {{ request('sort_total') == '' ? 'selected' : '' }}>Ordenar por total</option>
                    <option value="desc" {{ request('sort_total') == 'desc' ? 'selected' : '' }}>Mayor valor primero
                    </option>
                    <option value="asc" {{ request('sort_total') == 'asc' ? 'selected' : '' }}>Menor valor primero
                    </option>
                </select>

                {{-- Botón de limpiar filtros --}}
                @if (request('search') || (request('status') && request('status') != 'Todos los estados') || request('sort_total'))
                    <a href="/admin/pedidos" class="btn btn-light border shadow-sm fw-bold"
                        style="border-radius: 8px;">Limpiar</a>
                @endif
            </form>

            <h5 class="fw-bold mb-4">Gestión de pedidos en curso</h5>

            {{-- Tabla de Pedidos --}}
            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0 text-center">
                    <thead class="border-bottom">
                        <tr>
                            <th class="text-muted small pb-3 text-start">Pedido</th>
                            <th class="text-muted small pb-3 text-start">Cliente</th>
                            <th class="text-muted small pb-3">Fecha</th>
                            <th class="text-muted small pb-3">Entrega</th>
                            <th class="text-muted small pb-3">Total</th>
                            <th class="text-muted small pb-3 text-start">Estado</th>
                            <th class="text-muted small pb-3 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pedidos as $pedido)
                            <tr class="border-bottom">
                                <td class="py-3 fw-bold text-dark text-start">#{{ $pedido->id }}</td>

                                <td class="py-3 text-start">
                                    @if ($pedido->user)
                                        <span class="d-block fw-bold text-dark mb-1">
                                            {{ $pedido->user->name }} {{ $pedido->user->last_name }}
                                        </span>
                                        <small class="text-muted">{{ $pedido->user->email }}</small>
                                    @else
                                        <span class="d-block fw-bold text-muted mb-1">Usuario Eliminado</span>
                                        <small class="text-muted">Sin registro</small>
                                    @endif
                                </td>

                                <td class="py-3 text-muted small">
                                    {{ $pedido->created_at->format('d/m/Y H:i') }}
                                </td>

                                <td class="py-3 text-muted small">
                                    @if ($pedido->delivery_type == 'campus')
                                        <span class="badge bg-secondary">Campus</span>
                                    @else
                                        <span class="badge bg-dark">Domicilio</span>
                                    @endif
                                </td>

                                <td class="py-3 fw-bold text-primary">$ {{ number_format($pedido->total, 0, ',', '.') }}
                                </td>

                                <td class="py-3 text-start">
                                    <div class="d-flex align-items-center gap-2">
                                        {{-- Badge dinámico visual --}}
                                        @if ($pedido->status == 'pendiente')
                                            <span
                                                class="badge bg-warning bg-opacity-25 text-warning-emphasis border border-warning-subtle px-2 py-1"
                                                style="min-width: 90px;">Pendiente</span>
                                        @elseif($pedido->status == 'listo_para_retirar')
                                            <span
                                                class="badge bg-dark bg-opacity-10 text-dark border border-dark-subtle px-2 py-1"
                                                style="width: 85px; background-color: #E8DAEF !important; color: #6C3483 !important; border-color: #D2B4DE !important;">Para
                                                retirar</span>
                                        @elseif($pedido->status == 'en_preparacion')
                                            <span
                                                class="badge bg-info bg-opacity-10 text-info border border-info-subtle px-2 py-1"
                                                style="min-width: 90px;">Preparando</span>
                                        @elseif($pedido->status == 'enviado')
                                            <span
                                                class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle px-2 py-1"
                                                style="min-width: 90px;">Enviado</span>
                                        @endif

                                        {{-- Formulario invisible para actualizar estado --}}
                                        <form action="/admin/pedidos/{{ $pedido->id }}/estado" method="POST"
                                            class="m-0">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="form-select form-select-sm w-auto"
                                                onchange="this.form.submit()">
                                                <option value="pendiente"
                                                    {{ $pedido->status == 'pendiente' ? 'selected' : '' }}>Pendiente
                                                </option>
                                                <option value="en_preparacion"
                                                    {{ $pedido->status == 'en_preparacion' ? 'selected' : '' }}>En
                                                    Preparación</option>
                                                <option value="listo_para_retirar"
                                                    {{ $pedido->status == 'listo_para_retirar' ? 'selected' : '' }}>Listo
                                                    para retirar</option>
                                                <option value="enviado"
                                                    {{ $pedido->status == 'enviado' ? 'selected' : '' }}>Enviado</option>
                                                <option value="entregado"
                                                    {{ $pedido->status == 'entregado' ? 'selected' : '' }}>Entregado
                                                </option>
                                            </select>
                                        </form>
                                    </div>
                                </td>

                                <td class="py-3 text-end">
                                    <button type="button" class="btn btn-sm btn-light border fw-bold px-3"
                                        style="border-radius: 6px;" data-bs-toggle="modal"
                                        data-bs-target="#modalPedido{{ $pedido->id }}">Ver detalle</button>

                                    {{-- MODAL CON EL DETALLE DEL PEDIDO --}}
                                    <div class="modal fade text-start" id="modalPedido{{ $pedido->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content border-0 shadow rounded-4">
                                                <div class="modal-header border-bottom">
                                                    <h5 class="modal-title fw-bold" style="color: #021A54;">Detalle del
                                                        Pedido #{{ $pedido->id }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="row mb-4">
                                                        <div class="col-md-6 mb-3 mb-md-0">
                                                            <h6 class="fw-bold text-muted mb-2 text-uppercase"
                                                                style="font-size: 0.8rem;">Datos del Cliente</h6>
                                                            <strong class="text-dark d-block mb-1">
                                                                {{ $pedido->user->name ?? 'Usuario Eliminado' }}
                                                                {{ $pedido->user->last_name ?? '' }}
                                                            </strong>
                                                            <p class="mb-0 text-muted">
                                                                {{ $pedido->user->email ?? 'Sin registro' }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6 class="fw-bold text-muted mb-2 text-uppercase"
                                                                style="font-size: 0.8rem;">Datos de Entrega</h6>
                                                            <p class="mb-1 text-dark"><strong>Método:</strong>
                                                                {{ $pedido->delivery_type == 'campus' ? 'Retiro en Campus UNNE' : 'Envío a Domicilio' }}
                                                            </p>
                                                            <p class="mb-0 text-muted"><strong>Dirección:</strong>
                                                                {{ $pedido->address }}</p>
                                                        </div>
                                                    </div>

                                                    <h6 class="fw-bold text-muted mb-3 text-uppercase"
                                                        style="font-size: 0.8rem;">Artículos del Pedido</h6>
                                                    <div class="table-responsive border rounded-3">
                                                        <table class="table align-middle text-center mb-0">
                                                            <thead class="bg-light">
                                                                <tr>
                                                                    <th class="small text-muted text-start ps-3">Producto
                                                                    </th>
                                                                    <th class="small text-muted">Cantidad</th>
                                                                    <th class="small text-muted">Precio Unit.</th>
                                                                    <th class="small text-muted pe-3">Subtotal</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($pedido->items as $item)
                                                                    <tr>
                                                                        <td class="text-start ps-3 text-dark fw-bold">
                                                                            {{ $item->product ? $item->product->name : 'Producto Eliminado' }}
                                                                        </td>
                                                                        <td>{{ $item->quantity }}</td>
                                                                        <td>$
                                                                            {{ number_format($item->price, 0, ',', '.') }}
                                                                        </td>
                                                                        <td class="fw-bold text-dark pe-3">$
                                                                            {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot class="bg-light">
                                                                <tr>
                                                                    <td colspan="3" class="text-end fw-bold text-dark">
                                                                        Total pagado:</td>
                                                                    <td class="fw-bold text-primary fs-5 pe-3">$
                                                                        {{ number_format($pedido->total, 0, ',', '.') }}
                                                                    </td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top-0">
                                                    <button type="button"
                                                        class="btn btn-secondary fw-bold rounded-3 px-4"
                                                        data-bs-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">No hay pedidos que coincidan con la
                                    búsqueda.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $pedidos->links() }}
            </div>

        </div>
    </div>

    {{-- Acordeón de Pedidos Entregados (Historial) --}}
    <div class="accordion shadow-sm rounded-4" id="accordionEntregados">
        <div class="accordion-item border-0 rounded-4">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed fw-bold text-muted bg-white rounded-4" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapseEntregados">
                    📦 Ver Historial de Pedidos Entregados
                </button>
            </h2>
            <div id="collapseEntregados" class="accordion-collapse collapse" data-bs-parent="#accordionEntregados">
                <div class="accordion-body p-0 bg-light rounded-bottom-4">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table align-middle text-center mb-0">
                            <thead class="bg-white sticky-top shadow-sm">
                                <tr>
                                    <th class="text-muted small py-3 text-start ps-4">Pedido</th>
                                    <th class="text-muted small py-3 text-start">Cliente</th>
                                    <th class="text-muted small py-3">Fecha Entregado</th>
                                    <th class="text-muted small py-3">Total</th>
                                    <th class="text-muted small py-3 text-start">Estado</th>
                                    <th class="text-muted small py-3 text-end pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pedidosEntregados as $pedido)
                                    <tr class="border-bottom">
                                        <td class="py-3 fw-bold text-dark text-start ps-4">#{{ $pedido->id }}</td>
                                        <td class="py-3 text-start">
                                            @if ($pedido->user)
                                                <span class="d-block fw-bold text-dark mb-1">{{ $pedido->user->name }}
                                                    {{ $pedido->user->last_name }}</span>
                                                <small class="text-muted">{{ $pedido->user->email }}</small>
                                            @else
                                                <span class="d-block fw-bold text-muted mb-1">Usuario Eliminado</span>
                                                <small class="text-muted">Sin registro</small>
                                            @endif
                                        </td>
                                        <td class="py-3 text-muted small">{{ $pedido->updated_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="py-3 fw-bold text-primary">$
                                            {{ number_format($pedido->total, 0, ',', '.') }}</td>
                                        <td class="py-3 text-start">
                                            <div class="d-flex align-items-center gap-2">
                                                <span
                                                    class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-2 py-1"
                                                    style="min-width: 90px;">Entregado</span>

                                                {{-- Mantenemos el select por si el admin se equivocó y necesita devolverlo a un estado anterior --}}
                                                <form action="/admin/pedidos/{{ $pedido->id }}/estado" method="POST"
                                                    class="m-0">
                                                    @csrf @method('PUT')
                                                    <select name="status"
                                                        class="form-select form-select-sm w-auto text-muted"
                                                        onchange="this.form.submit()">
                                                        <option value="entregado" selected>Entregado</option>
                                                        <option value="enviado">Deshacer (Volver a Enviado)</option>
                                                    </select>
                                                </form>
                                            </div>
                                        </td>
                                        <td class="py-3 text-end pe-4">
                                            <button type="button" class="btn btn-sm btn-light border fw-bold px-3"
                                                style="border-radius: 6px;" data-bs-toggle="modal"
                                                data-bs-target="#modalPedidoEntregado{{ $pedido->id }}">Ver
                                                detalle</button>

                                            {{-- MODAL DEL PEDIDO ENTREGADO --}}
                                            <div class="modal fade text-start"
                                                id="modalPedidoEntregado{{ $pedido->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow rounded-4">
                                                        <div class="modal-header border-bottom">
                                                            <h5 class="modal-title fw-bold" style="color: #021A54;">
                                                                Historial - Pedido #{{ $pedido->id }}</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            <div class="row mb-4">
                                                                <div class="col-md-6 mb-3 mb-md-0">
                                                                    <h6 class="fw-bold text-muted mb-2 text-uppercase"
                                                                        style="font-size: 0.8rem;">Datos del Cliente</h6>
                                                                    <strong
                                                                        class="text-dark d-block mb-1">{{ $pedido->user->name ?? 'Usuario Eliminado' }}
                                                                        {{ $pedido->user->last_name ?? '' }}</strong>
                                                                    <p class="mb-0 text-muted">
                                                                        {{ $pedido->user->email ?? 'Sin registro' }}</p>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h6 class="fw-bold text-muted mb-2 text-uppercase"
                                                                        style="font-size: 0.8rem;">Datos de Entrega</h6>
                                                                    <p class="mb-1 text-dark"><strong>Método:</strong>
                                                                        {{ $pedido->delivery_type == 'campus' ? 'Retiro en Campus UNNE' : 'Envío a Domicilio' }}
                                                                    </p>
                                                                    <p class="mb-0 text-muted"><strong>Dirección:</strong>
                                                                        {{ $pedido->address }}</p>
                                                                </div>
                                                            </div>
                                                            <h6 class="fw-bold text-muted mb-3 text-uppercase"
                                                                style="font-size: 0.8rem;">Artículos del Pedido</h6>
                                                            <div class="table-responsive border rounded-3">
                                                                <table class="table align-middle text-center mb-0">
                                                                    <thead class="bg-light">
                                                                        <tr>
                                                                            <th class="small text-muted text-start ps-3">
                                                                                Producto</th>
                                                                            <th class="small text-muted">Cantidad</th>
                                                                            <th class="small text-muted">Precio Unit.</th>
                                                                            <th class="small text-muted pe-3">Subtotal</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($pedido->items as $item)
                                                                            <tr>
                                                                                <td
                                                                                    class="text-start ps-3 text-dark fw-bold">
                                                                                    {{ $item->product ? $item->product->name : 'Producto Eliminado' }}
                                                                                </td>
                                                                                <td>{{ $item->quantity }}</td>
                                                                                <td>$
                                                                                    {{ number_format($item->price, 0, ',', '.') }}
                                                                                </td>
                                                                                <td class="fw-bold text-dark pe-3">$
                                                                                    {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                    <tfoot class="bg-light">
                                                                        <tr>
                                                                            <td colspan="3"
                                                                                class="text-end fw-bold text-dark">Total
                                                                                pagado:</td>
                                                                            <td class="fw-bold text-primary fs-5 pe-3">$
                                                                                {{ number_format($pedido->total, 0, ',', '.') }}
                                                                            </td>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-top-0">
                                                            <button type="button"
                                                                class="btn btn-secondary fw-bold rounded-3 px-4"
                                                                data-bs-dismiss="modal">Cerrar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- FIN MODAL ENTREGADO --}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">No hay pedidos entregados
                                            en el historial.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
