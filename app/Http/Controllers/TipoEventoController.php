<?php

namespace App\Http\Controllers;

use App\Models\TipoEvento;
use Illuminate\Http\Request;

class TipoEventoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tiposEvento = TipoEvento::all();
        return view('tiposEvento.index', [
            'tiposEvento' => $tiposEvento,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tiposEvento.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:tipos_evento,nombre',
            'descripcion' => 'nullable|string',
        ]);

        TipoEvento::create($request->all());
        return redirect()->route('tiposEvento.index')->with('success', 'Evento creado exitosamente.');
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
    public function edit(TipoEvento $tipoEvento)
    {
        return view('tiposEvento.edit', [
            'tipoEvento' => $tipoEvento
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoEvento $tipoEvento)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:tipos_evento,nombre,' . $tipoEvento->id,
            'descripcion' => 'nullable|string',
        ]);

        $tipoEvento->update($request->all());
        return redirect()->route('tiposEvento.index')->with('success', 'Evento actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoEvento $tipoEvento)
    {
        $tipoEvento->estado = $tipoEvento->estado ? 0 : 1; // Cambia el estado de la condicion
        $tipoEvento->save();

        $action = $tipoEvento->estado == 0 ? 'eliminado' : 'restaurado';

        return redirect()->route('tiposEvento.index')->with('success', "Evento {$action} exitosamente.");
    }
}
