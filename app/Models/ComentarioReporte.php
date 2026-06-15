<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ComentarioReporte extends Model {
    protected $table = 'comentarios_reporte';
    protected $fillable = ['reporte_id','tipo','contenido','hrs_5dp','hrs_5hwdp','hrs_6dc','hrs_8dc','hrs_jar','hrs_monel','hrs_otro'];
    public function reporte() { return $this->belongsTo(MorningReport::class, 'reporte_id'); }
}
