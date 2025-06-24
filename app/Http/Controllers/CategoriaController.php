<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use Illuminate\Routing\Controller;

class CategoriaController extends Controller
{

    function __construct () {
        $this->middleware('permission:ver-categoria|crear-categoria|editar-categoria|eliminar-categoria', ['only' => 'index']);
        $this->middleware('permission:crear-categoria', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-categoria', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-categoria', ['only' => 'destroy']);
    }


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
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre',
            'descripcion' => 'nullable|string',
        ]);

        Categoria::create($request->all());

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
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre,' . $categoria->id,
            'descripcion' => 'nullable|string',
        ]);

        $categoria->update($request->all());

        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada exitosamente.');
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->estado = $categoria->estado ? 0 : 1;
        $categoria->save();

        $action = $categoria->estado == 0 ? 'eliminada' : 'restaurada';

        return redirect()->route('categorias.index')->with('success', "Categoria {$action} exitosamente.");
    }
}
