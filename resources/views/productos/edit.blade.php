@extends('template')

@section('title', 'Editar Producto')

@push('css')
    <!-- CDN de Bootstrap-select CSS (cargado solo para esta vista) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <style>
        /* Estilos opcionales para los atributos */
        .attribute-row {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            align-items: flex-end; /* Alinea los elementos en la parte inferior */
            flex-wrap: wrap; /* Permite que los elementos se envuelvan en pantallas pequeñas */
        }
        .attribute-row .form-group {
            flex: 1; /* Permite que los campos de formulario ocupen el espacio disponible */
            min-width: 150px; /* Ancho mínimo para evitar que se pongan demasiado pequeños */
        }
        .attribute-row .btn-danger {
            flex-shrink: 0; /* Evita que el botón se encoja */
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Editar Producto</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a></li>
            <li class="breadcrumb-item active">Editar Producto</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            {{-- Formulario para actualizar el producto --}}
            <form action="{{ route('productos.update', ['producto' => $producto]) }}" method="post">
                @method('PATCH') {{-- Necesario para Laravel para simular el método PUT/PATCH --}}
                @csrf

                <!-- Datos Principales del Producto -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $producto->nombre) }}">
                        @error('nombre')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="codigo" class="form-label">Código:</label>
                        <input type="text" name="codigo" id="codigo" class="form-control" value="{{ old('codigo', $producto->codigo) }}">
                        @error('codigo')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="serial" class="form-label">Número de Serie (Opcional):</label>
                        <input type="text" name="serial" id="serial" class="form-control" value="{{ old('serial', $producto->serial) }}">
                        @error('serial')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="estado" class="form-label">Estado:</label>
                        <select name="estado" id="estado" class="form-control selectpicker show-tick" title="Selecciona un estado">
                            <option value="1" {{ old('estado', $producto->estado) == '1' ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('estado', $producto->estado) == '0' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('estado')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- Selectores de Relaciones -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="marca_id" class="form-label">Marca:</label>
                        <select name="marca_id" id="marca_id" class="form-control selectpicker show-tick" data-live-search="true" title="Escoge una marca">
                            @foreach ($marcas as $marca)
                                <option value="{{ $marca->id }}" {{ old('marca_id', $producto->marca_id) == $marca->id ? 'selected' : '' }}>
                                    {{ $marca->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('marca_id')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="categoria_id" class="form-label">Categoría:</label>
                        <select name="categoria_id" id="categoria_id" class="form-control selectpicker show-tick" data-live-search="true" title="Escoge una categoría">
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('categoria_id')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="ubicacion_id" class="form-label">Ubicación:</label>
                        <select name="ubicacion_id" id="ubicacion_id" class="form-control selectpicker show-tick" data-live-search="true" title="Escoge una ubicación">
                            @foreach ($ubicaciones as $ubicacion)
                                <option value="{{ $ubicacion->id }}" {{ old('ubicacion_id', $producto->ubicacion_id) == $ubicacion->id ? 'selected' : '' }}>
                                    {{ $ubicacion->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('ubicacion_id')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="condicion_id" class="form-label">Condición:</label>
                        <select name="condicion_id" id="condicion_id" class="form-control selectpicker show-tick" data-live-search="true" title="Escoge una condición">
                            @foreach ($condiciones as $condicion)
                                <option value="{{ $condicion->id }}" {{ old('condicion_id', $producto->condicion_id) == $condicion->id ? 'selected' : '' }}>
                                    {{ $condicion->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('condicion_id')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>
                </div>

                <hr>

                <!-- Atributos Dinámicos -->
                <h3 class="mt-4">Atributos del Producto (Opcional)</h3>
<div id="attributes-container" class="mb-4">
    {{-- Prioriza old() si hay errores de validación, de lo contrario usa los atributos del producto --}}
    @php
        $currentAttributes = old('atributos', $producto->atributos->toArray());
        // Asegurarse de que $currentAttributes sea un array para el forelse
        if ($currentAttributes instanceof \Illuminate\Database\Eloquent\Collection) {
            $currentAttributes = $currentAttributes->toArray();
        }
    @endphp

    @forelse ($currentAttributes as $index => $attribute)
        <div class="attribute-row">
            <div class="form-group">
                <label for="atributos_{{ $index }}_nombre">Nombre del Atributo:</label>
                <input type="text" name="atributos[{{ $index }}][nombre]" id="atributos_{{ $index }}_nombre"
                    class="form-control" value="{{ $attribute['nombre'] ?? '' }}">
                @error('atributos.' . $index . '.nombre')
                    <small class="text-danger">{{ '*' . $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="atributos_{{ $index }}_valor">Valor del Atributo:</label>
                <input type="text" name="atributos[{{ $index }}][valor]" id="atributos_{{ $index }}_valor"
                    class="form-control" value="{{ $attribute['valor'] ?? '' }}">
                @error('atributos.' . $index . '.valor')
                    <small class="text-danger">{{ '*' . $message }}</small>
                @enderror
            </div>
            {{-- Campo oculto para el ID del atributo existente (crucial para actualizar/eliminar en el backend) --}}
            @if (isset($attribute['id']))
                <input type="hidden" name="atributos[{{ $index }}][id]" value="{{ $attribute['id'] }}">
            @endif
            <button type="button" class="btn btn-danger remove-attribute-row">Eliminar</button>
        </div>
    @empty
        {{-- Si no hay atributos existentes (ni en old, ni en el producto), inicia con una fila vacía --}}
        <div class="attribute-row">
            <div class="form-group">
                <label for="atributos_0_nombre">Nombre del Atributo:</label>
                <input type="text" name="atributos[0][nombre]" id="atributos_0_nombre" class="form-control">
            </div>
            <div class="form-group">
                <label for="atributos_0_valor">Valor del Atributo:</label>
                <input type="text" name="atributos[0][valor]" id="atributos_0_valor" class="form-control">
            </div>
            <button type="button" class="btn btn-danger remove-attribute-row">Eliminar</button>
        </div>
    @endforelse
</div>
                <button type="button" id="add-attribute-row" class="btn btn-success mb-4">Añadir Atributo</button>

                <hr>

                <!-- Botones de Acción -->
                <div class="row g-3 mt-4">
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary">Actualizar Producto</button>
                        <a href="{{ route('productos.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <!-- ¡IMPORTANTE! jQuery CDN: Ahora SIN atributos integrity/crossorigin para evitar bloqueos. -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- CDN de Bootstrap-select JS y su idioma (cargado una única vez) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/i18n/defaults-es_ES.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar todos los selectpickers
            if (typeof jQuery !== 'undefined') {
                $('.selectpicker').selectpicker();
            } else {
                console.error('ERROR: jQuery NO está disponible. El selectpicker no funcionará.');
            }

            // Determinar el índice inicial para los nuevos atributos
            // Si hay atributos old (después de una validación fallida) o atributos existentes,
            // usa el siguiente índice disponible.
            let attributeRowIndex = 0;
            const existingAttributeRows = document.querySelectorAll('#attributes-container .attribute-row');
            if (existingAttributeRows.length > 0) {
                // Si hay filas existentes, el índice para la próxima fila nueva será el número de filas existentes
                attributeRowIndex = existingAttributeRows.length;
            } else if ({{ old('atributos') ? 'true' : 'false' }}) {
                // Si no hay filas existentes pero hay datos old, usa el conteo de old
                attributeRowIndex = {{ old('atributos') ? count(old('atributos')) : 0 }};
            }


            // Función para añadir una nueva fila de atributo
            document.getElementById('add-attribute-row').addEventListener('click', function() {
                const container = document.getElementById('attributes-container');
                const newRow = document.createElement('div');
                newRow.classList.add('attribute-row');
                newRow.innerHTML = `
                    <div class="form-group">
                        <label for="atributos_${attributeRowIndex}_nombre">Nombre del Atributo:</label>
                        <input type="text" name="atributos[${attributeRowIndex}][nombre]" id="atributos_${attributeRowIndex}_nombre" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="atributos_${attributeRowIndex}_valor">Valor del Atributo:</label>
                        <input type="text" name="atributos[${attributeRowIndex}][valor]" id="atributos_${attributeRowIndex}_valor" class="form-control">
                    </div>
                    <button type="button" class="btn btn-danger remove-attribute-row">Eliminar</button>
                `;
                container.appendChild(newRow);
                attributeRowIndex++;
            });

            // Función para eliminar una fila de atributo (delegación de eventos)
            document.getElementById('attributes-container').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-attribute-row')) {
                    e.target.closest('.attribute-row').remove();
                }
            });
        });
    </script>
@endpush
