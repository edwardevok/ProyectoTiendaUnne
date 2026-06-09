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

    {{-- Contenedor de las dos columnas: align-items-start evita que se estiren igual --}}
    <div class="row align-items-start">

        {{-- COLUMNA IZQUIERDA: CLIENTES --}}
        <div class="col-lg-6 mb-4">
            {{-- Tarjeta Clientes Activos (SIN h-100) --}}
            <div class="card border-0 shadow-sm rounded-4 mb-3 bg-white">
                <div class="card-body p-4 bg-light rounded-4">

                    {{-- Encabezado con Filtro Dinámico --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <h5 class="fw-bold mb-0">Clientes activos</h5>
                            <span class="badge bg-white text-dark border px-3 py-2 rounded-pill shadow-sm">
                                Cantidad: {{ $clientes->count() }}
                            </span>
                        </div>

                        {{-- Formulario de Filtro --}}
                        <form action="/admin/usuarios" method="GET" class="m-0">
                            <select name="sort"
                                class="form-select form-select-sm border-0 shadow-sm text-primary fw-bold rounded-pill px-3"
                                onchange="this.form.submit()" style="cursor: pointer;">
                                <option value="recientes" {{ request('sort') == 'recientes' ? 'selected' : '' }}>Más
                                    recientes</option>
                                <option value="compras_desc" {{ request('sort') == 'compras_desc' ? 'selected' : '' }}>Más
                                    compras ⬇</option>
                                <option value="compras_asc" {{ request('sort') == 'compras_asc' ? 'selected' : '' }}>Menos
                                    compras ⬆</option>
                            </select>
                        </form>
                    </div>

                    {{-- Lista de Clientes --}}
                    <div style="max-height: 480px; overflow-y: auto; overflow-x: hidden; padding-right: 5px;">
                        @forelse ($clientes as $cliente)
                            <div
                                class="d-flex justify-content-between align-items-center bg-white rounded-3 p-3 mb-3 shadow-sm border border-light">
                                <div>
                                    <strong class="d-block text-dark">{{ $cliente->name }}
                                        {{ $cliente->last_name }}</strong>
                                    <small class="text-muted d-block">{{ $cliente->email }}</small>
                                    <small class="text-primary fw-bold mt-1 d-block">🛒 Compras realizadas:
                                        {{ $cliente->orders_count }}</small>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span
                                        class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle px-3 py-2 rounded-pill d-none d-sm-block">Cliente</span>

                                    {{-- Botón Suspender --}}
                                    <form action="/admin/usuarios/{{ $cliente->id }}" method="POST"
                                        onsubmit="return confirm('¿Estás seguro de suspender a este cliente de la plataforma?');">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-sm text-danger bg-danger bg-opacity-10 fw-bold px-3 py-1"
                                            style="border-radius: 6px;">Suspender</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="bg-white rounded-3 p-4 text-center shadow-sm border border-light">
                                <p class="text-muted mb-0">No hay clientes activos en la plataforma.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Desplegable Clientes Inactivos --}}
            <div class="accordion shadow-sm rounded-4" id="accClientesInactivos">
                <div class="accordion-item border-0 rounded-4">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold text-muted bg-white rounded-4" type="button"
                            data-bs-toggle="collapse" data-bs-target="#colClientesInactivos">
                            🚫 Clientes Inactivos (Suspendidos)
                        </button>
                    </h2>
                    <div id="colClientesInactivos" class="accordion-collapse collapse"
                        data-bs-parent="#accClientesInactivos">
                        <div class="accordion-body p-3 bg-light rounded-bottom-4">

                            {{-- ACÁ ESTÁ EL NUEVO CONTENEDOR CON SCROLL PARA LOS INACTIVOS --}}
                            <div style="max-height: 250px; overflow-y: auto; overflow-x: hidden; padding-right: 5px;">
                                @forelse ($clientesInactivos ?? [] as $clienteDel)
                                    <div
                                        class="d-flex justify-content-between align-items-center bg-white rounded-3 p-2 mb-2 shadow-sm border border-light">
                                        <div>
                                            <strong class="d-block text-muted small">{{ $clienteDel->name }}
                                                {{ $clienteDel->last_name }}</strong>
                                            <small class="text-muted"
                                                style="font-size: 0.75rem;">{{ $clienteDel->email }}</small>
                                        </div>
                                        <form action="/admin/usuarios/{{ $clienteDel->id }}/restaurar" method="POST">
                                            @csrf @method('PUT')
                                            <button class="btn btn-sm btn-outline-success fw-bold py-1 px-2"
                                                style="font-size: 0.75rem;">Reactivar</button>
                                        </form>
                                    </div>
                                @empty
                                    <small class="text-muted">No hay clientes suspendidos.</small>
                                @endforelse
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA: ADMINISTRADORES --}}
        <div class="col-lg-6 mb-4">
            {{-- Tarjeta Admins Activos (SIN h-100) --}}
            <div class="card border-0 shadow-sm rounded-4 mb-3 bg-white">
                <div class="card-body p-4 bg-light rounded-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Administradores activos</h5>
                        <button type="button" class="btn btn-primary fw-bold px-3 shadow-sm"
                            style="border-radius: 8px; background-color: #0d6efd;" data-bs-toggle="modal"
                            data-bs-target="#modalNuevoAdmin">
                            Nuevo administrador
                        </button>
                    </div>

                    <div style="max-height: 480px; overflow-y: auto; overflow-x: hidden; padding-right: 5px;">
                        @forelse ($admins as $admin)
                            <div
                                class="d-flex justify-content-between align-items-center bg-white rounded-3 p-3 mb-3 shadow-sm border border-light">
                                <div>
                                    <strong class="d-block text-dark">{{ $admin->name }} {{ $admin->last_name }}</strong>
                                    <small class="text-muted">{{ $admin->email }}</small>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button"
                                        class="btn btn-sm text-primary bg-primary bg-opacity-10 fw-bold px-3 py-1"
                                        style="border-radius: 6px;" data-bs-toggle="modal"
                                        data-bs-target="#modalEditarAdmin{{ $admin->id }}">
                                        Editar
                                    </button>
                                    <form action="/admin/usuarios/{{ $admin->id }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('¿Desactivar este administrador?');">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-sm text-danger bg-danger bg-opacity-10 fw-bold px-3 py-1"
                                            style="border-radius: 6px;">Eliminar</button>
                                    </form>
                                </div>
                            </div>

                            {{-- MODAL DE EDICIÓN DE ADMIN --}}
                            <div class="modal fade" id="modalEditarAdmin{{ $admin->id }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content border-0 shadow rounded-4">
                                        <div class="modal-header border-bottom-0">
                                            <h5 class="modal-title fw-bold">Editar Administrador</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Cerrar"></button>
                                        </div>
                                        <form action="/admin/usuarios/{{ $admin->id }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
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
                                                        <input type="text" name="name"
                                                            class="form-control bg-light border-0" required
                                                            value="{{ old('name', $admin->name) }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label text-muted fw-bold">Apellido *</label>
                                                        <input type="text" name="last_name"
                                                            class="form-control bg-light border-0" required
                                                            value="{{ old('last_name', $admin->last_name) }}">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label text-muted fw-bold">Correo Electrónico
                                                        *</label>
                                                    <input type="email" name="email"
                                                        class="form-control bg-light border-0" required
                                                        value="{{ old('email', $admin->email) }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label text-muted fw-bold">Nueva Contraseña</label>
                                                    <input type="password" name="password"
                                                        class="form-control bg-light border-0" minlength="8">
                                                    <small class="text-muted">Dejar en blanco si NO deseas cambiar la
                                                        contraseña actual.</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top-0">
                                                <button type="button" class="btn btn-light fw-bold"
                                                    data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary fw-bold px-4"
                                                    style="background-color: #0d6efd; border-radius: 8px;">Actualizar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            {{-- FIN DEL MODAL DE EDICIÓN --}}

                        @empty
                            <div class="bg-white rounded-3 p-4 text-center shadow-sm border border-light">
                                <p class="text-muted mb-0">No hay administradores activos.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Desplegable Admins Inactivos --}}
            <div class="accordion shadow-sm rounded-4" id="accAdminsInactivos">
                <div class="accordion-item border-0 rounded-4">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold text-muted bg-white rounded-4" type="button"
                            data-bs-toggle="collapse" data-bs-target="#colAdminsInactivos">
                            🚫 Administradores Inactivos
                        </button>
                    </h2>
                    <div id="colAdminsInactivos" class="accordion-collapse collapse"
                        data-bs-parent="#accAdminsInactivos">
                        <div class="accordion-body p-3 bg-light rounded-bottom-4">

                            {{-- ACÁ ESTÁ EL NUEVO CONTENEDOR CON SCROLL PARA LOS ADMINS INACTIVOS --}}
                            <div style="max-height: 250px; overflow-y: auto; overflow-x: hidden; padding-right: 5px;">
                                @forelse ($adminsInactivos ?? [] as $adminDel)
                                    <div
                                        class="d-flex justify-content-between align-items-center bg-white rounded-3 p-2 mb-2 shadow-sm border border-light">
                                        <div>
                                            <strong class="d-block text-muted small">{{ $adminDel->name }}
                                                {{ $adminDel->last_name }}</strong>
                                            <small class="text-muted"
                                                style="font-size: 0.75rem;">{{ $adminDel->email }}</small>
                                        </div>
                                        <form action="/admin/usuarios/{{ $adminDel->id }}/restaurar" method="POST">
                                            @csrf @method('PUT')
                                            <button class="btn btn-sm btn-outline-success fw-bold py-1 px-2"
                                                style="font-size: 0.75rem;">Restaurar</button>
                                        </form>
                                    </div>
                                @empty
                                    <small class="text-muted">No hay administradores inactivos.</small>
                                @endforelse
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Modal para crear Nuevo Administrador --}}
    <div class="modal fade" id="modalNuevoAdmin" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold">Crear Nuevo Administrador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form action="/admin/usuarios" method="POST">
                    @csrf
                    <input type="hidden" name="role" value="admin">
                    <div class="modal-body">
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
                                <input type="text" name="name" class="form-control bg-light border-0" required
                                    value="{{ old('name') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted fw-bold">Apellido *</label>
                                <input type="text" name="last_name" class="form-control bg-light border-0" required
                                    value="{{ old('last_name') }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold">Correo Electrónico *</label>
                            <input type="email" name="email" class="form-control bg-light border-0" required
                                value="{{ old('email') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold">Contraseña *</label>
                            <input type="password" name="password" class="form-control bg-light border-0" required
                                minlength="8">
                            <small class="text-muted">Mínimo 8 caracteres, incluyendo letras, al menos una mayúscula y una
                                minúscula.</small>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary fw-bold px-4"
                            style="background-color: #0d6efd; border-radius: 8px;">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script para reabrir el modal automáticamente si hay errores de validación --}}
    @if ($errors->any() && old('role') == 'admin')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var modalAdmin = new bootstrap.Modal(document.getElementById('modalNuevoAdmin'));
                modalAdmin.show();
            });
        </script>
    @endif
@endsection
