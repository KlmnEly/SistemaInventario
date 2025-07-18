<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controller;

class userController extends Controller
{

    function __construct () {
        $this->middleware('permission:ver-user|crear-user|editar-user|eliminar-user', ['only' => 'index']);
        $this->middleware('permission:crear-user', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-user', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-user', ['only' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();

            // Encriptar contraseña
            $fieldHash = Hash::make($request->password);
            // Modificar el valor de password en nuestro request
            $request->merge(['password' => $fieldHash]);

            // Crear usuario
            $user = User::create($request->all());

            // Asignar su rol
            $user->assignRole($request->role);

            DB::commit();
        } catch(Exception $e) {
            DB::rollBack();
        }

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente');
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
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            DB::beginTransaction();

            // Comprobar el password y aplicar el Hash
            if (empty($request->password)) {
                $request = Arr::except($request,array('password'));
            } else {
                $fieldHash = Hash::make($request->password);
                $request->merge(['password' => $fieldHash]);
            }

            $user->update($request->all());

            // ACtualizar rol
            $user->syncRoles([$request->role]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        // Eliminar rol
        $rolUser = $user->getRoleNames()->first();
        $user->removeRole($rolUser);

        // Eliminar Usuario
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente');
    }
}
