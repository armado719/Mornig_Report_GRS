<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CablePerforacion extends Model {
    protected $table = 'cable_perforacion';
    protected $fillable = ['reporte_id','diametro','ton_milla_acumulada','ton_milla_dia','sobrante_ft','comentarios'];
    public function reporte() { return $this->belongsTo(MorningReport::class, 'reporte_id'); }
}
