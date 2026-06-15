<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\MorningReport;
use App\Models\PersonalReporte;
use App\Models\Rig;
use App\Models\Pozo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WizardReporte extends Component
{
    // ── Control del wizard ─────────────────────────────────────────────
    public int $paso = 1;
    public int $totalPasos = 5;
    public ?int $reporteId = null;
    public bool $guardando = false;
    public string $mensajeGuardado = '';

    // ── Paso 1 — Encabezado ────────────────────────────────────────────
    public string $rig = '';
    public string $pozo = '';
    public string $municipio = '';
    public string $operador = '';
    public string $fecha = '';
    public ?string $dias_spud = null;
    public ?string $prof_programada_ft = null;
    public ?string $prof_ayer_ft = null;
    public ?string $prof_hoy_ft = null;
    public ?string $ft_perforados = null;
    public string $operacion_actual = '';
    public ?string $hrs_rotacion = null;
    public ?string $horas_acum_rotacion = null;
    public string $prueba_preventoras_fecha = '';
    public string $prueba_preventoras_comentarios = '';

    // ── Paso 1 — Personal ──────────────────────────────────────────────
    public string $p_rig_manager = '';
    public string $p_dsm = '';
    public string $p_supervisor = '';
    public string $p_hseq = '';
    public int $p_dias_sin_lti = 0;
    public int $p_dias_sin_rwc = 0;

    // ── Catálogos ─────────────────────────────────────────────────────
    public array $rigs = [];
    public array $pozos = [];

    // ── Reglas de validación por paso ─────────────────────────────────
    protected function reglaPaso1(): array
    {
        return [
            'rig'              => 'required|string',
            'pozo'             => 'required|string',
            'fecha'            => 'required|date',
            'operacion_actual' => 'nullable|string|max:255',
            'prof_ayer_ft'     => 'nullable|numeric|min:0',
            'prof_hoy_ft'      => 'nullable|numeric|min:0',
            'hrs_rotacion'     => 'nullable|numeric|min:0|max:24',
        ];
    }

    protected function mensajesPaso1(): array
    {
        return [
            'rig.required'   => 'Selecciona un RIG.',
            'pozo.required'  => 'Selecciona un pozo.',
            'fecha.required' => 'La fecha es obligatoria.',
        ];
    }

    // ── Ciclo de vida ─────────────────────────────────────────────────
    public function mount(?int $id = null): void
    {
        // Cargar catálogos
        $this->rigs = Rig::where('activo', true)->pluck('numero', 'numero')->toArray();

        // Si el usuario es RIG_MANAGER, pre-seleccionar su RIG
        $user = Auth::user();
        if ($user->rig) {
            $this->rig = $user->rig;
            $this->cargarPozos();
        }

        $this->fecha = now()->format('Y-m-d');

        if ($id) {
            $this->reporteId = $id;
            $this->cargarReporte();
        }
    }

    // ── Watchers ──────────────────────────────────────────────────────

    public function updatedRig(): void
    {
        $this->pozo      = '';
        $this->municipio = '';
        $this->operador  = '';
        $this->cargarPozos();
    }

    public function updatedPozo(): void
    {
        $pozo = Pozo::where('nombre', $this->pozo)->first();
        if ($pozo) {
            $this->municipio = $pozo->municipio ?? '';
            $this->operador  = $pozo->operador  ?? '';
        }
    }

    public function updatedProfAyerFt(): void
    {
        $this->calcularFtPerforados();
    }

    public function updatedProfHoyFt(): void
    {
        $this->calcularFtPerforados();
    }

    // ── Acciones ──────────────────────────────────────────────────────

    public function siguientePaso(): void
    {
        $this->validarPasoActual();
        $this->guardarBorrador();
        $this->paso++;
    }

    public function anteriorPaso(): void
    {
        $this->guardarBorrador();
        $this->paso--;
    }

    public function irAPaso(int $paso): void
    {
        // Solo puede ir a pasos ya guardados
        if ($paso < $this->paso || $this->reporteId) {
            $this->guardarBorrador();
            $this->paso = $paso;
        }
    }

    public function guardarBorrador(): void
    {
        $this->guardando = true;

        DB::transaction(function () {
            $datos = [
                'rig'                              => $this->rig,
                'pozo'                             => $this->pozo,
                'municipio'                        => $this->municipio ?: null,
                'operador'                         => $this->operador ?: null,
                'fecha'                            => $this->fecha,
                'dias_spud'                        => $this->dias_spud ?: null,
                'prof_programada_ft'               => $this->prof_programada_ft ?: null,
                'prof_ayer_ft'                     => $this->prof_ayer_ft ?: null,
                'prof_hoy_ft'                      => $this->prof_hoy_ft ?: null,
                'ft_perforados'                    => $this->ft_perforados ?: null,
                'operacion_actual'                 => $this->operacion_actual ?: null,
                'hrs_rotacion'                     => $this->hrs_rotacion ?: null,
                'horas_acum_rotacion'              => $this->horas_acum_rotacion ?: null,
                'prueba_preventoras_fecha'         => $this->prueba_preventoras_fecha ?: null,
                'prueba_preventoras_comentarios'   => $this->prueba_preventoras_comentarios ?: null,
                'creado_por'                       => Auth::id(),
                'estado'                           => 'BORRADOR',
            ];

            if ($this->reporteId) {
                MorningReport::where('id', $this->reporteId)->update($datos);
            } else {
                $reporte           = MorningReport::create($datos);
                $this->reporteId   = $reporte->id;

                // Actualizar URL sin recargar
                $this->dispatch('reporte-creado', id: $reporte->id);
            }

            // Guardar personal
            PersonalReporte::updateOrCreate(
                ['reporte_id' => $this->reporteId],
                [
                    'rig_manager'   => $this->p_rig_manager ?: null,
                    'dsm'           => $this->p_dsm ?: null,
                    'supervisor'    => $this->p_supervisor ?: null,
                    'hseq'          => $this->p_hseq ?: null,
                    'dias_sin_lti'  => $this->p_dias_sin_lti,
                    'dias_sin_rwc'  => $this->p_dias_sin_rwc,
                ]
            );
        });

        $this->mensajeGuardado = 'Guardado ' . now()->format('H:i:s');
        $this->guardando = false;
    }

    // ── Helpers ───────────────────────────────────────────────────────

    private function cargarPozos(): void
    {
        $this->pozos = Pozo::where('activo', true)->pluck('nombre', 'nombre')->toArray();
    }

    private function calcularFtPerforados(): void
    {
        if (is_numeric($this->prof_hoy_ft) && is_numeric($this->prof_ayer_ft)) {
            $this->ft_perforados = (string) round(
                (float) $this->prof_hoy_ft - (float) $this->prof_ayer_ft, 2
            );
        }
    }

    private function validarPasoActual(): void
    {
        match ($this->paso) {
            1 => $this->validate($this->reglaPaso1(), $this->mensajesPaso1()),
            default => null,
        };
    }

    private function cargarReporte(): void
    {
        $r = MorningReport::with('personal')->findOrFail($this->reporteId);

        $this->rig                             = $r->rig ?? '';
        $this->pozo                            = $r->pozo ?? '';
        $this->municipio                       = $r->municipio ?? '';
        $this->operador                        = $r->operador ?? '';
        $this->fecha                           = $r->fecha?->format('Y-m-d') ?? now()->format('Y-m-d');
        $this->dias_spud                       = $r->dias_spud;
        $this->prof_programada_ft              = $r->prof_programada_ft;
        $this->prof_ayer_ft                    = $r->prof_ayer_ft;
        $this->prof_hoy_ft                     = $r->prof_hoy_ft;
        $this->ft_perforados                   = $r->ft_perforados;
        $this->operacion_actual                = $r->operacion_actual ?? '';
        $this->hrs_rotacion                    = $r->hrs_rotacion;
        $this->horas_acum_rotacion             = $r->horas_acum_rotacion;
        $this->prueba_preventoras_fecha        = $r->prueba_preventoras_fecha?->format('Y-m-d') ?? '';
        $this->prueba_preventoras_comentarios  = $r->prueba_preventoras_comentarios ?? '';

        if ($r->personal) {
            $this->p_rig_manager  = $r->personal->rig_manager ?? '';
            $this->p_dsm          = $r->personal->dsm ?? '';
            $this->p_supervisor   = $r->personal->supervisor ?? '';
            $this->p_hseq         = $r->personal->hseq ?? '';
            $this->p_dias_sin_lti = $r->personal->dias_sin_lti ?? 0;
            $this->p_dias_sin_rwc = $r->personal->dias_sin_rwc ?? 0;
        }

        $this->cargarPozos();
    }

    // ── Render ────────────────────────────────────────────────────────
    public function render()
    {
        return view('livewire.wizard-reporte');
    }
}
