<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OperacionLog extends Model {
    protected $table = 'operaciones_log';
    protected $fillable = ['reporte_id','hora_desde','hora_hasta','horas','codigo','descripcion','turno','noche_hrs','dia_hrs','orden'];
    public function reporte() { return $this->belongsTo(MorningReport::class, 'reporte_id'); }
}
