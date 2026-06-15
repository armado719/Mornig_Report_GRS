<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class BhaBroca extends Model {
    protected $table = 'bha_broca';
    protected $fillable = ['reporte_id','numero','tamano','tipo','jets','serie','total_bha'];
    public function reporte() { return $this->belongsTo(MorningReport::class, 'reporte_id'); }
}
