<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LodoReporte extends Model {
    protected $table = 'lodo_reporte';
    protected $fillable = ['reporte_id','tipo','viscosidad','peso','pv','yp','torta','ph','cloruros','oil_pct','flu_loss','solidos','arena','geles'];
    public function reporte() { return $this->belongsTo(MorningReport::class, 'reporte_id'); }
}
