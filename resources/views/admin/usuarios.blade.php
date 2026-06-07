@extends('layouts.admin')

@section('title', 'Usuarios')

@section('content')
    {{-- Encabezado de la página --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Usuarios</h2>
            <p class="text-muted mb-0">El centro de control de TiendaUNNE.</p>
        </div>
    </div>

    {{-- Cartel de Éxito --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif


    {{-- Contenedor de las dos columnas --}}
    <div class="row">

        {{-- Columna Izquierda: Clientes Registrados --}}
        {{-- Columna Izquierda: Clientes Registrados --}}
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    {{-- Encabezado con título y contador --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Clientes registrados</h5>
                        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                            Cantidad: {{ $clientes->count() }}
                        </span>
                    </div>

                    {{-- NUEVO: Contenedor con Scroll --}}
                    <div style="max-height: 400px; overflow-y: auto; overflow-x: hidden; padding-right: 5px;">

                        @forelse ($clientes as $cliente)
                            <div class="d-flex justify-content-between align-items-center bg-light rounded-3 p-3 mb-3">
                                <div>
                                    <strong class="d-block text-dark">{{ $cliente->name }}
                                        {{ $cliente->last_name }}</strong>
                                    <small class="text-muted">{{ $cliente->email }}</small>
                                </div>
                                <span
                                    class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle px-3 py-2 rounded-pill">Cliente</span>
                            </div>
                        @empty
                            <p class="text-muted text-center py-3">No hay clientes registrados todavía.</p>
                        @endforelse

                    </div>
                    {{-- Fin del contenedor con Scroll --}}

                </div>
            </div>
        </div>

        {{-- Columna Derecha: Administradores --}}
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">

                    {{-- Encabezado con botón que abre el modal --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Administradores</h5>
                        <button type="button" class="btn btn-primary fw-bold px-3" style="border-radius: 8px;"
                            data-bs-toggle="modal" data-bs-target="#modalNuevoAdmin">
                            Nuevo admin
                        </button>
                    </div>

                    @forelse ($admins as $admin)
                        <div class="d-flex justify-content-between align-items-center bg-light rounded-3 p-3 mb-3">
                            <div>
                                <strong class="d-block text-dark">{{ $admin->name }} {{ $admin->last_name }}</strong>
                                <small class="text-muted">{{ $admin->email }}</small>
                            </div>
                            <div>
                                {{-- Botón Editar que abre SU propio Modal --}}
                                <button type="button"
                                    class="btn btn-sm text-primary bg-primary bg-opacity-10 fw-bold me-2 px-3"
                                    style="border-radius: 6px;" data-bs-toggle="modal"
                                    data-bs-target="#modalEditarAdmin{{ $admin->id }}">
                                    Editar
                                </button>

                                {{-- Formulario para Eliminar --}}
                                <form action="/admin/usuarios/{{ $admin->id }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('¿Estás seguro de eliminar a este administrador?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-sm text-danger bg-danger bg-opacity-10 fw-bold px-3"
                                        style="border-radius: 6px;">Eliminar</button>
                                </form>
                            </div>
                        </div>

                        {{-- Modal EXCLUSIVO para Editar este Administrador --}}
                        <div class="modal fade" id="modalEditarAdmin{{ $admin->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Editar Administrador</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Cerrar"></button>
                                    </div>

                                    <form action="/admin/usuarios/{{ $admin->id }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="modal-body">
                                            {{-- Mostrar errores si falló la validación al editar --}}
                                            @if ($errors->any())
                                                <div class="alert alert-danger shadow-sm rounded-3 mb-3">
                                                    <ul class="mb-0">
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label text-muted fw-bold">Nombre *</label>
                                                    <input type="text" name="name" class="form-control" required
                                                        value="{{ old('name', $admin->name) }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label text-muted fw-bold">Apellido *</label>
                                                    <input type="text" name="last_name" class="form-control" required
                                                        value="{{ old('last_name', $admin->last_name) }}">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label text-muted fw-bold">Correo Electrónico *</label>
                                                <input type="email" name="email" class="form-control" required
                                                    value="{{ old('email', $admin->email) }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label text-muted fw-bold">Nueva Contraseña</label>
                                                <input type="password" name="password" class="form-control" minlength="8">
                                                <small class="text-muted">Dejar en blanco si NO deseas cambiar la contraseña
                                                    actual.</small>
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
                    @empty
                        <p class="text-muted text-center py-3">No hay administradores registrados.</p>
                    @endforelse

                </div>
            </div>
        </div>

    </div>

    {{-- Modal para crear Nuevo Administrador --}}
    <div class="modal fade" id="modalNuevoAdmin" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Crear Nuevo Administrador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <form action="/admin/usuarios" method="POST">
                    @csrf
                    {{-- Forzamos a que el rol sea admin de forma oculta --}}
                    <input type="hidden" name="role" value="admin">

                    <div class="modal-body">
                        {{-- Cartel para mostrar errores de validación (ej: contraseña débil) --}}
                        @if ($errors->any())
                            <div class="alert alert-danger shadow-sm rounded-3 mb-3">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted fw-bold">Nombre *</label>
                                <input type="text" name="name" class="form-control" required
                                    value="{{ old('name') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted fw-bold">Apellido *</label>
                                <input type="text" name="last_name" class="form-control" required
                                    value="{{ old('last_name') }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold">Correo Electrónico *</label>
                            <input type="email" name="email" class="form-control" required
                                value="{{ old('email') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold">Contraseña *</label>
                            <input type="password" name="password" class="form-control" required minlength="8">
                            <small class="text-muted">Mínimo 8 caracteres, incluyendo letras, al menos una mayúscula y una
                                minúscula.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary fw-bold"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary fw-bold" style="background-color: #0d6efd;">Guardar
                            Administrador</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script para reabrir el modal automáticamente si hay errores de validación en la creación --}}
    @if ($errors->any() && old('role') == 'admin')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var modalAdmin = new bootstrap.Modal(document.getElementById('modalNuevoAdmin'));
                modalAdmin.show();
            });
        </script>
    @endif

@endsection
