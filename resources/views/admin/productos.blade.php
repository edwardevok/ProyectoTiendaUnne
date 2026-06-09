@extends('layouts.admin')

@section('title', 'Productos')

@section('content')
    {{-- Encabezado de la página --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Productos</h2>
            <p class="text-muted mb-0">El centro de control de TiendaUNNE.</p>
        </div>
    </div>

    {{-- Tarjeta Principal --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-4">Gestión de productos</h5>

            {{-- Barra de herramientas (Filtros y botón verde) --}}
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center mb-4 gap-3">

                {{-- FORMULARIO DE FILTROS SEPARADOS --}}
                <form action="/admin/productos" method="GET"
                    class="d-flex flex-wrap flex-lg-nowrap align-items-center gap-2 m-0 flex-grow-1">

                    {{-- Buscador con botón --}}
                    <div class="input-group shadow-sm flex-grow-1"
                        style="min-width: 220px; max-width: 300px; border-radius: 8px; overflow: hidden;">
                        <input type="text" name="search" class="form-control border-0" placeholder="Buscar producto..."
                            value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit" style="background-color: #0d6efd;">Buscar</button>
                    </div>

                    {{-- Filtro de Categorías --}}
                    <select name="category_id" class="form-select border shadow-sm w-auto flex-shrink-0"
                        style="border-radius: 8px;" onchange="this.form.submit()">
                        <option value="">Todas las categorías</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}"
                                {{ request('category_id') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->name }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Desplegable 1: Precio --}}
                    <select name="sort_price" class="form-select border shadow-sm w-auto flex-shrink-0"
                        style="border-radius: 8px;" onchange="this.form.submit()">
                        <option value="">Filtrar Precio...</option>
                        <option value="desc" {{ request('sort_price') == 'desc' ? 'selected' : '' }}>Mayor Precio</option>
                        <option value="asc" {{ request('sort_price') == 'asc' ? 'selected' : '' }}>Menor Precio</option>
                    </select>

                    {{-- Desplegable 2: Stock --}}
                    <select name="sort_stock" class="form-select border shadow-sm w-auto flex-shrink-0"
                        style="border-radius: 8px;" onchange="this.form.submit()">
                        <option value="">Filtrar Stock...</option>
                        <option value="desc" {{ request('sort_stock') == 'desc' ? 'selected' : '' }}>Mayor Stock</option>
                        <option value="asc" {{ request('sort_stock') == 'asc' ? 'selected' : '' }}>Menor Stock</option>
                    </select>

                    {{-- Botón para limpiar filtros --}}
                    @if (request('search') || request('category_id') || request('sort_price') || request('sort_stock'))
                        <a href="/admin/productos" class="btn btn-light border shadow-sm fw-bold flex-shrink-0"
                            style="border-radius: 8px;">Limpiar</a>
                    @endif
                </form>

                {{-- Botón de Crear --}}
                <div class="flex-shrink-0">
                    <a href="/admin/productos/crear" class="btn fw-bold text-white px-4 shadow-sm w-100 text-nowrap"
                        style="background-color: #198754; border-radius: 8px;">Crear producto</a>
                </div>
            </div>

            {{-- Tabla de Productos Activos --}}
            <div class="table-responsive border rounded-3">
                <table class="table align-middle text-center mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-muted small py-3 text-start ps-4">Imagen</th>
                            <th class="text-muted small py-3 text-start">Producto</th>
                            <th class="text-muted small py-3">Categoría</th>
                            <th class="text-muted small py-3">Stock</th>
                            <th class="text-muted small py-3">Precio</th>
                            <th class="text-muted small py-3 text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($productos as $producto)
                            <tr class="border-bottom">
                                <td class="py-3 text-start ps-4">
                                    @if ($producto->image)
                                        <img src="{{ asset('img/' . $producto->image) }}" alt="{{ $producto->name }}"
                                            style="width: 50px; height: 50px; object-fit: cover;"
                                            class="rounded-3 shadow-sm border">
                                    @else
                                        <div class="bg-secondary bg-opacity-10 border rounded-3 d-flex justify-content-center align-items-center text-secondary fw-bold fs-5"
                                            style="width: 50px; height: 50px;">
                                            {{ substr($producto->name, 0, 1) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 fw-bold text-start text-dark">{{ $producto->name }}</td>
                                <td class="py-3 text-muted">
                                    {{ $producto->category ? $producto->category->name : 'Sin categoría' }}</td>
                                <td class="py-3">
                                    {{-- Etiqueta visual de stock --}}
                                    @if ($producto->stock == 0)
                                        <span
                                            class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle px-2 py-1">Agotado
                                            (0)</span>
                                    @elseif($producto->stock <= 5)
                                        <span
                                            class="badge bg-warning bg-opacity-10 text-warning-emphasis border border-warning-subtle px-2 py-1">Poco
                                            ({{ $producto->stock }})</span>
                                    @else
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-2 py-1">{{ $producto->stock }}</span>
                                    @endif
                                </td>
                                <td class="py-3 fw-bold text-primary">$ {{ number_format($producto->price, 0, ',', '.') }}
                                </td>
                                <td class="py-3 text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="/admin/productos/{{ $producto->id }}/editar"
                                            class="btn btn-sm text-primary bg-primary bg-opacity-10 fw-bold px-3 py-1"
                                            style="border-radius: 6px;">Editar</a>
                                        <form action="/admin/productos/{{ $producto->id }}" method="POST"
                                            onsubmit="return confirm('¿Estás seguro de desactivar este producto?');">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm text-danger bg-danger bg-opacity-10 fw-bold px-3 py-1"
                                                style="border-radius: 6px;">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">No hay productos que coincidan con la
                                    búsqueda.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Acordeón de Productos Inactivos --}}
    <div class="accordion shadow-sm rounded-4" id="accordionInactivos">
        <div class="accordion-item border-0 rounded-4">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed fw-bold text-muted bg-white rounded-4" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapseInactivos">
                    🚫 Ver Productos Inactivos (Eliminados)
                </button>
            </h2>
            <div id="collapseInactivos" class="accordion-collapse collapse" data-bs-parent="#accordionInactivos">
                <div class="accordion-body p-0 bg-light rounded-bottom-4">
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <table class="table align-middle text-muted text-center mb-0">
                            <thead class="bg-white sticky-top shadow-sm">
                                <tr>
                                    <th class="text-start ps-4">Producto</th>
                                    <th>Fecha Eliminación</th>
                                    <th class="text-end pe-4">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($productosInactivos ?? [] as $inactivo)
                                    <tr>
                                        <td class="text-start ps-4 text-dark"><strong>{{ $inactivo->name }}</strong></td>
                                        <td class="small">{{ $inactivo->deleted_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-end pe-4">
                                            <form action="/admin/productos/{{ $inactivo->id }}/restaurar" method="POST">
                                                @csrf @method('PUT')
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-success fw-bold px-3 py-1"
                                                    style="border-radius: 6px;">Restaurar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4">No hay productos inactivos.</td>
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
