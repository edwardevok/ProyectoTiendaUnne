@extends('layouts.admin')

@section('title', 'Categorías')

@section('content')
    {{-- Encabezado de la página --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Categorías</h2>
            <p class="text-muted mb-0">El centro de control de TiendaUNNE.</p>
        </div>
    </div>

    {{-- NUEVO: Mensajes de Alerta (Éxito o Error al eliminar) --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <strong>¡Acción denegada!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    {{-- Tarjeta Principal --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">

            {{-- Encabezado de la tarjeta y botón --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">CRUD de categorías</h5>
                <button type="button" class="btn btn-primary fw-bold px-4"
                    style="border-radius: 8px; background-color: #0d6efd;" data-bs-toggle="modal"
                    data-bs-target="#modalNuevaCategoria">
                    Nueva categoría
                </button>
            </div>

            <div class="row">
                @forelse ($categorias as $categoria)
                    <div class="col-md-4 mb-3">
                        <div class="card border shadow-sm rounded-4 h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <strong class="text-dark fs-5">{{ $categoria->name }}</strong>
                                    {{-- NUEVO: El contador dinámico de productos --}}
                                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                                        {{ $categoria->products->count() }} productos
                                    </span>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn w-50 text-primary bg-primary bg-opacity-10 fw-bold"
                                        style="border-radius: 8px;" data-bs-toggle="modal"
                                        data-bs-target="#modalEditarCategoria{{ $categoria->id }}">
                                        Editar
                                    </button>

                                    {{-- Formulario para eliminar --}}
                                    <form action="/admin/categorias/{{ $categoria->id }}" method="POST" class="w-50"
                                        onsubmit="return confirm('¿Seguro?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn w-100 text-danger bg-danger bg-opacity-10 fw-bold"
                                            style="border-radius: 8px;">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ¡AQUÍ ADENTRO DEL BUCLE VA EL MODAL DE EDITAR! --}}
                    {{-- Ventana Emergente (Modal) EXCLUSIVA para EDITAR esta categoría --}}
                    <div class="modal fade" id="modalEditarCategoria{{ $categoria->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold">Editar Categoría</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Cerrar"></button>
                                </div>

                                <form action="/admin/categorias/{{ $categoria->id }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="modal-body text-start">
                                        <div class="mb-3">
                                            <label class="form-label text-muted fw-bold">Nombre de la Categoría *</label>
                                            <input type="text" name="name" class="form-control" required
                                                value="{{ $categoria->name }}">
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary fw-bold"
                                            data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary fw-bold"
                                            style="background-color: #0d6efd;">Actualizar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- Fin del modal de Editar --}}

                @empty
                    <div class="col-12 text-center py-4">
                        <p class="text-muted">No hay categorías registradas. ¡Crea la primera!</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    {{-- Ventana Emergente (Modal) para Nueva Categoría (AFUERA DEL BUCLE) --}}
    <div class="modal fade" id="modalNuevaCategoria" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Crear Nueva Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <form action="/admin/categorias" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold">Nombre de la Categoría *</label>
                            <input type="text" name="name" class="form-control" required
                                placeholder="Ej: Indumentaria">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary fw-bold" style="background-color: #0d6efd;">Guardar
                            Categoría</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
