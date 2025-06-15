<?php

namespace App\Http\Controllers;

use App\Models\Atributo;
use App\Models\Categoria;
use App\Models\Condicion;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\Ubicacion;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Muestra una lista de todos los productos.
     */
    public function index()
    {
        // Cargamos los productos con sus relaciones
        $productos = Producto::with(['marca', 'categoria', 'ubicacion', 'condicion', 'atributos'])
            ->get();
        return view('productos.index', compact('productos'));
    }

    /**
     * Muestra el formulario para crear un nuevo producto.
     */
    public function create()
    {
        $marcas = Marca::where('estado', 1)->get();
        $categorias = Categoria::where('estado', 1)->get();
        $ubicaciones = Ubicacion::where('estado', 1)->get();
        $condiciones = Condicion::where('estado', 1)->get();

        // Pasamos todas las colecciones a la vista
        return view('productos.create', compact('marcas', 'categorias', 'ubicaciones', 'condiciones'));
    }

    /**
     * Almacena un nuevo producto en la base de datos.
     */
    public function store(Request $request)
    {
        // dd($request->all()); // Para depurar y ver los datos recibidos


        // Validamos los datos del formulario
        $request->validate([
            'marca_id' => 'required|exists:marcas,id',
            'categoria_id' => 'required|exists:categorias,id',
            'ubicacion_id' => 'required|exists:ubicaciones,id',
            'condicion_id' => 'required|exists:condiciones,id',
            'codigo' => 'required|string|max:50|unique:productos,codigo',
            'nombre' => 'required|string|max:255',
            'serial' => 'required|string|max:100|unique:productos,serial',

            // Validacion para los atributos
            'atributos' => 'nullable|array', // Atributos es un array opcional
            'atributos.*.nombre' => 'required_with:atributos|string|max:255', // El nombre de cada atributo es requerido si 'atributos' existe
            'atributos.*.valor' => 'required_with:atributos|string|max:255',  // El valor de cada atributo es requerido si 'atributos' existe
        ]);

        // Creamos el producto en la base de datos
        $producto = Producto::create($request->except('atributos'));

        if ($request->has('atributos')) {
            foreach ($request->input('atributos') as $atributoData) {
                $producto->atributos()->create($atributoData); // Crea el atributo y lo asocia al producto
            }
        }

        return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');

    }

    /**
     * Muestra los detalles de un producto específico.
     */
    public function show(Producto $producto)
    {
        // Cargamos el producto con sus relaciones
        $producto->load(['marca', 'categoria', 'ubicacion', 'condicion', 'atributos']);

        return view('productos.show', compact('producto'));
    }

    /**
     * Muestra el formulario para editar un producto específico.
     */
    public function edit(Producto $producto)
    {
        // Cargamos las opciones para los selectores (dropdowns) del formulario de edición
        $producto->load('atributos');

        $marcas = Marca::where('estado', 1)->get();
        $categorias = Categoria::where('estado', 1)->get();
        $ubicaciones = Ubicacion::where('estado', 1)->get();
        $condiciones = Condicion::where('estado', 1)->get();

        return view('productos.edit', compact('producto', 'marcas', 'categorias', 'ubicaciones', 'condiciones'));
    }

    /**
     * Actualiza un producto existente en la base de datos.
     * @param  \Illuminate\Http\Request  $request
     */
    public function update(Request $request, Producto $producto)
    {
        // Valida los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'marca_id' => 'required|exists:marcas,id',
            'categoria_id' => 'required|exists:categorias,id',
            'ubicacion_id' => 'required|exists:ubicaciones,id',
            'condicion_id' => 'required|exists:condiciones,id',
            'codigo' => 'required|string|max:50|unique:productos,codigo,' . $producto->id,
            'serial' => 'nullable|string|max:255',
            'estado' => 'required|boolean',
            // Validación para los atributos (incluye ID para los existentes)
            'atributos' => 'nullable|array',
            'atributos.*.id' => 'nullable|exists:atributos,id', // ID es opcional (para nuevos) pero debe existir si se provee
            'atributos.*.nombre' => 'required_with:atributos|string|max:255',
            'atributos.*.valor' => 'required_with:atributos|string|max:255',
        ]);

        // Actualiza el producto principal
        $producto->update($request->except('atributos'));

        // Sincronizamos los atributos (manejo de creacion, actualizacion y eliminacion)
        $atributosEnviados = collect($request->input('atributos', [])); // Convertimos los atributos enviados a una colección

        // Obtener Ids de atributos existentes asociados al producto
        $idsAtributosExistentes = $producto->atributos->pluck('id')->toArray();

        // Obtener Ids de atributos que fueron enviados en el formulario (Si tienen Id)
        $idsAtributosEnviados = $atributosEnviados->pluck('id')->filter()->toArray(); // Filtramos para obtener solo los IDs que no son nulos

        $atributosAEliminar = array_diff($idsAtributosExistentes, $idsAtributosEnviados); // Atributos que deben eliminarse
        if (!empty($atributosAEliminar)) {
            Atributo::whereIn('id', $atributosAEliminar)->delete(); // Elimina los atributos que ya no están en el formulario
        }

        // Atributos a crear o actualizar
        foreach ($atributosEnviados as $atributoData) {
            if (isset($atributoData['id']) && in_array($atributoData['id'], $idsAtributosExistentes)) {
                // Si el atributo tiene un id y ya existe para este oprducto, lo actualizamos
                $atributo = Atributo::find($atributoData['id']);
                if ($atributo) { // Doble verificacion por si el id no corresponde al producto inyectado
                    $atributo->update($atributoData);
                }
            } else {
                // Si no se encuentra el atributo, lo creamos como nuevo
                $producto->atributos()->create($atributoData);
            }
        }

        return redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Elimina o cambia el estado de un producto específico.
     */
    public function destroy(Producto $producto)
    {
        // Cambia el estado del producto. Si está activo, lo desactivamos; si está inactivo, lo activamos.
        $producto->estado = $producto->estado ? 0 : 1; // Cambia el estado del producto
        $producto->save();

        $action = $producto->estado == 0 ? 'eliminado' : 'restaurado';

        return redirect()->route('productos.index')->with('success', "Producto {$action} exitosamente.");
    }
}
