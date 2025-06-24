@extends('template')

@section('title', 'Productos')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/template.css') }}" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')

    @if (session('success'))
        <script>
            let message = "{{ session('success') }}"
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "success",
                title: message
            });
        </script>
    @endif


    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Productos</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="">Inicio</a></li>
            <li class="breadcrumb-item active">Productos</li>
        </ol>
        <div class="mb-4">
            <a href="{{ route('productos.create') }}">
                <button type="button" class="btn btn-primary">Añadir nuevo producto</button>
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Tabla productos
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Codigo</th>
                            <th>Serial</th>
                            <th>Marca</th>
                            <th>Categoria</th>
                            <th>Ubicacion</th>
                            <th>Estado</th>
                            <th>Acciones</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productos as $producto)
                            <tr>
                                <td>{{ $producto->nombre }}</td>
                                <td>{{ $producto->codigo }}</td>
                                <td>{{ $producto->serial }}</td>
                                <td>{{ $producto->marca->nombre }}</td>

                                <td>{{ $producto->categoria->nombre }}</td>
                                <td>{{ $producto->ubicacion->nombre }}</td>
                                <td class="text-left">
                                    @if ($producto->estado == 1)
                                        <span class="fw-bolder rounded bg-success text-white p-1">Activo</span>
                                    @else
                                        <span class="fw-bolder rounded bg-danger text-white p-1">Eliminado</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-around align-items-center" role="group"
                                        aria-label="Basic mixed styles example">
                                        @can('editar-producto')
                                            <form action="{{ route('productos.edit', ['producto' => $producto->id]) }}"
                                                method="get">
                                                <button type="submit" class="btn btn-warning">Editar</button>
                                            </form>
                                        @endcan

                                        @can('mostrar-producto')
                                            <button type="submit" class="btn btn-success" data-bs-toggle="modal"
                                                data-bs-target="#verModal-{{ $producto->id }}">Ver</button>
                                        @endcan

                                        @can('eliminar-producto')
                                            @if ($producto->estado == 1)
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#confirmModal-{{ $producto->id }}">Eliminar</button>
                                            @else
                                                <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                                                    data-bs-target="#confirmModal-{{ $producto->id }}">Restaurar</button>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="verModal-{{ $producto->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Detalles de Producto</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <label for=""><span class="fw-bolder">Nombre:
                                                    </span>{{ $producto->nombre }}</label>
                                                <label for=""><span class="fw-bolder">Serial:
                                                    </span>{{ $producto->serial }}</label>
                                                <label for=""><span class="fw-bolder">Marca:
                                                    </span>{{ $producto->marca->nombre }}</label>
                                                <label for=""><span class="fw-bolder">Categoria:
                                                    </span>{{ $producto->categoria->nombre }}</label>
                                                <label for=""><span class="fw-bolder">Ubicacion:
                                                    </span>{{ $producto->ubicacion->nombre }}</label>
                                                <label for=""><span class="fw-bolder">Condicion:
                                                    </span>{{ $producto->condicion->nombre }}</label>
                                                <div class="row">
                                                    <p class="fw-bolder text-center">Atributos</p>
                                                    @if ($producto->atributos->isEmpty())
                                                        <span
                                                            class="mx-1 rounded-pill p-1 bg-secondary text-white text-center">Sin
                                                            atributos</span>
                                                    @else
                                                        {!! $producto->atributos->map(function ($atributo) {
                                                                return '<span class="mx-1 mb-2 rounded-pill p-1 bg-secondary text-white text-center">' .
                                                                    $atributo->nombre .
                                                                    ': ' .
                                                                    $atributo->valor .
                                                                    '</span>';
                                                            })->implode(' ') !!}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="confirmModal-{{ $producto->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de confirmacion</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{ $producto->estado == 1 ? '¿Seguro quieres eliminar este producto?' : '¿Seguro que quieres restaurar el producto?' }}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cerrar</button>
                                            <form action="{{ route('productos.destroy', ['producto' => $producto->id]) }}"
                                                method="post">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Confirmar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
