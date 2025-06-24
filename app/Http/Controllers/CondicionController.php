<?php

namespace App\Http\Controllers;

use App\Models\Condicione;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CondicionController extends Controller
{

    function __construct () {
        $this->middleware('permission:ver-condicion|crear-condicion|editar-condicion|eliminar-condicion', ['only' => 'index']);
        $this->middleware('permission:crear-condicion', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-condicion', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-condicion', ['only' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $condiciones = Condicione::all();
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
            'nombre' => 'required|string|max:255|unique:condiciones,nombre',
            'descripcion' => 'nullable|string',
        ]);

        Condicione::create($request->all());
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
    public function edit(Condicione $condicione)
    {
        return view('condiciones.edit', compact('condicione'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Condicione $condicione)
    {
        $request->validate([
        'nombre' => 'required|string|max:255|unique:condiciones,nombre,' . $condicione->id,
        'descripcion' => 'nullable|string',
        ]);

        $condicione->update($request->all());
        return redirect()->route('condiciones.index')->with('success', 'Condicion actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $condicion = Condicione::find($id);

        if (!$condicion) {
            return redirect()->route('condiciones.index')->with('error', 'La condición especificada no fue encontrada.');
        }

        $condicion->estado = $condicion->estado ? 0 : 1;
        $condicion->save();

        $action = $condicion->estado == 0 ? 'eliminada' : 'restaurada';

        return redirect()->route('condiciones.index')->with('success', "Condicion {$action} exitosamente.");
    }
}
