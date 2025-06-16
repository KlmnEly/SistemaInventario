<?php

namespace App\Http\Controllers;

use App\Models\Condicion;
use Illuminate\Http\Request;

class CondicionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $condiciones = Condicion::all();
        return view('condiciones.index', [
            'condiciones' => $condiciones,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('condiciones.create');
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

        Condicion::create($request->all());
        return redirect()->route('condiciones.index')->with('success', 'Condicion creada exitosamente.');
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
    public function edit(Condicion $condicion)
    {
        return view('condiciones.edit', [
            'condicion' => $condicion
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Condicion $condicion)
    {
        $request->validate([
        'nombre' => 'required|string|max:255|unique:categorias,nombre,' . $condicion->id,
        'descripcion' => 'nullable|string',
        ]);

        $condicion->update($request->all());
        return redirect()->route('condiciones.index')->with('success', 'Condicion actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Condicion $condicion)
    {
        $condicion->estado = $condicion->estado ? 0 : 1; // Cambia el estado de la condicion
        $condicion->save();

        $action = $condicion->estado == 0 ? 'eliminada' : 'restaurada';

        return redirect()->route('condiciones.index')->with('success', "Condicion {$action} exitosamente.");
    }
}
