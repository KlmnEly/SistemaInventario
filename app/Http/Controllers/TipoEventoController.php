<?php

namespace App\Http\Controllers;

use App\Models\TipoEvento;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TipoEventoController extends Controller
{

    function __construct () {
        $this->middleware('permission:ver-tipoEvento|crear-tipoEvento|editar-tipoEvento|eliminar-tipoEvento', ['only' => 'index']);
        $this->middleware('permission:crear-tipoEvento', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-tipoEvento', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-tipoEvento', ['only' => 'destroy']);
    }

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
    public function edit(TipoEvento $tiposEvento)
    {
        return view('tiposEvento.edit', [
            'tiposEvento' => $tiposEvento
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoEvento $tiposEvento)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:tipos_evento,nombre,' . $tiposEvento->id,
            'descripcion' => 'nullable|string',
        ]);

        $tiposEvento->update($request->all());
        return redirect()->route('tiposEvento.index')->with('success', 'Evento actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tipoEvento = TipoEvento::find($id);

        if (!$tipoEvento) {
            return redirect()->route('tiposEvento.index')->with('error', 'El tipo de evento especificado no fue encontrado.');
        }

        $tipoEvento->estado = $tipoEvento->estado ? 0 : 1;
        $tipoEvento->save();

        $action = $tipoEvento->estado == 0 ? 'eliminado' : 'restaurado';

        return redirect()->route('tiposEvento.index')->with('success', "Evento {$action} exitosamente.");
    }
}
