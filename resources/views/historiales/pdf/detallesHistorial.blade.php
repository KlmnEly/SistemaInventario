<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Historial #{{ $historial->id }}</title>
    {{-- Estilos CSS inline para el PDF --}}
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        h1, h2, h3 {
            color: #333;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 8px;
        }
        .section-title {
            background-color: #f0f0f0;
            padding: 8px;
            margin-top: 15px;
            margin-bottom: 10px;
            border-left: 5px solid #007bff;
        }
        .data-item {
            margin-bottom: 5px;
        }
        .data-label {
            display: inline-block; /* Changed to inline-block for proper width application */
            width: 150px; /* Fixed width for labels */
            color: black; /* Ensure color is black */
        }
        /* Ensured strong tag takes up the full width of data-label */
        .data-item strong {
            display: inline-block;
            width: 150px; /* Aligned with data-label */
        }
        .attributes-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        .attributes-list li {
            background-color: #fafafa;
            padding: 5px;
            margin-bottom: 3px;
            border-radius: 4px;
        }
        /* Estilos específicos para las secciones Antes y Después */
        .section-before-title {
            background-color: #fff3cd; /* Color de advertencia similar a Bootstrap yellow-200 */
            border-left: 5px solid #ffc107; /* Color de advertencia similar a Bootstrap yellow-500 */
            padding: 8px;
            margin-top: 15px;
            margin-bottom: 10px;
        }
        .section-after-title {
            background-color: #d4edda; /* Color de éxito similar a Bootstrap green-200 */
            border-left: 5px solid #28a745; /* Color de éxito similar a Bootstrap green-500 */
            padding: 8px;
            margin-top: 15px;
            margin-bottom: 10px;
        }
        .alert-warning-custom { /* Estilo para el mensaje de "no disponible" */
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            padding: 10px;
            margin-top: 15px;
            border-radius: 4px;
            color: #856404; /* Color de texto para alertas de advertencia */
        }

        /* NEW STYLES for table-like layout for columns */
        .two-column-layout {
            display: table;
            width: 100%;
            border-collapse: collapse; /* Ensure cells fit together */
        }
        .column-row {
            display: table-row;
        }
        .column-left,
        .column-right {
            display: table-cell;
            width: 50%; /* Each column takes half width */
            vertical-align: top; /* Align content to the top */
            padding-right: 1%; /* Small gap between columns */
        }
        .column-right {
            padding-left: 1%; /* Small gap between columns */
            padding-right: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reporte de Historial de Producto</h1>
        <h2>Registro #{{ $historial->id }}</h2>
        <p>Fecha de Generación: {{ now()->format('d/m/Y H:i') }}</p>

        <div class="section-title"><h3>Detalles del Evento</h3></div>
        <div class="data-item"><strong>Fecha del Evento:</strong> {{ \Carbon\Carbon::parse($historial->fecha_evento)->format('d/m/Y H:i') }}</div>
        <div class="data-item"><strong>Tipo de Evento:</strong> {{ $historial->tipoEvento->nombre ?? 'Sin Modificar' }}</div>
        <div class="data-item"><strong>Realizado por:</strong> {{ $historial->user->name ?? 'Sin Modificar' }}</div>
        <div class="data-item"><strong>Descripción:</strong> {{ $historial->descripcion ?? 'Sin Modificar' }}</div>

        {{-- SECCIÓN: ESTADO ANTES DEL EVENTO --}}
        @if ($historial->producto_estado_anterior_json)
            @php
                $anterior = $historial->producto_estado_anterior_json;
            @endphp
            <div class="section-before-title">
                <h3>Estado del Producto ANTES del Evento</h3>
            </div>
            {{-- Using table-like divs for two-column layout --}}
            <div class="two-column-layout">
                <div class="column-row">
                    <div class="column-left">
                        <div class="data-item">
                            <strong class="data-label">Nombre:</strong> {{ $anterior['nombre'] ?? 'Sin Modificar' }}
                        </div>
                        <div class="data-item">
                            <strong class="data-label">Código:</strong> {{ $anterior['codigo'] ?? 'Sin Modificar' }}
                        </div>
                        <div class="data-item">
                            <strong class="data-label">No. Serie:</strong> {{ $anterior['serial'] ?? 'Sin Modificar' }}
                        </div>
                        <div class="data-item">
                            <strong class="data-label">Marca:</strong> {{ $anterior['marca']['nombre'] ?? 'Sin Modificar' }}
                        </div>
                        <div class="data-item">
                            <strong class="data-label">Categoría:</strong> {{ $anterior['categoria']['nombre'] ?? 'Sin Modificar' }}
                        </div>
                        <div class="data-item">
                            <strong class="data-label">Estado:</strong> {{ ($anterior['estado'] ?? null) == 1 ? 'Activo' : 'Inactivo' }}
                        </div>
                    </div>
                    <div class="column-right">
                        <div class="data-item">
                            <strong class="data-label">Ubicación:</strong> {{ $anterior['ubicacion']['nombre'] ?? 'Sin Modificar' }}
                        </div>
                        <div class="data-item">
                            <strong class="data-label">Condición:</strong> {{ $anterior['condicion']['nombre'] ?? 'Sin Modificar' }}
                        </div>
                    </div>
                </div>
            </div>

            @if (!empty($anterior['atributos']))
                <h6 style="margin-top: 15px;">Atributos Antes:</h6>
                <ul class="attributes-list">
                    @foreach ($anterior['atributos'] as $attr)
                        <li>
                            <strong class="data-label">{{ $attr['nombre'] ?? 'Sin Modificar' }}:</strong> {{ $attr['valor'] ?? 'Sin Modificar' }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No había atributos registrados para este producto antes del evento.</p>
            @endif
        @else
            <p class="alert-warning-custom">Información del estado anterior del producto no disponible.</p>
        @endif

        <hr>

        <div class="section-after-title">
            <h3>Estado del Producto DESPUÉS del Evento (Estado Actual)</h3>
        </div>
        {{-- Using table-like divs for two-column layout --}}
        <div class="two-column-layout">
            <div class="column-row">
                <div class="column-left">
                    <div class="data-item">
                        <strong class="data-label">Nombre:</strong> {{ $historial->producto->nombre ?? 'Sin Modificar' }}
                    </div>
                    <div class="data-item">
                        <strong class="data-label">Código:</strong> {{ $historial->producto->codigo ?? 'Sin Modificar' }}
                    </div>
                    <div class="data-item">
                        <strong class="data-label">No. Serie:</strong> {{ $historial->producto->serial ?? 'Sin Modificar' }}
                    </div>
                    <div class="data-item">
                        <strong class="data-label">Marca:</strong> {{ $historial->producto->marca->nombre ?? 'Sin Modificar' }}
                    </div>
                    <div class="data-item">
                        <strong class="data-label">Categoría:</strong> {{ $historial->producto->categoria->nombre ?? 'Sin Modificar' }}
                    </div>
                    <div class="data-item">
                        <strong class="data-label">Estado:</strong> {{ ($historial->producto->estado ?? null) == 1 ? 'Activo' : 'Inactivo' }}
                    </div>
                </div>
                <div class="column-right">
                    <div class="data-item">
                        <strong class="data-label">Ubicación:</strong> {{ $historial->producto->ubicacion->nombre ?? 'Sin Modificar' }}
                    </div>
                    <div class="data-item">
                        <strong class="data-label">Condición:</strong> {{ $historial->producto->condicion->nombre ?? 'Sin Modificar' }}
                    </div>
                </div>
            </div>
        </div>

        @if ($historial->producto && $historial->producto->atributos->count() > 0)
            <h6 style="margin-top: 15px;">Atributos Actuales:</h6>
            <ul class="attributes-list">
                @foreach ($historial->producto->atributos as $atributo)
                    <li>
                        <strong class="data-label">{{ $atributo->nombre }}:</strong> {{ $atributo->valor ?? 'Sin Modificar' }}
                    </li>
                @endforeach
            </ul>
        @else
            <p>No hay atributos registrados para este producto actualmente.</p>
        @endif

    </div>
</body>
</html>
