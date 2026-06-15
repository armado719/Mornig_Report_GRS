<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

    public function crear()
    {
        return view('reportes.wizard');
    }

    public function ver($id)
    {
        return view('reportes.ver', compact('id'));
    }

    public function editar($id)
    {
        return view('reportes.editar', compact('id'));
    }

    public function pdf($id)
    {
        // TODO: generar PDF con DomPDF
        abort(501, 'PDF en construcción');
    }

    public function excel($id)
    {
        // TODO: exportar Excel con Maatwebsite
        abort(501, 'Excel en construcción');
    }
}
