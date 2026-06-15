<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DieselReporte extends Model {
    protected $table = 'diesel_reporte';
    protected $fillable = ['reporte_id','recibido','ayer','hoy','usado','acumulado'];
    public function reporte() { return $this->belongsTo(MorningReport::class, 'reporte_id'); }
}
