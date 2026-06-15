<?php

namespace App\Http\Controllers;

use App\Models\MorningReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = MorningReport::with('creadoPor')
            ->orderByDesc('fecha')
            ->orderByDesc('created_at');

        if ($user->rig && ! $user->esAdmin()) {
            $query->where('rig', $user->rig);
        }

        $reportes = $query->paginate(20);

        return view('reportes.index', compact('reportes'));
    }

    public function crear()
    {
        return view('reportes.wizard', ['id' => null]);
    }

    public function ver($id)
    {
        $reporte = MorningReport::with([
            'personal', 'lodo', 'bombas', 'operaciones',
            'cable', 'diesel', 'bhaBroca', 'inventarioTuberia',
            'topDrive', 'equiposReparacion', 'parametros', 'comentarios',
            'creadoPor',
        ])->findOrFail($id);

        $this->autorizarAcceso($reporte);

        return view('reportes.ver', compact('reporte'));
    }

    public function editar($id)
    {
        $reporte = MorningReport::findOrFail($id);
        $this->autorizarAcceso($reporte);

        return view('reportes.wizard', compact('id'));
    }

    public function pdf($id)
    {
        $reporte = MorningReport::with([
            'personal', 'lodo', 'bombas', 'operaciones',
            'cable', 'diesel', 'bhaBroca', 'inventarioTuberia',
            'topDrive', 'equiposReparacion', 'parametros', 'comentarios',
            'creadoPor',
        ])->findOrFail($id);

        $this->autorizarAcceso($reporte);

        $pdf = Pdf::loadView('reportes.pdf', compact('reporte'))
            ->setPaper('letter', 'portrait');

        return $pdf->stream("Reporte-{$reporte->rig}-{$reporte->fecha}.pdf");
    }

    public function excel($id)
    {
        $reporte = MorningReport::with([
            'personal', 'lodo', 'bombas', 'operaciones',
            'cable', 'diesel', 'bhaBroca', 'inventarioTuberia',
            'topDrive', 'equiposReparacion', 'parametros', 'comentarios',
        ])->findOrFail($id);

        $this->autorizarAcceso($reporte);

        return (new \App\Exports\ReporteExport($reporte))
            ->download("Reporte-{$reporte->rig}-{$reporte->fecha}.xlsx");
    }

    private function autorizarAcceso(MorningReport $reporte): void
    {
        $user = Auth::user();
        if ($user->esAdmin()) {
            return;
        }
        if ($user->rig && $reporte->rig !== $user->rig) {
            abort(403);
        }
    }
}
