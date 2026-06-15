<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Rig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::with('roles')->orderBy('nombre')->paginate(25);
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Role::pluck('name', 'name')->toArray();
        $rigs  = Rig::where('activo', true)->pluck('numero', 'numero')->toArray();
        return view('admin.usuarios.create', compact('roles', 'rigs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'   => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => ['required', Rules\Password::min(8)],
            'rig'      => 'nullable|string',
            'rol'      => 'required|string|exists:roles,name',
            'activo'   => 'boolean',
        ], [
            'nombre.required'   => 'El nombre es obligatorio.',
            'email.required'    => 'El email es obligatorio.',
            'email.unique'      => 'Este email ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'rol.required'      => 'Selecciona un rol.',
        ]);

        $usuario = User::create([
            'nombre'   => $data['nombre'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'rig'      => $data['rig'] ?: null,
            'activo'   => $request->boolean('activo', true),
        ]);

        $usuario->assignRole($data['rol']);

        return redirect()->route('admin.usuarios.index')
            ->with('flash.banner', "Usuario «{$usuario->nombre}» creado exitosamente.")
            ->with('flash.bannerStyle', 'success');
    }

    public function show($id)
    {
        return redirect()->route('admin.usuarios.edit', $id);
    }

    public function edit($id)
    {
        $usuario = User::with('roles')->findOrFail($id);
        $roles   = Role::pluck('name', 'name')->toArray();
        $rigs    = Rig::where('activo', true)->pluck('numero', 'numero')->toArray();
        return view('admin.usuarios.edit', compact('usuario', 'roles', 'rigs'));
    }

    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $data = $request->validate([
            'nombre'   => 'required|string|max:255',
            'email'    => "required|email|unique:users,email,{$id}",
            'password' => ['nullable', Rules\Password::min(8)],
            'rig'      => 'nullable|string',
            'rol'      => 'required|string|exists:roles,name',
            'activo'   => 'boolean',
        ]);

        $usuario->update([
            'nombre' => $data['nombre'],
            'email'  => $data['email'],
            'rig'    => $data['rig'] ?: null,
            'activo' => $request->boolean('activo', true),
        ]);

        if (!empty($data['password'])) {
            $usuario->update(['password' => Hash::make($data['password'])]);
        }

        $usuario->syncRoles([$data['rol']]);

        return redirect()->route('admin.usuarios.index')
            ->with('flash.banner', "Usuario «{$usuario->nombre}» actualizado.")
            ->with('flash.bannerStyle', 'success');
    }

    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->update(['activo' => false]);

        return redirect()->route('admin.usuarios.index')
            ->with('flash.banner', "Usuario «{$usuario->nombre}» desactivado.")
            ->with('flash.bannerStyle', 'success');
    }
}
