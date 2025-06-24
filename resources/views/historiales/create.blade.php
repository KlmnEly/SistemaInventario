@extends('template')

@section('title', 'Crear Registro de Historial')

@push('css')
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <style>
        /* Tus estilos CSS ... */
        .attribute-row {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .attribute-row .form-group {
            flex: 1;
            min-width: 150px;
        }

        .attribute-row .btn-danger {
            flex-shrink: 0;
        }

        /* Estilos para los campos condicionales (ahora siempre visibles) */
        .conditional-section {
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            padding: 15px;
            margin-top: 20px;
            background-color: #f9f9f9;
        }

        .current-location-display {
            font-weight: bold;
            color: #007bff;
            margin-top: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Crear Registro de Historial</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('historiales.index') }}">Historial</a></li>
            <li class="breadcrumb-item active">Crear</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">

            <form action="{{ route('historiales.store') }}" method="POST">
                <div class="row g-3 mb-4">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="col-md-6">
                        <label for="producto_id" class="form-label">Producto:</label>
                        <select class="form-control selectpicker" data-live-search="true" id="producto_id"
                            name="producto_id" title="Selecciona un producto" required>
                            <option value="">Selecciona un producto</option>
                            @foreach ($productos as $producto)
                                <option value="{{ $producto->id }}"
                                    {{ old('producto_id') == $producto->id ? 'selected' : '' }}>
                                    {{ $producto->nombre }} ({{ $producto->codigo }})
                                </option>
                            @endforeach
                        </select>
                        @error('producto_id')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                        <div id="current_product_location_display" class="current-location-display mt-2"
                            style="display: none;">
                            Ubicación actual: <span id="current_location_name"></span>
                        </div>
                    </div>

                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                    <div class="col-md-6">
                        <label for="tipo_evento_id" class="form-label">Tipo de Evento:</label>
                        <select class="form-control selectpicker" data-live-search="true" id="tipo_evento_id" name="tipo_evento_id"
                            title="Selecciona un tipo de evento" required>
                            <option value="">Selecciona un tipo de evento</option>
                            @foreach ($tiposEvento as $tipoEvento)
                                <option value="{{ $tipoEvento->id }}"
                                    {{ old('tipo_evento_id') == $tipoEvento->id ? 'selected' : '' }}>
                                    {{ $tipoEvento->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo_evento_id')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    {{-- Sección para "Reubicacion" - AHORA SIEMPRE VISIBLE --}}
                    <div id="reubicacion_section" class="row g-3 mb-4 conditional-section">
                        <h4 class="mb-3">Detalles de Reubicación</h4>

                        <div class="col-md-6">
                            <label for="ubicacion_antigua_display" class="form-label">Antigua Ubicación:</label>
                            <input type="text" class="form-control" id="ubicacion_antigua_display" value=""
                                disabled>
                            <input type="hidden" name="ubicacion_antigua_id" id="ubicacion_antigua_hidden_id">
                        </div>

                        <div class="col-md-6">
                            <label for="ubicacion_nueva_id" class="form-label">Nueva Ubicación:</label>
                            <select class="form-control selectpicker show-tick" data-live-search="true"
                                id="ubicacion_nueva_id" name="ubicacion_nueva_id" title="Selecciona una nueva ubicación">
                                <option value="">Selecciona una nueva ubicación</option>
                                @foreach ($ubicaciones as $ubicacion)
                                    <option value="{{ $ubicacion->id }}"
                                        {{ old('ubicacion_nueva_id') == $ubicacion->id ? 'selected' : '' }}>
                                        {{ $ubicacion->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ubicacion_nueva_id')
                                <small class="text-danger">{{ '*' . $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- Sección para Detalles de Condición - AHORA SIEMPRE VISIBLE --}}
                    <div class="row g-3 mb-4 conditional-section">
                        <h4 class="mb-3">Detalles de Condición del Producto</h4>

                        <div class="col-md-6">
                            <label for="condicion_actual_display" class="form-label">Condición Actual del Producto:</label>
                            <input type="text" class="form-control" id="condicion_actual_display" value=""
                                disabled>
                            <input type="hidden" name="condicion_antigua_id" id="condicion_antigua_hidden_id">
                        </div>

                        <div class="col-md-6">
                            <label for="condicion_nueva_id" class="form-label">Asignar Nueva Condición:</label>
                            <select class="form-control selectpicker show-tick" data-live-search="true"
                                id="condicion_nueva_id" name="condicion_nueva_id" title="Selecciona una nueva condición">
                                <option value="">Selecciona una nueva condición</option>
                                @foreach ($condiciones as $condicion)
                                    <option value="{{ $condicion->id }}"
                                        {{ old('condicion_nueva_id') == $condicion->id ? 'selected' : '' }}>
                                        {{ $condicion->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('condicion_nueva_id')
                                <small class="text-danger">{{ '*' . $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- Sección para "Cambio de Piezas" (Atributos) - AHORA SIEMPRE VISIBLE --}}
                    <div id="cambio_piezas_section" class="conditional-section">
                        <h4 class="mb-3">Atributos del Producto (Cambio de Piezas)</h4>
                        <div id="attributes-container" class="mb-4">
                            {{-- Los atributos se cargarán aquí dinámicamente con JS --}}
                            @if (old('atributos'))
                                @foreach (old('atributos') as $index => $oldAttribute)
                                    <div class="attribute-row">
                                        <div class="form-group">
                                            <label for="atributos_{{ $index }}_nombre">Nombre del Atributo:</label>
                                            <input type="text" name="atributos[{{ $index }}][nombre]"
                                                id="atributos_{{ $index }}_nombre" class="form-control"
                                                value="{{ $oldAttribute['nombre'] ?? '' }}">
                                            @error('atributos.' . $index . '.nombre')
                                                <small class="text-danger">{{ '*' . $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="atributos_{{ $index }}_valor">Valor del Atributo:</label>
                                            <input type="text" name="atributos[{{ $index }}][valor]"
                                                id="atributos_{{ $index }}_valor" class="form-control"
                                                value="{{ $oldAttribute['valor'] ?? '' }}">
                                            @error('atributos.' . $index . '.valor')
                                                <small class="text-danger">{{ '*' . $message }}</small>
                                            @enderror
                                        </div>
                                        <button type="button"
                                            class="btn btn-danger remove-attribute-row">Eliminar</button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" id="add-attribute-row" class="btn btn-success mb-4">Añadir
                            Atributo</button>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_evento" class="form-label">Fecha y Hora del Evento:</label>
                        <input type="datetime-local" class="form-control" id="fecha_evento" name="fecha_evento"
                            value="{{ old('fecha_evento', \Carbon\Carbon::now()->format('Y-m-d\TH:i')) }}" required>
                        @error('fecha_evento')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción del Evento:</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar Registro</button>
                    <a href="{{ route('historiales.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/i18n/defaults-es_ES.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inicializar todos los selectpickers una sola vez al cargar el DOM
            $('.selectpicker').selectpicker();

            const productoSelect = $('#producto_id');
            const tipoEventoSelect = $('#tipo_evento_id');

            // Campos de Ubicación (ahora siempre visibles)
            const ubicacionAntiguaDisplay = $('#ubicacion_antigua_display');
            const ubicacionAntiguaHiddenId = $('#ubicacion_antigua_hidden_id');
            const ubicacionNuevaSelect = $('#ubicacion_nueva_id');
            const currentLocationDisplay = $('#current_product_location_display');
            const currentLocationNameSpan = $('#current_location_name');

            // Campos de Condición (ahora siempre visibles)
            const condicionActualDisplay = $('#condicion_actual_display');
            const condicionAntiguaHiddenId = $('#condicion_antigua_hidden_id');
            const condicionNuevaSelect = $('#condicion_nueva_id');

            // Campos de Atributos (ahora siempre visibles)
            const attributesContainer = $('#attributes-container');
            const addAttributeButton = $('#add-attribute-row');


            const ID_TIPO_EVENTO_REUBICACION = {{ $idTipoEventoReubicacion ?? 'null' }};
            const ID_TIPO_EVENTO_CAMBIO_PIEZAS = {{ $idTipoEventoCambioPiezas ?? 'null' }};
            const ID_TIPO_EVENTO_CAMBIO_CONDICION = {{ $idTipoEventoCambioCondicion ?? 'null' }}; // Nuevo

            const GET_PRODUCT_DATA_URL_TEMPLATE = "{{ route('historiales.getProductData', ':id') }}";

            let attributeRowIndex = {{ old('atributos') ? count(old('atributos')) : 0 }};

            function addAttributeRow(name = '', value = '', enabled = true) {
                const newRow = `
                    <div class="attribute-row">
                        <div class="form-group">
                            <label for="atributos_${attributeRowIndex}_nombre">Nombre del Atributo:</label>
                            <input type="text" name="atributos[${attributeRowIndex}][nombre]"
                                id="atributos_${attributeRowIndex}_nombre" class="form-control"
                                value="${name}" ${enabled ? '' : 'disabled'}>
                        </div>
                        <div class="form-group">
                            <label for="atributos_${attributeRowIndex}_valor">Valor del Atributo:</label>
                            <input type="text" name="atributos[${attributeRowIndex}][valor]"
                                id="atributos_${attributeRowIndex}_valor" class="form-control"
                                value="${value}" ${enabled ? '' : 'disabled'}>
                        </div>
                        <button type="button" class="btn btn-danger remove-attribute-row" ${enabled ? '' : 'disabled'}>Eliminar</button>
                    </div>
                `;
                attributesContainer.append(newRow);
                attributeRowIndex++;
            }

            function updateProductInfo() {
                const selectedProductId = productoSelect.val();

                // Limpiar campos de producto y ocultar display de ubicación si no hay producto seleccionado
                if (!selectedProductId) {
                    currentLocationDisplay.hide();
                    currentLocationNameSpan.text('');
                    ubicacionAntiguaDisplay.val('');
                    ubicacionAntiguaHiddenId.val('');
                    condicionActualDisplay.val('');
                    condicionAntiguaHiddenId.val('');
                    // attributesContainer.empty(); // No limpiar aquí, se limpia condicionalmente más abajo
                    attributeRowIndex = 0; // Resetear índice al limpiar atributos
                    toggleFieldStates(); // Ajustar el estado de los campos
                    return;
                }

                $.ajax({
                    url: GET_PRODUCT_DATA_URL_TEMPLATE.replace(':id', selectedProductId),
                    method: 'GET',
                    success: function(data) {
                        // Actualizar Ubicación Actual del producto
                        currentLocationNameSpan.text(data.ubicacion_actual || 'Sin ubicación');
                        currentLocationDisplay.show();
                        ubicacionAntiguaDisplay.val(data.ubicacion_actual || '');
                        ubicacionAntiguaHiddenId.val(data.ubicacion_actual_id || '');

                        // Actualizar Condición Actual del producto
                        condicionActualDisplay.val(data.condicion_actual_nombre || 'Sin condición');
                        condicionAntiguaHiddenId.val(data.condicion_actual_id || '');

                        // Limpiar y cargar atributos del producto
                        attributesContainer.empty(); // Limpiar el contenedor ANTES de añadir nuevos atributos
                        attributeRowIndex = 0; // Resetear el índice para los nuevos atributos
                        const isCambioPiezas = (parseInt(tipoEventoSelect.val()) === ID_TIPO_EVENTO_CAMBIO_PIEZAS);

                        if (data.atributos && data.atributos.length > 0) {
                            data.atributos.forEach(function(attribute) {
                                // Los atributos se cargan habilitados/deshabilitados según el tipo de evento
                                addAttributeRow(attribute.nombre, attribute.valor, isCambioPiezas);
                            });
                        } else if (isCambioPiezas) {
                            // Si es "Cambio de Piezas" y no hay atributos, añadir una fila vacía y editable
                            addAttributeRow('', '', true);
                        }
                        // Si no es "Cambio de Piezas" y no hay atributos, no añadir nada.
                        // La lógica para deshabilitar los que ya estaban por 'old()' se maneja en toggleFieldStates.


                        // Reiniciar selector de nueva ubicación
                        ubicacionNuevaSelect.val('').selectpicker('refresh');

                        // Ajustar el estado de los campos (requerido/deshabilitado)
                        toggleFieldStates();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching product data:', error);
                        alert('Error al cargar los datos del producto.');
                        // Limpiar campos y resetear estado en caso de error
                        currentLocationDisplay.hide();
                        currentLocationNameSpan.text('');
                        ubicacionAntiguaDisplay.val('');
                        ubicacionAntiguaHiddenId.val('');
                        condicionActualDisplay.val('');
                        condicionAntiguaHiddenId.val('');
                        attributesContainer.empty();
                        attributeRowIndex = 0;
                        toggleFieldStates();
                    }
                });
            }

            function toggleFieldStates() {
                const selectedTipoEventoId = parseInt(tipoEventoSelect.val());
                const selectedProductId = productoSelect.val(); // Para verificar si hay un producto seleccionado

                // --- Lógica para Nueva Ubicación ---
                // Solo es requerido y habilitado si el tipo de evento es "Reubicación" y hay un producto seleccionado
                const isReubicacion = (selectedTipoEventoId === ID_TIPO_EVENTO_REUBICACION);
                ubicacionNuevaSelect.prop('required', isReubicacion && selectedProductId);
                ubicacionNuevaSelect.prop('disabled', !isReubicacion || !selectedProductId);
                ubicacionNuevaSelect.selectpicker('refresh');

                // --- Lógica para Nueva Condición ---
                // CONDICION: Siempre habilitada, no es requerida por tipo de evento
                condicionNuevaSelect.prop('disabled', !selectedProductId); // Solo deshabilitar si no hay producto seleccionado
                condicionNuevaSelect.selectpicker('refresh');


                // --- Lógica para Atributos (Cambio de Piezas) ---
                const isCambioPiezas = (selectedTipoEventoId === ID_TIPO_EVENTO_CAMBIO_PIEZAS);

                // Habilitar/deshabilitar el botón "Añadir Atributo"
                addAttributeButton.prop('disabled', !isCambioPiezas || !selectedProductId);

                // Habilitar/deshabilitar inputs de atributos y botones de eliminar
                attributesContainer.find('.attribute-row input[type="text"]').prop('disabled', !isCambioPiezas || !selectedProductId);
                attributesContainer.find('.remove-attribute-row').prop('disabled', !isCambioPiezas || !selectedProductId);

                // Manejo especial para cuando el tipo de evento NO es "Cambio de Piezas"
                if (!isCambioPiezas) {
                    // Si no es "Cambio de Piezas", pero hay atributos cargados (ej. por updateProductInfo o old()),
                    // se asegura que estén deshabilitados. No los eliminamos para preservar la información visual.
                    // Si un usuario cambia de "Cambio de Piezas" a otro evento, los atributos siguen ahí, pero no editables.
                    if (attributesContainer.children().length > 0) {
                        attributesContainer.find('.attribute-row input[type="text"]').prop('disabled', true);
                        attributesContainer.find('.remove-attribute-row').prop('disabled', true);
                    }
                } else { // Es "Cambio de Piezas"
                    // Si es "Cambio de Piezas" y no hay atributos visibles (y hay producto), recárgalos.
                    // Esto cubre el caso de que se cambie el tipo de evento a "Cambio de Piezas" y los atributos no se hayan cargado aún.
                    if (attributesContainer.is(':empty') && selectedProductId) {
                        updateProductInfo(); // Recarga los atributos para que aparezcan habilitados
                    } else if (attributesContainer.is(':empty') && !selectedProductId) {
                         // Si es "Cambio de Piezas" pero NO hay producto, añade una fila vacía editable para empezar a escribir
                        addAttributeRow('', '', true);
                    }
                }
            }

            // --- Escuchadores de eventos ---

            tipoEventoSelect.on('change', function() {
                updateProductInfo();
            });

            productoSelect.on('change', function() {
                updateProductInfo();
            });

            addAttributeButton.on('click', function() {
                addAttributeRow('', '', true);
            });

            attributesContainer.on('click', '.remove-attribute-row', function() {
                $(this).closest('.attribute-row').remove();
            });

            // --- Ejecutar al cargar la página ---

            // Llamada inicial para establecer el estado correcto al cargar la página
            if (productoSelect.val()) {
                updateProductInfo();
            } else if (tipoEventoSelect.val()) {
                toggleFieldStates();
            } else {
                currentLocationDisplay.hide();
                currentLocationNameSpan.text('');
                ubicacionAntiguaDisplay.val('');
                ubicacionAntiguaHiddenId.val('');
                condicionActualDisplay.val('');
                condicionAntiguaHiddenId.val('');
                ubicacionNuevaSelect.val('').selectpicker('refresh');
                attributesContainer.empty();
                attributeRowIndex = 0;
                toggleFieldStates();
            }
        });
        console.log('Historial create.js loaded successfully');
    </script>
@endpush
