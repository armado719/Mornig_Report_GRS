<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class BombaLodo extends Model {
    protected $table = 'bombas_lodo';
    protected $fillable = ['reporte_id','numero','camisa_diametro','profundidad_ft','peso_lodo_ppg','spm','presion_psi'];
    public function reporte() { return $this->belongsTo(MorningReport::class, 'reporte_id'); }
}
