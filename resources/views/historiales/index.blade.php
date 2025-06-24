@extends('template')

@section('title', 'historiales')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/template.css') }}" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .form-filter {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: .25rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-filter .form-label {
            font-weight: bold;
        }
    </style>
@endpush

@section('content')

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
    </script>

    @if (session('success'))
        <script>
            Toast.fire({
                icon: "success",
                title: "{{ session('success') }}"
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Toast.fire({
                icon: "error",
                title: "{{ session('error') }}",
                timer: 4000
            });
        </script>
    @endif


    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Historial de Productos</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Historial</li>
        </ol>
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <a href="{{ route('historiales.create') }}">
                <button type="button" class="btn btn-primary">Añadir nuevo registro</button>
            </a>
        </div>

        {{-- Formulario de Filtros --}}
        <div class="card mb-4 form-filter">
            <div class="card-header">
                <i class="fas fa-filter me-1"></i>
                Filtros de Búsqueda
            </div>
            <div class="card-body">
                <form action="{{ route('historiales.index') }}" method="GET">
                    <div class="row g-3">
                        {{-- Filtro por Tipo de Evento --}}
                        <div class="col-md-4">
                            <label for="tipo_evento" class="form-label">Tipo de Evento:</label>
                            <select class="form-select" id="tipo_evento" name="tipo_evento">
                                <option value="">Seleccione un tipo de evento</option>
                                @foreach ($tiposEvento as $tipoEvento)
                                    <option value="{{ $tipoEvento->id }}"
                                        {{ request('tipo_evento') == $tipoEvento->id ? 'selected' : '' }}>
                                        {{ $tipoEvento->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filtro por Serial del Producto --}}
                        <div class="col-md-4">
                            <label for="serial_producto" class="form-label">Serial del Producto:</label>
                            <input type="text" class="form-control" id="serial_producto" name="serial_producto"
                                value="{{ request('serial_producto') }}" placeholder="Ingrese serial del producto">
                        </div>

                        {{-- Filtro por Categoría del Producto --}}
                        <div class="col-md-4">
                            <label for="categoria_producto" class="form-label">Categoría del Producto:</label>
                            <select class="form-select" id="categoria_producto" name="categoria_producto">
                                <option value="">Seleccione una categoría</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}"
                                        {{ request('categoria_producto') == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-success me-2">Aplicar Filtros</button>
                            <a href="{{ route('historiales.index') }}" class="btn btn-secondary">Limpiar Filtros</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{-- Fin Formulario de Filtros --}}


        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Tabla de Historial
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Tipo de Evento</th>
                            <th>Usuario</th>
                            <th>Fecha del Evento</th>
                            <th>Descripción</th>
                            <th>Ubicación Antigua</th>
                            <th>Ubicación Nueva</th>
                            <th>Condición Antigua</th>
                            <th>Condición Nueva</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($historiales as $historial)
                            <tr>
                                <td>{{ $historial->producto->nombre ?? 'N/A' }}</td>
                                <td>{{ $historial->tipoEvento->nombre ?? 'N/A' }}</td>
                                <td>{{ $historial->user->name ?? 'N/A' }}</td>
                                <td>{{ $historial->fecha_evento }}</td>
                                <td>{{ $historial->descripcion }}</td>
                                <td>{{ $historial->ubicacionAntigua->nombre ?? 'N/A' }}</td>
                                <td>{{ $historial->ubicacionNueva->nombre ?? 'N/A' }}</td>
                                <td>{{ $historial->condicionAntigua->nombre ?? 'N/A' }}</td>
                                <td>{{ $historial->condicionNueva->nombre ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('historiales.downloadPdf', $historial->id) }}"
                                        class="btn btn-primary btn-sm" target="_blank" title="Ver Historial en PDF">
                                        Detalles
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">No hay registros de historial disponibles con los
                                    filtros aplicados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('js')


    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush
