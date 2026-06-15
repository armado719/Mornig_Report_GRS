<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MorningReport extends Model
{
    protected $table = 'morning_reports';

    protected $fillable = [
        'rig', 'pozo', 'municipio', 'operador', 'fecha',
        'dias_spud', 'prof_programada_ft', 'prof_ayer_ft', 'prof_hoy_ft',
        'ft_perforados', 'operacion_actual', 'hrs_rotacion', 'horas_acum_rotacion',
        'prueba_preventoras_fecha', 'prueba_preventoras_comentarios',
        'personal_grs', 'personal_ecopetrol', 'personal_flotantes',
        'creado_por', 'estado', 'pdf_path',
    ];

    protected function casts(): array
    {
        return [
            'fecha'                    => 'date',
            'prueba_preventoras_fecha' => 'date',
            'dias_spud'                => 'decimal:2',
            'prof_programada_ft'       => 'decimal:2',
            'prof_ayer_ft'             => 'decimal:2',
            'prof_hoy_ft'              => 'decimal:2',
            'ft_perforados'            => 'decimal:2',
            'hrs_rotacion'             => 'decimal:2',
            'horas_acum_rotacion'      => 'decimal:2',
        ];
    }

    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function personal(): HasOne
    {
        return $this->hasOne(PersonalReporte::class, 'reporte_id');
    }

    public function lodo(): HasOne
    {
        return $this->hasOne(LodoReporte::class, 'reporte_id');
    }

    public function bombas(): HasMany
    {
        return $this->hasMany(BombaLodo::class, 'reporte_id');
    }

    public function operaciones(): HasMany
    {
        return $this->hasMany(OperacionLog::class, 'reporte_id')->orderBy('orden');
    }

    public function cable(): HasOne
    {
        return $this->hasOne(CablePerforacion::class, 'reporte_id');
    }

    public function diesel(): HasOne
    {
        return $this->hasOne(DieselReporte::class, 'reporte_id');
    }

    public function topDrive(): HasOne
    {
        return $this->hasOne(TopDrive::class, 'reporte_id');
    }

    public function inventarioTuberia(): HasMany
    {
        return $this->hasMany(InventarioTuberia::class, 'reporte_id');
    }

    public function bhaBroca(): HasOne
    {
        return $this->hasOne(BhaBroca::class, 'reporte_id');
    }

    public function parametros(): HasMany
    {
        return $this->hasMany(ParametrosPerforacion::class, 'reporte_id');
    }

    public function equiposReparacion(): HasMany
    {
        return $this->hasMany(EquipoReparacion::class, 'reporte_id');
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(ComentarioReporte::class, 'reporte_id');
    }

    public function esBorrador(): bool
    {
        return $this->estado === 'BORRADOR';
    }

    public function esCompletado(): bool
    {
        return $this->estado === 'COMPLETADO';
    }
}
