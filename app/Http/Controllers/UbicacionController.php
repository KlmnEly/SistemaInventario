<?php

namespace App\Http\Controllers;

use App\Models\Ubicacion;
use Illuminate\Http\Request;

class UbicacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ubicaciones = Ubicacion::all();
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

        Ubicacion::create($request->all());
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
    public function edit(Ubicacion $ubicacion)
    {
        return view('ubicaciones.edit', [
            'ubicacion' => $ubicacion
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ubicacion $ubicacion)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:ubicaciones,nombre,' . $ubicacion->id,
            'descripcion' => 'nullable|string',
        ]);

        $ubicacion->update($request->all());
        return redirect()->route('ubicaciones.index')->with('success', 'Ubicacion actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ubicacion $ubicacion)
    {
        $ubicacion->estado = $ubicacion->estado ? 0 : 1; // Cambia el estado de la condicion
        $ubicacion->save();

        $action = $ubicacion->estado == 0 ? 'eliminada' : 'restaurada';

        return redirect()->route('ubicaciones.index')->with('success', "Ubicacion {$action} exitosamente.");
    }
}
