@extends('layouts.admin')

@section('title', 'Consultas')

@section('content')
    {{-- Encabezado de la página --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Consultas</h2>
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

    {{-- Errores --}}
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm rounded-3 mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Filtro de Estado Superior --}}
    <form action="/admin/consultas" method="GET" class="mb-4 d-flex gap-3 align-items-center">
        <select name="estado" class="form-select border-0 shadow-sm" style="max-width: 250px; border-radius: 8px;"
            onchange="this.form.submit()">
            <option value="">Todos los estados</option>
            <option value="No leído" {{ request('estado') == 'No leído' ? 'selected' : '' }}>No leído</option>
            <option value="Resuelto" {{ request('estado') == 'Resuelto' ? 'selected' : '' }}>Resuelto</option>
        </select>

        @if (request('estado'))
            <a href="/admin/consultas" class="btn btn-outline-danger fw-bold" style="border-radius: 8px;">Limpiar filtro</a>
        @endif
    </form>

    {{-- Tarjeta Principal con Pestañas --}}
    <div class="card border-0 shadow-sm rounded-4">

        {{-- Cabecera de la Tarjeta con Tabs --}}
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4 rounded-top-4">
            <ul class="nav nav-tabs border-bottom" id="consultasTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold text-dark border-0 border-bottom border-3 border-primary"
                        id="registrados-tab" data-bs-toggle="tab" data-bs-target="#registrados-tab-pane" type="button"
                        role="tab" aria-controls="registrados-tab-pane" aria-selected="true" onclick="activarTab(this)">
                        Clientes Registrados
                        <span class="badge bg-primary rounded-pill ms-2">{{ $consultasRegistrados->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-muted border-0" id="invitados-tab" data-bs-toggle="tab"
                        data-bs-target="#invitados-tab-pane" type="button" role="tab"
                        aria-controls="invitados-tab-pane" aria-selected="false" onclick="activarTab(this)">
                        Visitantes Anónimos
                        <span class="badge bg-secondary rounded-pill ms-2">{{ $consultasInvitados->count() }}</span>
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-4 pt-3">
            <div class="tab-content" id="consultasTabContent">

                {{-- PESTAÑA 1: REGISTRADOS --}}
                <div class="tab-pane fade show active" id="registrados-tab-pane" role="tabpanel"
                    aria-labelledby="registrados-tab" tabindex="0">
                    <div style="max-height: 500px; overflow-y: auto; overflow-x: hidden; padding-right: 5px;">
                        @forelse ($consultasRegistrados as $consulta)
                            @include('partials.admin_consulta_card', [
                                'consulta' => $consulta,
                                'esRegistrado' => true,
                            ])
                        @empty
                            <div class="text-center py-5 bg-light rounded-3">
                                <p class="text-muted mb-0">No hay consultas de clientes registrados.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- PESTAÑA 2: INVITADOS --}}
                <div class="tab-pane fade" id="invitados-tab-pane" role="tabpanel" aria-labelledby="invitados-tab"
                    tabindex="0">
                    <div style="max-height: 500px; overflow-y: auto; overflow-x: hidden; padding-right: 5px;">
                        @forelse ($consultasInvitados as $consulta)
                            @include('partials.admin_consulta_card', [
                                'consulta' => $consulta,
                                'esRegistrado' => false,
                            ])
                        @empty
                            <div class="text-center py-5 bg-light rounded-3">
                                <p class="text-muted mb-0">No hay consultas de visitantes anónimos.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Script para estilo de las pestañas y copiar email --}}
    <script>
        function activarTab(elemento) {
            // Reiniciar estilos de todos los botones
            let botones = document.querySelectorAll('#consultasTab .nav-link');
            botones.forEach(btn => {
                btn.classList.remove('border-bottom', 'border-3', 'border-primary', 'text-dark');
                btn.classList.add('text-muted');
            });
            // Activar el clickeado
            elemento.classList.remove('text-muted');
            elemento.classList.add('border-bottom', 'border-3', 'border-primary', 'text-dark');
        }

        function copiarEmail(email) {
            if (!email) {
                alert('No hay un correo electrónico registrado para esta consulta.');
                return;
            }
            var elementoTemporal = document.createElement("textarea");
            elementoTemporal.value = email;
            elementoTemporal.style.position = "fixed";
            elementoTemporal.style.top = "0";
            elementoTemporal.style.left = "0";
            elementoTemporal.style.opacity = "0";
            document.body.appendChild(elementoTemporal);
            elementoTemporal.select();
            elementoTemporal.setSelectionRange(0, 99999);
            try {
                var copiadoExitoso = document.execCommand("copy");
                if (copiadoExitoso) {
                    alert('¡Correo copiado al portapapeles: ' + email + '!');
                } else {
                    alert('No se pudo copiar automáticamente. Correo: ' + email);
                }
            } catch (err) {
                alert('Error al copiar. Correo: ' + email);
            }
            document.body.removeChild(elementoTemporal);
        }
    </script>
@endsection
