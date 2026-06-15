<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EquipoReparacion extends Model {
    protected $table = 'equipos_reparacion';
    protected $fillable = ['reporte_id','equipo','dias','motivo','estado','comentarios'];
    public function reporte() { return $this->belongsTo(MorningReport::class, 'reporte_id'); }
}
