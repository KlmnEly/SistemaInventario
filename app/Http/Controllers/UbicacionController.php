<?php

namespace App\Http\Controllers;

use App\Models\Ubicacione;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UbicacionController extends Controller
{

    function __construct () {
        $this->middleware('permission:ver-ubicacion|crear-ubicacion|editar-ubicacion|eliminar-ubicacion', ['only' => 'index']);
        $this->middleware('permission:crear-ubicacion', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-ubicacion', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-ubicacion', ['only' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ubicaciones = Ubicacione::all();
        return view('ubicaciones.index', [
            'ubicaciones' => $ubicaciones,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ubicaciones.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:ubicaciones,nombre',
            'descripcion' => 'nullable|string',
        ]);

        Ubicacione::create($request->all());
        return redirect()->route('ubicaciones.index')->with('success', 'Ubicacion creada exitosamente.');
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
    public function edit(Ubicacione $ubicacione)
    {
        return view('ubicaciones.edit', compact('ubicacione'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ubicacione $ubicacione)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:ubicaciones,nombre,' . $ubicacione->id,
            'descripcion' => 'nullable|string',
        ]);

        $ubicacione->update($request->all());
        return redirect()->route('ubicaciones.index')->with('success', 'Ubicacion actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ubicacion = ubicacione::find($id);

        if (!$ubicacion) {
            return redirect()->route('ubicaciones.index')->with('error', 'La condición especificada no fue encontrada.');
        }

        $ubicacion->estado = $ubicacion->estado ? 0 : 1;
        $ubicacion->save();

        $action = $ubicacion->estado == 0 ? 'eliminada' : 'restaurada';

        return redirect()->route('ubicaciones.index')->with('success', "Condicion {$action} exitosamente.");
    }
}
