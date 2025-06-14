<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
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
        Request::validate([
            'nombre' => 'required|string|max:255|unique:marcas,nombre', // 'nombre' es obligatorio, string, máx 255 y único en la tabla 'marcas'
            'descripcion' => 'nullable|string', // 'descripcion' es opcional y string
        ]);

        Marca::create($request->all());
        return redirect()->route('marcas.index')->with('success', 'Marca creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Marca $marca)
    {
        return Marca::findOrFail($marca->id); // Devuelve la marca encontrada o un error 404 si no existe
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marca $marca)
    {
        return view('marcas.edit', ['marca' => $marca]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Marca $marca)
    {
        Request::validate([
            'nombre' => 'required|string|max:255|unique:marcas,nombre,' . $marca->id, // 'nombre' es obligatorio, string, máx 255 y único en la tabla 'marcas', excepto el registro actual
            'descripcion' => 'nullable|string', // 'descripcion' es opcional y string
        ]);

        $marca->update($request->all());
        return redirect()->route('marcas.index')->with('success', 'Marca actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Marca $marca)
    {
        if ($marca->estado == 1) {
            Marca::where('id', $marca->id)->update(['estado' => 0]);
            $message = 'Marca eliminada exitosamente';
        } else {
            Marca::where('id', $marca->id)->update(['estado' => 1]);
            $message = 'Marca restaurada exitosamente';
        }
        return redirect()->route('marcas.index')->with('success', $message);
    }
}
