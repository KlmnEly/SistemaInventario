@extends('template')

@section('title', 'categorias')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/template.css') }}" rel="stylesheet" />
@endpush


@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Panel de Administración</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Administración</li>
        </ol>
       
        <div class="card mb-4">
            <div class="card-body d-flex justify-content-around" style="gap: 5px;">
                
                
                <div class="card" onclick="window.location.href='/categorias'" style="width: 20%;">
                    <div class="card-body d-flex justify-content-center align-content-center" >
                        <p>Categorias</p>
                    </div>
                </div>
                <div class="card"  onclick="window.location.href='/marcas'" style="width: 20%;">
                    <div class="card-body d-flex justify-content-center align-content-center" >
                        <p>Marcas</p>
                    </div>
                </div>
                <div class="card" style="width: 20%;">
                    <div class="card-body d-flex justify-content-center align-content-center" >
                        <p>Ubicaciones</p>
                    </div>
                </div>
                <div class="card" style="width: 20%;">
                    <div class="card-body d-flex justify-content-center align-content-center" >
                        <p>Estados</p>
                    </div>
                </div>
                <div class="card" onclick="window.location.href='/tiposEvento'" style="width: 20%;">
                    <div class="card-body d-flex justify-content-center align-content-center" >
                        <p>Tipo de evento</p>
                    </div>
                </div>
                
                
            </div>
        </div>
    </div>
@endsection

