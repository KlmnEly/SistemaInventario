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
        // Validación de los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre', // 'nombre' es obligatorio, string, máx 255 y único en la tabla 'categorias'
            'descripcion' => 'nullable|string', // 'descripcion' es opcional y string
        ]);

        // Creamos la nueva categoría en la base de datos
        Categoria::create($request->all()); // Los datos validados se usan para crear el registro

        // Redireccionamos al usuario con un mensaje de éxito
        return redirect()->route('categorias.index')->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        return Categoria::findOrFail($categoria->id); // Devuelve la categoría encontrada o un error 404 si no existe
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', ['categoria' => $categoria]);
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
        // La restricción ahora se maneja a nivel de base de datos
        // gracias al onDelete('set null') o onDelete('cascade') en la migración.
        // $categoria->delete();

        // Redirecciona con un mensaje de éxito
        if ($categoria->estado == 1) {
            Categoria::where('id', $categoria->id)->update(['estado' => 0]);

            $message = 'Categoria eliminada exitosamente';
        } else {
            Categoria::where('id', $categoria->id)
            ->update([
                'estado' => 1
            ]);
            $message = 'Categoria restaurada exitosamente';
        }

        return redirect()->route('categorias.index')->with('success', $message);
    }
}
