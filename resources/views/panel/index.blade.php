@extends('template')

@yield('title', 'Panel')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="{{ asset('css/template.css') }}" rel="stylesheet" />

    @endpush

    @section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            let message = "{{ session('success') }}";

            Swal.fire({
                title: message,
                showClass: {
                    popup: `animate__animated animate__fadeInUp animate__faster`
                },
                hideClass: {
                    popup: `animate__animated animate__fadeOutDown animate__faster`
                }
            });
        </script>
    @endif


    <div class="container-fluid px-4">
        <h1 class="mt-4">Panel</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Panel</li>
        </ol>
        <div class="row">

            {{-- Productos --}}
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-computer"></i><span class="m-1">Productos</span>
                            </div>
                            <div class="col-4">
                                <?php
                                use App\Models\Producto;
                                $productos = count(Producto::all());
                                ?>
                                <p class="text-center fw-bold fs-4">{{$productos }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('productos.index') }}">Ver más</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            {{-- Historiales --}}
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-book"></i><span class="m-1">Historiales</span>
                            </div>
                            <div class="col-4">
                                <?php
                                use App\Models\Historial;
                                $historiales = count(Historial::all());
                                ?>
                                <p class="text-center fw-bold fs-4">{{$historiales }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('historiales.index') }}">Ver más</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            {{-- Categorias --}}
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-list"></i><span class="m-1">Categorias</span>
                            </div>
                            <div class="col-4">
                                <?php
                                use App\Models\Categoria;
                                $categorias = count(Categoria::all());
                                ?>
                                <p class="text-center fw-bold fs-4">{{$categorias }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('categorias.index') }}">Ver más</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            {{-- Ubicaciones --}}
            <div class="col-xl-3 col-md-6">
                <div class="card bg-danger text-white mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-location-dot"></i><span class="m-1">Ubicaciones</span>
                            </div>
                            <div class="col-4">
                                <?php
                                use App\Models\Ubicacione;
                                $ubicaciones = count(Ubicacione::all());
                                ?>
                                <p class="text-center fw-bold fs-4">{{$ubicaciones }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('ubicaciones.index') }}">Ver más</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            {{-- Tipos de evento --}}
            <div class="col-xl-3 col-md-6">
                <div class="card bg-danger text-white mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-calendar-days"></i><span class="m-1">Tipos de evento</span>
                            </div>
                            <div class="col-4">
                                <?php
                                use App\Models\TipoEvento;
                                $tiposEvento = count(TipoEvento::all());
                                ?>
                                <p class="text-center fw-bold fs-4">{{$tiposEvento }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('tiposEvento.index') }}">Ver más</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            {{-- Condiciones --}}
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-heart-pulse"></i><span class="m-1">Condiciones</span>
                            </div>
                            <div class="col-4">
                                <?php
                                use App\Models\Condicione;
                                $condiciones = count(Condicione::all());
                                ?>
                                <p class="text-center fw-bold fs-4">{{$condiciones }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('condiciones.index') }}">Ver más</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            {{-- Marcas --}}
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-bookmark"></i><span class="m-1">Marcas</span>
                            </div>
                            <div class="col-4">
                                <?php
                                use App\Models\Marca;
                                $marcas = count(Marca::all());
                                ?>
                                <p class="text-center fw-bold fs-4">{{$marcas }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('marcas.index') }}">Ver más</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            {{-- Perfil --}}
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <i class="fa-solid fa-user"></i><span class="m-1">Perfil</span>
                            </div>
                            <div class="col-4">
                                <p class="text-center fw-bold fs-4">1</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('profile.index') }}">Ver más</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('assets/demo/chart-bar-demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
