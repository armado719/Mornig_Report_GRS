<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TopDrive extends Model {
    protected $table = 'top_drive';
    protected $fillable = ['reporte_id','hrs_rotacion','hrs_unidad','hrs_motor','acum_rotacion'];
    public function reporte() { return $this->belongsTo(MorningReport::class, 'reporte_id'); }
}
