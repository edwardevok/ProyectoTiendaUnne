@extends('layouts.admin')

@section('title', 'Crear Producto')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Nuevo Producto</h2>
            <p class="text-muted mb-0">El centro de control de TiendaUNNE.</p>
        </div>
        <a href="/admin/productos" class="btn btn-outline-secondary fw-bold">Volver</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">

            <form action="{{ isset($producto) ? '/admin/productos/' . $producto->id : '/admin/productos' }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                {{-- Si estamos editando, le decimos a Laravel que use el método PUT --}}
                @if (isset($producto))
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted fw-bold">Nombre del Producto *</label>
                        <input type="text" name="name" class="form-control" required
                            value="{{ $producto->name ?? '' }}" placeholder="Ej: Notebook Lenovo">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted fw-bold">Categoría *</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Seleccione una categoría...</option>

                            {{-- Aquí recorremos las categorías que vienen de la base de datos --}}
                            @foreach ($categorias as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ isset($producto) && $producto->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted fw-bold">Precio ($) *</label>
                        <input type="number" name="price" class="form-control" step="0.01" required
                            value="{{ $producto->price ?? '' }}" placeholder="Ej: 1200000">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted fw-bold">Stock Inicial *</label>
                        <input type="number" name="stock" class="form-control" required
                            value="{{ $producto->stock ?? '' }}" placeholder="Ej: 15">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label text-muted fw-bold">Imagen del Producto</label>
                        @if (isset($producto) && $producto->image)
                            <div class="mb-2">
                                <img src="{{ asset('img/' . $producto->image) }}" width="80" class="rounded">
                            </div>
                        @endif
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>

                    <div class="col-12 mb-4">
                        <label class="form-label text-muted fw-bold">Descripción</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Detalles del producto...">{{ $producto->description ?? '' }}</textarea>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary fw-bold px-4" style="background-color: #0d6efd;">
                        {{ isset($producto) ? 'Actualizar Producto' : 'Guardar Producto' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
