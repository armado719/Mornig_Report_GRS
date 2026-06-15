<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ParametrosPerforacion extends Model {
    protected $table = 'parametros_perforacion';
    protected $fillable = ['reporte_id','turno','peso_subiendo','peso_bajando','peso_rotacion','presion_bomba_psi','rpm','torque','wob','spm','gpm','rop'];
    public function reporte() { return $this->belongsTo(MorningReport::class, 'reporte_id'); }
}
