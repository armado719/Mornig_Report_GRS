<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PersonalReporte extends Model {
    protected $table = 'personal_reporte';
    protected $fillable = ['reporte_id','rig_manager','dsm','supervisor','hseq','dias_sin_lti','dias_sin_rwc'];
    public function reporte() { return $this->belongsTo(MorningReport::class, 'reporte_id'); }
}
