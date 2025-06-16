<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = Categoria::all();
        return view('categorias.index', [
            'categorias' => $categorias,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validación de los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre', // 'nombre' es obligatorio, string, máx 255 y único en la tabla 'categorias'
            'descripcion' => 'nullable|string', // 'descripcion' es opcional y string
        ]);

        // 2. Creamos la nueva categoría en la base de datos
        Categoria::create($request->all()); // Los datos validados se usan para crear el registro

        // Redireccionamos al usuario con un mensaje de éxito
        return redirect()->route('categorias.index')->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', [
            'categoria' => $categoria
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, Categoria $categoria)
    {
        // 1. Validación de los datos del formulario para la actualización
        $request->validate([
            // 'nombre' es obligatorio, string, máx 255.
            // 'unique:categorias,nombre,' . $categoria->id  asegura que el nombre sea único,
            // pero ignora el nombre de la categoría actual que se está editando.
            'nombre' => 'required|string|max:255|unique:categorias,nombre,' . $categoria->id,
            'descripcion' => 'nullable|string',
        ]);

        // 2. Actualiza la categoría en la base de datos
        $categoria->update($request->all()); // Los datos validados se usan para actualizar el registro

        // 3. Redirecciona al usuario con un mensaje de éxito
        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Elimina una categoría de la base de datos.
     *
     * @param  \App\Models\Categoria  $categoria  Laravel inyecta automáticamente la categoría a eliminar
     */
    public function destroy(Categoria $categoria)
    {
        $categoria->estado = $categoria->estado ? 0 : 1; // Cambia el estado de la categoria
        $categoria->save();

        $action = $categoria->estado == 0 ? 'eliminada' : 'restaurada';

        return redirect()->route('categorias.index')->with('success', "Categoria {$action} exitosamente.");
    }
}
