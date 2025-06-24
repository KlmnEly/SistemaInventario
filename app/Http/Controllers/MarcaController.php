<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MarcaController extends Controller
{

    function __construct () {
        $this->middleware('permission:ver-marca|crear-marca|editar-marca|eliminar-marca', ['only' => 'index']);
        $this->middleware('permission:crear-marca', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-marca', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-marca', ['only' => 'destroy']);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $marcas = Marca::all();
        return view('marcas.index', [
            'marcas' => $marcas,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('marcas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:marcas,nombre',
            'descripcion' => 'nullable|string',
        ]);

        Marca::create($request->all());
        return redirect()->route('marcas.index')->with('success', 'Marca creada exitosamente.');
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
    public function edit(Marca $marca)
    {
        return view('marcas.edit', [
            'marca' => $marca
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Marca $marca)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:marcas,nombre,' . $marca->id,
            'descripcion' => 'nullable|string',
        ]);

        $marca->update($request->all());
        return redirect()->route('marcas.index')->with('success', 'Marca actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Marca $marca)
    {
        $marca->estado = $marca->estado ? 0 : 1;
        $marca->save();

        $action = $marca->estado == 0 ? 'eliminada' : 'restaurada';

        return redirect()->route('marcas.index')->with('success', "Marca {$action} exitosamente.");
    }
}
