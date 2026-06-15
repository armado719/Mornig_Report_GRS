<?php

namespace App\Http\Controllers;

use App\Models\MorningReport;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = MorningReport::query();
        if ($user->rig && ! $user->esAdmin()) {
            $query->where('rig', $user->rig);
        }

        $hoy          = now()->toDateString();
        $inicioMes    = now()->startOfMonth()->toDateString();

        $reportesHoy      = (clone $query)->whereDate('fecha', $hoy)->count();
        $completadosMes   = (clone $query)->where('estado', 'COMPLETADO')->where('fecha', '>=', $inicioMes)->count();
        $borradores       = (clone $query)->where('estado', 'BORRADOR')->count();
        $totalReportes    = (clone $query)->count();
        $ultimosReportes  = (clone $query)->with('creadoPor')->orderByDesc('fecha')->orderByDesc('created_at')->limit(5)->get();

        return view('dashboard', compact(
            'reportesHoy', 'completadosMes', 'borradores', 'totalReportes', 'ultimosReportes'
        ));
    }
}
