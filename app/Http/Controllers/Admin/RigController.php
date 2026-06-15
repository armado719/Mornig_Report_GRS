<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rig;
use Illuminate\Http\Request;

class RigController extends Controller
{
    public function index()
    {
        $rigs = Rig::orderBy('numero')->paginate(25);
        return view('admin.rigs.index', compact('rigs'));
    }

    public function create()
    {
        return view('admin.rigs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'numero' => 'required|string|max:20|unique:rigs,numero',
            'nombre' => 'nullable|string|max:255',
            'activo' => 'boolean',
        ], [
            'numero.required' => 'El número de RIG es obligatorio.',
            'numero.unique'   => 'Este número de RIG ya existe.',
        ]);

        $rig = Rig::create([
            'numero' => $data['numero'],
            'nombre' => $data['nombre'] ?: null,
            'activo' => $request->boolean('activo', true),
        ]);

        return redirect()->route('admin.rigs.index')
            ->with('flash.banner', "RIG {$rig->numero} creado exitosamente.")
            ->with('flash.bannerStyle', 'success');
    }

    public function show($id)
    {
        return redirect()->route('admin.rigs.edit', $id);
    }

    public function edit($id)
    {
        $rig = Rig::findOrFail($id);
        return view('admin.rigs.edit', compact('rig'));
    }

    public function update(Request $request, $id)
    {
        $rig = Rig::findOrFail($id);

        $data = $request->validate([
            'numero' => "required|string|max:20|unique:rigs,numero,{$id}",
            'nombre' => 'nullable|string|max:255',
            'activo' => 'boolean',
        ]);

        $rig->update([
            'numero' => $data['numero'],
            'nombre' => $data['nombre'] ?: null,
            'activo' => $request->boolean('activo', true),
        ]);

        return redirect()->route('admin.rigs.index')
            ->with('flash.banner', "RIG {$rig->numero} actualizado.")
            ->with('flash.bannerStyle', 'success');
    }

    public function destroy($id)
    {
        $rig = Rig::findOrFail($id);
        $rig->update(['activo' => false]);

        return redirect()->route('admin.rigs.index')
            ->with('flash.banner', "RIG {$rig->numero} desactivado.")
            ->with('flash.bannerStyle', 'success');
    }
}
