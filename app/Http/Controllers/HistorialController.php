<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Condicione;
use App\Models\Historial;
use App\Models\Producto;
use App\Models\TipoEvento;
use App\Models\Ubicacione;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Routing\Controller;

class HistorialController extends Controller
{

    function __construct () {
        $this->middleware('permission:ver-historial|crear-historial|editar-historial|mostrar-historial|eliminar-historial', ['only' => ['index', 'getProductData']]);
        $this->middleware('permission:crear-historial', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-historial', ['only' => ['edit', 'update']]);
        $this->middleware('permission:mostrar-historial', ['only' => ['show', 'downloadPdf']]);
    }

    /**
     * Muestra una lista paginada de registros de historial.
     */
    public function index(Request $request)
    {
        try {
            $tiposEvento = TipoEvento::orderBy('nombre')->get();
            $categorias = Categoria::orderBy('nombre')->get();

            $historialesQuery = Historial::query()
                ->with([
                    'producto',
                    'tipoEvento',
                    'user',
                    'ubicacionAntigua',
                    'ubicacionNueva',
                    'condicionAntigua',
                    'condicionNueva'
                ]);

            // --- Aplicación de Filtros ---
            if ($request->filled('tipo_evento')) {
                $historialesQuery->where('tipo_evento_id', $request->tipo_evento);
            }

            if ($request->filled('serial_producto')) {
                $serial = $request->serial_producto;
                $historialesQuery->whereHas('producto', function ($query) use ($serial) {
                    $query->where('serial', 'like', '%' . $serial . '%');
                });
            }

            if ($request->filled('categoria_producto')) {
                $categoria_id = $request->categoria_producto;
                $historialesQuery->whereHas('producto.categoria', function ($query) use ($categoria_id) {
                    $query->where('id', $categoria_id);
                });
            }

            // Ordenar los resultados para mostrar el mas reciente de primero
            $historiales = $historialesQuery->orderBy('fecha_evento', 'desc')->paginate(10);

            return view('historiales.index', compact('historiales', 'tiposEvento', 'categorias'));
        } catch (Exception $e) {
            Log::error('Error al cargar la lista de historial con filtros: ' . $e->getMessage(), ['exception' => $e, 'request_params' => $request->all()]);
            return redirect()->back()->with('error', 'No se pudieron cargar los registros de historial con los filtros aplicados.');
        }
    }

    /**
     * Muestra el formulario para crear un nuevo registro de historial.
     */
    public function create()
    {
        try {
            $productos = Producto::with(['ubicacion', 'condicion'])->get(['id', 'nombre', 'codigo', 'ubicacion_id', 'condicion_id']);
            $tiposEvento = TipoEvento::all();
            $ubicaciones = Ubicacione::all();
            $condiciones = Condicione::all();

            // Obtener los IDs de los tipos de evento específicos
            $idTipoEventoReubicacion = TipoEvento::where('nombre', 'Reubicacion')->value('id');
            $idTipoEventoCambioPiezas = TipoEvento::where('nombre', 'Cambio de Piezas')->value('id');

            return view('historiales.create', compact(
                'productos',
                'tiposEvento',
                'ubicaciones',
                'condiciones',
                'idTipoEventoReubicacion',
                'idTipoEventoCambioPiezas',
            ));
        } catch (Exception $e) {
            // Manejo de errores
            return redirect()->back()->with('error', 'Error al cargar el formulario de historial: ' . $e->getMessage());
        }
    }

    public function getProductData($id)
    {
        // Cargar el producto con todas las relaciones necesarias para mostrar en el modal
        // Asegúrate de que las relaciones 'marca' y 'categoria' estén definidas en tu modelo Producto.
        $producto = Producto::with(['ubicacion', 'condicion', 'atributos', 'marca', 'categoria'])->find($id);

        if (!$producto) {
            // Si el producto no se encuentra, devuelve un error 404
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }

        // Mapear los atributos a un formato más simple para la respuesta JSON
        $atributos = $producto->atributos ? $producto->atributos->map(function ($attr) {
            return ['nombre' => $attr->nombre, 'valor' => $attr->valor];
        })->toArray() : [];

        // Retornar los datos del producto en formato JSON
        return response()->json([
            'id' => $producto->id,
            'nombre' => $producto->nombre,
            'codigo' => $producto->codigo,
            'serial' => $producto->serial ?? 'N/A', // Asumiendo que 'serial' es un campo directo en Producto
            'marca_nombre' => $producto->marca->nombre ?? 'N/A', // Accede al nombre a través de la relación 'marca'
            'categoria_nombre' => $producto->categoria->nombre ?? 'N/A', // Accede al nombre a través de la relación 'categoria'
            'ubicacion_actual_id' => $producto->ubicacion_id,
            'ubicacion_actual' => $producto->ubicacion->nombre ?? 'N/A',
            'condicion_actual_id' => $producto->condicion_id,
            'condicion_actual_nombre' => $producto->condicion->nombre ?? 'N/A',
            'estado' => $producto->estado, // Asumiendo que 'estado' es un campo directo en Producto (1 para activo, 0 para inactivo)
            'atributos' => $atributos,
        ]);
    }

    /**
     * Almacena un nuevo registro de historial y actualiza el producto.
     */
public function store(Request $request)
    {
        // dd($request->all()); // Descomenta esta línea para depurar el request, luego comenta de nuevo.
        // Obtener los IDs de los tipos de evento específicos para la validación
        $idTipoEventoReubicacion = TipoEvento::where('nombre', 'Reubicacion')->value('id');
        $idTipoEventoCambioPiezas = TipoEvento::where('nombre', 'Cambio de Piezas')->value('id');

        $rules = [
            'producto_id' => 'required|exists:productos,id',
            'user_id' => 'required|exists:users,id',
            'tipo_evento_id' => 'required|exists:tipos_evento,id',
            'ubicacion_antigua_id' => 'nullable|exists:ubicaciones,id',
            'condicion_antigua_id' => 'nullable|exists:condiciones,id',
            'condicion_nueva_id' => 'nullable|exists:condiciones,id',
            'fecha_evento' => 'required|date_format:Y-m-d\TH:i',
            'descripcion' => 'nullable|string|max:1000',
            'atributos' => 'array',
            'atributos.*.nombre' => 'nullable|string|max:255',
            'atributos.*.valor' => 'nullable|string|max:255',
        ];

        // Reglas condicionales para 'ubicacion_nueva_id' y 'condicion_nueva_id'
        if ((int)$request->input('tipo_evento_id') === (int)$idTipoEventoReubicacion) {
            $rules['ubicacion_nueva_id'] = 'required|exists:ubicaciones,id';
        } else {
            $rules['ubicacion_nueva_id'] = 'nullable|exists:ubicaciones,id'; // Asegura que se valida si se envía, pero no es requerido
        }

        $request->validate($rules);

        // Iniciar transacción para asegurar que todas las operaciones se completan o ninguna lo hace
        DB::beginTransaction();

        try {
            // *** AÑADE ESTAS LÍNEAS AQUÍ PARA CARGAR EL PRODUCTO Y SUS RELACIONES ***
            // Esto es crucial para poder capturar el estado anterior.
            $producto = Producto::with(['ubicacion', 'condicion', 'marca', 'categoria', 'atributos'])->find($request->producto_id);

            if (!$producto) {
                throw new Exception('Producto no encontrado para la actualización. ID: ' . $request->producto_id);
            }

            // *** AÑADE O CONFIRMA ESTE BLOQUE PARA CAPTURAR EL ESTADO ANTERIOR DEL PRODUCTO ***
            // Creamos un array con los datos relevantes del producto
            // dd($producto); // Debug: Descomenta para ver el contenido del producto antes de serializarlo.
            $productoEstadoAnterior = [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'codigo' => $producto->codigo,
                'serial' => $producto->serial ?? null,
                'estado' => $producto->estado,
                'ubicacion' => [
                    'id' => $producto->ubicacion_id,
                    'nombre' => $producto->ubicacion->nombre ?? 'Sin Modificar' // Usar "Sin Modificar" aquí también
                ],
                'condicion' => [
                    'id' => $producto->condicion_id,
                    'nombre' => $producto->condicion->nombre ?? 'Sin Modificar' // Usar "Sin Modificar" aquí también
                ],
                'marca' => [
                    'id' => $producto->marca->id ?? null,
                    'nombre' => $producto->marca->nombre ?? 'Sin Modificar'
                ],
                'categoria' => [
                    'id' => $producto->categoria->id ?? null,
                    'nombre' => $producto->categoria->nombre ?? 'Sin Modificar'
                ],
                // Mapear los atributos del producto. Asegurarse de que `atributos` existe y es una colección.
                'atributos' => $producto->atributos ? $producto->atributos->map(function($attr){
                    return ['nombre' => $attr->nombre, 'valor' => $attr->valor];
                })->toArray() : [], // Si no hay atributos, guarda un array vacío
            ];

            // Debug: Descomenta para ver el array final que se intentará guardar como JSON.
            // dd($productoEstadoAnterior);

            // Crear el registro de historial con los IDs antiguos del producto y el JSON del estado anterior
            Historial::create([
                'producto_id' => $request->producto_id,
                'user_id' => $request->user_id,
                'tipo_evento_id' => $request->tipo_evento_id,
                'ubicacion_antigua_id' => $producto->ubicacion_id, // Guarda la ubicación actual del producto (ID)
                'condicion_antigua_id' => $producto->condicion_id, // Guarda la condición actual del producto (ID)
                'ubicacion_nueva_id' => $request->input('ubicacion_nueva_id'),
                'condicion_nueva_id' => $request->input('condicion_nueva_id'),
                'fecha_evento' => $request->fecha_evento,
                'descripcion' => $request->descripcion,
                'producto_estado_anterior_json' => $productoEstadoAnterior, // ¡Guarda el JSON aquí!
            ]);

            // Lógica para actualizar el PRODUCTO (ubicación, condición y atributos) DESPUÉS de guardar el historial
            // ... (el resto de tu lógica para actualizar el producto se mantiene igual) ...

            if ((int)$request->input('tipo_evento_id') === (int)$idTipoEventoReubicacion) {
                $producto->ubicacion_id = $request->ubicacion_nueva_id;
            }

            if ($request->has('condicion_nueva_id') && $request->input('condicion_nueva_id') !== null) {
                $producto->condicion_id = $request->condicion_nueva_id;
            }

            if ((int)$request->input('tipo_evento_id') === (int)$idTipoEventoCambioPiezas) {
                $producto->atributos()->delete();

                if ($request->has('atributos') && is_array($request->atributos)) {
                    foreach ($request->atributos as $atributo) {
                        if (!empty($atributo['nombre'])) {
                            $producto->atributos()->create([
                                'nombre' => $atributo['nombre'],
                                'valor' => $atributo['valor'] ?? null,
                            ]);
                        }
                    }
                }
            }

            // Guardar los cambios en el producto
            $producto->save();

            DB::commit(); // Confirmar todas las operaciones si todo fue bien

            return redirect()->route('historiales.index')->with('success', 'Registro de historial creado con éxito.');
        } catch (Exception $e) {
            DB::rollBack(); // Revertir todas las operaciones si algo falla
            Log::error('Error al crear el registro de historial: ' . $e->getMessage(), ['exception' => $e, 'request' => $request->all()]);
            return redirect()->back()->withInput()->with('error', 'Error al crear el registro de historial: ' . $e->getMessage());
        }
    }

    /**
     * Muestra los detalles de un registro de historial específico en la vista de detalles.
     */
    public function show(Historial $historial)
    {
        try {
            // Cargar todas las relaciones necesarias para mostrar en la vista de detalles del historial
            $historial->load([
                'producto.marca',     // Carga la marca del producto asociado al historial
                'producto.categoria', // Carga la categoría del producto asociado al historial
                'producto.atributos', // Carga los atributos del producto asociado al historial
                'tipoEvento',
                'user',
                'ubicacionAntigua',
                'ubicacionNueva',
                'condicionAntigua',
                'condicionNueva'
            ]);
            // Retorna la vista 'historiales.pdf.detallesHistorial' con los datos del historial
            return view('historiales.pdf.detallesHistorial', compact('historial'));
        } catch (Exception $e) {
            Log::error('Error al cargar detalles del historial en la vista de detalles: ' . $e->getMessage(), ['id' => $historial->id, 'exception' => $e]);
            return redirect()->route('historiales.index')->with('error', 'No se pudieron cargar los detalles del registro de historial.');
        }
    }

    // Métodos 'edit', 'update', 'destroy' se mantienen sin cambios por ser inmutables
    public function edit(Historial $historial)
    {
        return view('historiales.edit', ['historial' => $historial]);
    }

    public function update(Request $request, string $id)
    {
        Log::info('Intento de actualizar un registro de historial con ID: ' . $id . '. Esta funcionalidad no está implementada.');
        return redirect()->route('historiales.index')->with('info', 'La actualización de registros de historial no está implementada.');
    }

    public function destroy(string $id)
    {

    }

    public function downloadPdf(Historial $historial)
    {
        try {
            // Cargar todas las relaciones necesarias para el PDF
            $historial->load([
                'producto.marca',
                'producto.categoria',
                'producto.atributos',
                'tipoEvento',
                'user',
                'ubicacionAntigua',
                'ubicacionNueva',
                'condicionAntigua',
                'condicionNueva'
            ]);

            // Carga la vista Blade con los datos del historial
            $pdf = Pdf::loadView('historiales.pdf.detallesHistorial', compact('historial'));

            // Opcional: Ajustar el tamaño del papel y la orientación (ej: 'a4', 'letter', 'landscape', 'portrait')
            // $pdf->setPaper('a4', 'portrait');

            // *** CAMBIO CLAVE AQUÍ: Usar stream() en lugar de download() ***
            // Esto hará que el PDF se abra en el navegador en lugar de descargarse directamente.
            return $pdf->stream('historial_' . $historial->id . '.pdf');

        } catch (Exception $e) {
            Log::error('Error al generar el PDF para el historial: ' . $e->getMessage(), ['id' => $historial->id, 'exception' => $e]);
            return redirect()->back()->with('error', 'No se pudo generar el PDF para este registro de historial. Detalle: ' . $e->getMessage());
        }
    }
}
