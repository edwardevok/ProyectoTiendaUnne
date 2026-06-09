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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex gap-3">
                    {{-- FORMULARIO DE FILTROS --}}
                    <form action="/admin/productos" method="GET" class="d-flex align-items-center gap-3 mb-4">
                        <div class="input-group" style="max-width: 350px;">
                            <input type="text" name="search" class="form-control" placeholder="Buscar producto..."
                                value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit"
                                style="background-color: #0d6efd;">Buscar</button>
                        </div>

                        <select name="category_id" class="form-select" style="max-width: 250px;"
                            onchange="this.form.submit()">
                            <option value="">Todas las categorías</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}"
                                    {{ request('category_id') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->name }}
                                </option>
                            @endforeach
                        </select>

                        @if (request('search') || request('category_id'))
                            <a href="/admin/productos" class="btn btn-outline-danger fw-bold">Limpiar</a>
                        @endif
                    </form>
                </div>
                <a href="/admin/productos/crear" class="btn fw-bold text-white px-4"
                    style="background-color: #198754; border-radius: 8px;">Crear producto</a>
            </div>

            {{-- Tabla de Productos Activos --}}
            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <thead class="border-bottom">
                        <tr>
                            <th class="text-muted small pb-3">Imagen</th>
                            <th class="text-muted small pb-3">Producto</th>
                            <th class="text-muted small pb-3">Categoría</th>
                            <th class="text-muted small pb-3">Stock</th>
                            <th class="text-muted small pb-3">Precio</th>
                            <th class="text-muted small pb-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($productos as $producto)
                            <tr class="border-bottom">
                                <td class="py-3">
                                    @if ($producto->image)
                                        <img src="{{ asset('img/' . $producto->image) }}" alt="{{ $producto->name }}"
                                            style="width: 50px; height: 50px; object-fit: cover;"
                                            class="rounded-3 shadow-sm">
                                    @else
                                        <div class="bg-secondary bg-opacity-25 rounded-3 d-flex justify-content-center align-items-center text-secondary fw-bold fs-5"
                                            style="width: 50px; height: 50px;">
                                            {{ substr($producto->name, 0, 1) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 fw-bold">{{ $producto->name }}</td>
                                <td class="py-3">{{ $producto->category ? $producto->category->name : 'Sin categoría' }}
                                </td>
                                <td class="py-3">{{ $producto->stock }}</td>
                                <td class="py-3 fw-bold">$ {{ number_format($producto->price, 0, ',', '.') }}</td>
                                <td class="py-3">
                                    <div class="d-flex">
                                        <a href="/admin/productos/{{ $producto->id }}/editar"
                                            class="btn btn-sm text-primary bg-primary bg-opacity-10 fw-bold me-2 px-3"
                                            style="border-radius: 6px;">Editar</a>
                                        <form action="/admin/productos/{{ $producto->id }}" method="POST"
                                            onsubmit="return confirm('¿Estás seguro de desactivar este producto?');">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm text-danger bg-danger bg-opacity-10 fw-bold px-3"
                                                style="border-radius: 6px;">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No hay productos activos.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- NUEVO: Acordeón de Productos Inactivos --}}
    <div class="accordion shadow-sm rounded-4" id="accordionInactivos">
        <div class="accordion-item border-0 rounded-4">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed fw-bold text-muted bg-light rounded-4" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapseInactivos">
                    🚫 Ver Productos Inactivos (Eliminados)
                </button>
            </h2>
            <div id="collapseInactivos" class="accordion-collapse collapse" data-bs-parent="#accordionInactivos">
                <div class="accordion-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle text-muted">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Fecha Eliminación</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($productosInactivos ?? [] as $inactivo)
                                    <tr>
                                        <td><strong>{{ $inactivo->name }}</strong></td>
                                        <td>{{ $inactivo->deleted_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <form action="/admin/productos/{{ $inactivo->id }}/restaurar" method="POST">
                                                @csrf @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-success fw-bold px-3"
                                                    style="border-radius: 6px;">Restaurar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-3">No hay productos inactivos.</td>
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
