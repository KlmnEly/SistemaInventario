@extends('template')

@section('title', 'Crear usuario')

@push('css')
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Crear Usuairo</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
            <li class="breadcrumb-item active">Crear Usuario</li>
        </ol>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="post">
                    @csrf

                    <div class="row mb-4 mt-4">
                        <label for="name" class="col-sm-2 col-form-label">Nombres:</label>
                        <div class="col-sm-4">
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name') }}">
                        </div>
                        <div class="col-sm-4">
                            <div class="form-text">
                                Escriba un solo nombre
                            </div>
                        </div>
                        <div class="col-sm-2">
                            @error('name')
                                <small class="text-danger">{{ '*' . $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label for="email" class="col-sm-2 col-form-label">Correo:</label>
                        <div class="col-sm-4">
                            <input type="text" name="email" id="email" class="form-control"
                                value="{{ old('email') }}">
                        </div>
                        <div class="col-sm-4">
                            <div class="form-text">
                                Direccion de correo eléctronico
                            </div>
                        </div>
                        <div class="col-sm-2">
                            @error('email')
                                <small class="text-danger">{{ '*' . $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label for="password" class="col-sm-2 col-form-label">Contraseña:</label>
                        <div class="col-sm-4">
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <div class="col-sm-4">
                            <div class="form-text">
                                Escriba una contraseña segura. Debe incluir números.
                            </div>
                        </div>
                        <div class="col-sm-2">
                            @error('password')
                                <small class="text-danger">{{ '*' . $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label for="password_confirm" class="col-sm-2 col-form-label">Confirmar contraseña:</label>
                        <div class="col-sm-4">
                            <input type="password" name="password_confirm" id="password_confirm" class="form-control">
                        </div>
                        <div class="col-sm-4">
                            <div class="form-text">
                                Vuelva a escribir su contraseña.
                            </div>
                        </div>
                        <div class="col-sm-2">
                            @error('password_confirm')
                                <small class="text-danger">{{ '*' . $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label for="role" class="col-sm-2 col-form-label">Seleccionar rol:</label>
                        <div class="col-sm-4">
                            <select name="role" id="role" class="form-select">
                                <option value="" selected disabled>Seleccione:</option>
                                @foreach($roles as $item)
                                <option value="{{ $item->name }}" @selected(old('role') == $item->name)>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-text">
                                Escoja un rol para el usuario.
                            </div>
                        </div>
                        <div class="col-sm-2">
                            @error('role')
                                <small class="text-danger">{{ '*' . $message }}</small>
                            @enderror
                        </div>
                    </div>


                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>

                </form>
            </div>
        </div>

    </div>
@endsection

@push('js')
@endpush
