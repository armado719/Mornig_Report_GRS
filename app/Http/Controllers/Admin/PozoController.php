<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pozo;
use Illuminate\Http\Request;

class PozoController extends Controller
{
    public function index()
    {
        $pozos = Pozo::orderBy('nombre')->paginate(25);
        return view('admin.pozos.index', compact('pozos'));
    }

    public function create()
    {
        return view('admin.pozos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'       => 'required|string|max:100|unique:pozos,nombre',
            'operador'     => 'nullable|string|max:100',
            'municipio'    => 'nullable|string|max:100',
            'departamento' => 'nullable|string|max:100',
            'activo'       => 'boolean',
        ], [
            'nombre.required' => 'El nombre del pozo es obligatorio.',
            'nombre.unique'   => 'Ya existe un pozo con ese nombre.',
        ]);

        $pozo = Pozo::create([
            'nombre'       => $data['nombre'],
            'operador'     => $data['operador']     ?: null,
            'municipio'    => $data['municipio']    ?: null,
            'departamento' => $data['departamento'] ?: null,
            'activo'       => $request->boolean('activo', true),
        ]);

        return redirect()->route('admin.pozos.index')
            ->with('flash.banner', "Pozo «{$pozo->nombre}» creado exitosamente.")
            ->with('flash.bannerStyle', 'success');
    }

    public function show($id)
    {
        return redirect()->route('admin.pozos.edit', $id);
    }

    public function edit($id)
    {
        $pozo = Pozo::findOrFail($id);
        return view('admin.pozos.edit', compact('pozo'));
    }

    public function update(Request $request, $id)
    {
        $pozo = Pozo::findOrFail($id);

        $data = $request->validate([
            'nombre'       => "required|string|max:100|unique:pozos,nombre,{$id}",
            'operador'     => 'nullable|string|max:100',
            'municipio'    => 'nullable|string|max:100',
            'departamento' => 'nullable|string|max:100',
            'activo'       => 'boolean',
        ]);

        $pozo->update([
            'nombre'       => $data['nombre'],
            'operador'     => $data['operador']     ?: null,
            'municipio'    => $data['municipio']    ?: null,
            'departamento' => $data['departamento'] ?: null,
            'activo'       => $request->boolean('activo', true),
        ]);

        return redirect()->route('admin.pozos.index')
            ->with('flash.banner', "Pozo «{$pozo->nombre}» actualizado.")
            ->with('flash.bannerStyle', 'success');
    }

    public function destroy($id)
    {
        $pozo = Pozo::findOrFail($id);
        $pozo->update(['activo' => false]);

        return redirect()->route('admin.pozos.index')
            ->with('flash.banner', "Pozo «{$pozo->nombre}» desactivado.")
            ->with('flash.bannerStyle', 'success');
    }
}
