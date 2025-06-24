<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controller;

class roleController extends Controller
{

    function __construct () {
        $this->middleware('permission:ver-role|crear-role|editar-role|eliminar-role', ['only' => 'index']);
        $this->middleware('permission:crear-role', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-role', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-role', ['only' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permisos = Permission::all();
        return view('roles.create', compact('permisos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permission' => 'required'
        ]);

        try {
            DB::beginTransaction();
            //Crear rol
            $rol = Role::create(['name' => $request->name]);

            //Asignar permisos
            $rol->syncPermissions($request->permission);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }


        return redirect()->route('roles.index')->with('success', 'Rol registrado');
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
    public function edit(Role $role)
    {
        $permisos = Permission::all();
        return view('roles.edit', compact('role', 'permisos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' .$role->id,
            'permission' => 'required'
        ]);

        try {
            DB::beginTransaction();

            $role->name = $request->name;
            $role->save();

            // Actuyalizar permisos
            $role->syncPermissions($request->permission);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return redirect()->route('roles.index')->with('success', 'Rol actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Role::where('id',$id)->delete();

        return redirect()->route('roles.index')->with('success', 'Rol eliminado');
    }

}
