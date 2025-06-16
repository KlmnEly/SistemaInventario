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
        return view('tiposEvento.index', compact('tiposEvento'));
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
            'nombre' => 'required|string|max:255|unique:tipos_evento,nombre', // 'nombre' es obligatorio
            'descripcion' => 'nullable|string', // 'descripcion' es opcional
        ]);
    
        TipoEvento::create($request->all());
    
        return redirect()->route('tiposEvento.index')->with('success', 'Marca creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoEvento $tipoEvento)
    {
        return TipoEvento::findOrFail($tipoEvento->id); // Devuelve la marca encontrada o un error 404 si no existe
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoEvento $tipoEvento)
    {
        return view('tiposEvento.edit', ['tipoEvento' => $tipoEvento]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoEvento $tipoEvento)
    {
        $request -> validate([
            'nombre' => 'required|string|max:255|unique:tipos_evento,nombre,' . $tipoEvento->id, // 'nombre' es obligatorio, string, máx 255 y único en la tabla 'marcas', excepto el registro actual
            'descripcion' => 'nullable|string', // 'descripcion' es opcional y string
        ]);

        $tipoEvento->update($request->all());
        return redirect()->route('tiposEvento.index')->with('success', 'Tipo de evento actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoEvento $tipoEvento)
    {
        if ($tipoEvento->estado == 1) {
            TipoEvento::where('id', $tipoEvento->id)->update(['estado' => 0]);
            $message = 'Tipo de evento eliminado exitosamente';
        } else {
            TipoEvento::where('id', $tipoEvento->id)->update(['estado' => 1]);
            $message = 'Tipo de evento restaurado exitosamente';
        }
        return redirect()->route('tiposEvento.index')->with('success', $message);
    }
}
