<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class InventarioTuberia extends Model {
    protected $table = 'inventario_tuberia';
    protected $fillable = ['reporte_id','diametro','torre','base_reparacion','locacion','total'];
    public function reporte() { return $this->belongsTo(MorningReport::class, 'reporte_id'); }
}
