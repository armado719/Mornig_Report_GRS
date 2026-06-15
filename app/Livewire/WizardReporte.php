<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MorningReport;
use App\Models\PersonalReporte;
use App\Models\OperacionLog;
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

    // ── Paso 2 — Cronología de operaciones ────────────────────────────
    public array $operaciones = [];

    // ── Paso 3 — Lodo ─────────────────────────────────────────────────
    public string $l_tipo       = '';
    public string $l_viscosidad = '';
    public string $l_geles      = '';
    public ?string $l_peso      = null;
    public ?string $l_pv        = null;
    public ?string $l_yp        = null;
    public ?string $l_torta     = null;
    public ?string $l_ph        = null;
    public ?string $l_cloruros  = null;
    public ?string $l_oil_pct   = null;
    public ?string $l_flu_loss  = null;
    public ?string $l_solidos   = null;
    public ?string $l_arena     = null;

    // ── Paso 3 — Bombas de lodo (3 fijas) ────────────────────────────
    public array $bombas = [];

    // ── Paso 3 — Cable de perforación ─────────────────────────────────
    public string  $c_diametro           = '';
    public ?string $c_ton_milla_acumulada = null;
    public ?string $c_ton_milla_dia       = null;
    public ?string $c_sobrante_ft         = null;
    public string  $c_comentarios         = '';

    // ── Paso 3 — Diesel (galones) ─────────────────────────────────────
    public ?string $d_recibido  = null;
    public ?string $d_ayer      = null;
    public ?string $d_hoy       = null;
    public ?string $d_usado     = null;
    public ?string $d_acumulado = null;

    // ── Catálogos ─────────────────────────────────────────────────────
    public array $rigs = [];
    public array $pozos = [];

    // Códigos de operación estándar API
    public const CODIGOS = [
        '1'  => 'Perforando',
        '2'  => 'Reaming / Lavando',
        '3'  => 'Circulando',
        '4'  => 'Conexión de tubería',
        '5'  => 'Entrando a pozo (RIH)',
        '6'  => 'Sacando de pozo (POOH)',
        '7'  => 'Preparando BHA',
        '8'  => 'Cambio de broca',
        '9'  => 'Registro eléctrico (LWD/MWD)',
        '10' => 'Cementación',
        '11' => 'Esperando cemento (WOC)',
        '12' => 'Bajando casing',
        '13' => 'Espera (WOW / instrucciones)',
        '14' => 'Reparación mecánica',
        '15' => 'Reparación eléctrica',
        '16' => 'NPT — Tiempo no productivo',
        '17' => 'Prueba de preventoras (BOP)',
        '18' => 'Pesca',
        '19' => 'Operaciones de lodo',
        '20' => 'Seguridad / HSE',
        '21' => 'Movimiento de materiales',
        '22' => 'Dirección / MWD / Survey',
        '23' => 'Otros',
        'A'  => 'A — Perforación especial',
        'B'  => 'B — Operación de completación',
        'C'  => 'C — Workover',
        'D'  => 'D — Pulling unit',
        'E'  => 'E — Servicio de pozo',
        'F'  => 'F — Abandono',
    ];

    // ── Validación por paso ────────────────────────────────────────────
    protected function reglaPaso1(): array
    {
        return [
            'rig'          => 'required|string',
            'pozo'         => 'required|string',
            'fecha'        => 'required|date',
            'prof_ayer_ft' => 'nullable|numeric|min:0',
            'prof_hoy_ft'  => 'nullable|numeric|min:0',
            'hrs_rotacion' => 'nullable|numeric|min:0|max:24',
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

    protected function reglaPaso2(): array
    {
        return [
            'operaciones.*.hora_desde'  => 'nullable|date_format:H:i',
            'operaciones.*.hora_hasta'  => 'nullable|date_format:H:i',
            'operaciones.*.codigo'      => 'nullable|string',
            'operaciones.*.descripcion' => 'nullable|string|max:500',
        ];
    }

    // ── Ciclo de vida ─────────────────────────────────────────────────
    public function mount(?int $id = null): void
    {
        $this->rigs = Rig::where('activo', true)->pluck('numero', 'numero')->toArray();

        $user = Auth::user();
        if ($user->rig) {
            $this->rig = $user->rig;
            $this->cargarPozos();
        }

        $this->fecha = now()->format('Y-m-d');
        $this->iniciarOperaciones();
        $this->iniciarBombas();

        if ($id) {
            $this->reporteId = $id;
            $this->cargarReporte();
        }
    }

    // ── Watchers ──────────────────────────────────────────────────────

    public function updatedRig(): void
    {
        $this->pozo = $this->municipio = $this->operador = '';
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

    public function updatedProfAyerFt(): void { $this->calcularFtPerforados(); }
    public function updatedProfHoyFt(): void  { $this->calcularFtPerforados(); }

    public function updatedDAyer(): void    { $this->calcularDiesel(); }
    public function updatedDRecibido(): void { $this->calcularDiesel(); }
    public function updatedDHoy(): void     { $this->calcularDiesel(); }

    // Recalcula horas de una fila cuando cambian los tiempos
    public function updatedOperaciones($value, $key): void
    {
        $parts = explode('.', $key);
        $index = (int) $parts[0];
        $field = $parts[1] ?? '';

        if (in_array($field, ['hora_desde', 'hora_hasta'])) {
            $this->calcularHorasFila($index);
            $this->autoDetectarTurno($index);
        }

        if ($field === 'turno') {
            $this->distribuirHorasTurno($index);
        }
    }

    // ── Acciones del wizard ───────────────────────────────────────────

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
        if ($paso < $this->paso || $this->reporteId) {
            $this->guardarBorrador();
            $this->paso = $paso;
        }
    }

    // ── Acciones paso 2 ───────────────────────────────────────────────

    public function addOperacion(): void
    {
        $this->operaciones[] = $this->filaVacia(count($this->operaciones));
    }

    public function removeOperacion(int $index): void
    {
        array_splice($this->operaciones, $index, 1);
        // Re-indexar para Livewire
        $this->operaciones = array_values($this->operaciones);
    }

    public function moverFila(int $index, string $direccion): void
    {
        $target = $direccion === 'up' ? $index - 1 : $index + 1;
        if ($target < 0 || $target >= count($this->operaciones)) {
            return;
        }
        [$this->operaciones[$index], $this->operaciones[$target]] =
            [$this->operaciones[$target], $this->operaciones[$index]];
        $this->operaciones = array_values($this->operaciones);
    }

    // Getter reactivo: total de horas ingresadas
    public function getTotalHorasProperty(): float
    {
        return collect($this->operaciones)->sum(fn($op) => (float) ($op['horas'] ?? 0));
    }

    // ── Guardado ──────────────────────────────────────────────────────

    public function guardarBorrador(): void
    {
        $this->guardando = true;

        DB::transaction(function () {
            $datosReporte = [
                'rig'                            => $this->rig,
                'pozo'                           => $this->pozo,
                'municipio'                      => $this->municipio ?: null,
                'operador'                       => $this->operador ?: null,
                'fecha'                          => $this->fecha,
                'dias_spud'                      => $this->dias_spud ?: null,
                'prof_programada_ft'             => $this->prof_programada_ft ?: null,
                'prof_ayer_ft'                   => $this->prof_ayer_ft ?: null,
                'prof_hoy_ft'                    => $this->prof_hoy_ft ?: null,
                'ft_perforados'                  => $this->ft_perforados ?: null,
                'operacion_actual'               => $this->operacion_actual ?: null,
                'hrs_rotacion'                   => $this->hrs_rotacion ?: null,
                'horas_acum_rotacion'            => $this->horas_acum_rotacion ?: null,
                'prueba_preventoras_fecha'       => $this->prueba_preventoras_fecha ?: null,
                'prueba_preventoras_comentarios' => $this->prueba_preventoras_comentarios ?: null,
                'creado_por'                     => Auth::id(),
                'estado'                         => 'BORRADOR',
            ];

            if ($this->reporteId) {
                MorningReport::where('id', $this->reporteId)->update($datosReporte);
            } else {
                $reporte         = MorningReport::create($datosReporte);
                $this->reporteId = $reporte->id;
                $this->dispatch('reporte-creado', id: $reporte->id);
            }

            PersonalReporte::updateOrCreate(
                ['reporte_id' => $this->reporteId],
                [
                    'rig_manager'  => $this->p_rig_manager ?: null,
                    'dsm'          => $this->p_dsm ?: null,
                    'supervisor'   => $this->p_supervisor ?: null,
                    'hseq'         => $this->p_hseq ?: null,
                    'dias_sin_lti' => $this->p_dias_sin_lti,
                    'dias_sin_rwc' => $this->p_dias_sin_rwc,
                ]
            );

            // Paso 3 — lodo, bombas, cable, diesel
            if ($this->paso >= 3) {
                \App\Models\LodoReporte::updateOrCreate(
                    ['reporte_id' => $this->reporteId],
                    [
                        'tipo'       => $this->l_tipo       ?: null,
                        'viscosidad' => $this->l_viscosidad ?: null,
                        'geles'      => $this->l_geles      ?: null,
                        'peso'       => $this->l_peso       ?: null,
                        'pv'         => $this->l_pv         ?: null,
                        'yp'         => $this->l_yp         ?: null,
                        'torta'      => $this->l_torta      ?: null,
                        'ph'         => $this->l_ph         ?: null,
                        'cloruros'   => $this->l_cloruros   ?: null,
                        'oil_pct'    => $this->l_oil_pct    ?: null,
                        'flu_loss'   => $this->l_flu_loss   ?: null,
                        'solidos'    => $this->l_solidos    ?: null,
                        'arena'      => $this->l_arena      ?: null,
                    ]
                );

                \App\Models\BombaLodo::where('reporte_id', $this->reporteId)->delete();
                foreach ($this->bombas as $bomba) {
                    if (empty($bomba['numero']) && empty($bomba['presion_psi'])) continue;
                    \App\Models\BombaLodo::create(['reporte_id' => $this->reporteId] + $bomba);
                }

                \App\Models\CablePerforacion::updateOrCreate(
                    ['reporte_id' => $this->reporteId],
                    [
                        'diametro'            => $this->c_diametro            ?: null,
                        'ton_milla_acumulada' => $this->c_ton_milla_acumulada ?: null,
                        'ton_milla_dia'       => $this->c_ton_milla_dia       ?: null,
                        'sobrante_ft'         => $this->c_sobrante_ft         ?: null,
                        'comentarios'         => $this->c_comentarios         ?: null,
                    ]
                );

                \App\Models\DieselReporte::updateOrCreate(
                    ['reporte_id' => $this->reporteId],
                    [
                        'recibido'  => $this->d_recibido  ?: null,
                        'ayer'      => $this->d_ayer      ?: null,
                        'hoy'       => $this->d_hoy       ?: null,
                        'usado'     => $this->d_usado     ?: null,
                        'acumulado' => $this->d_acumulado ?: null,
                    ]
                );
            }

            // Paso 2 — sincronizar operaciones
            if ($this->paso >= 2) {
                OperacionLog::where('reporte_id', $this->reporteId)->delete();
                foreach ($this->operaciones as $i => $op) {
                    if (empty($op['hora_desde']) && empty($op['descripcion'])) {
                        continue;
                    }
                    OperacionLog::create([
                        'reporte_id'  => $this->reporteId,
                        'hora_desde'  => $op['hora_desde'] ?: null,
                        'hora_hasta'  => $op['hora_hasta'] ?: null,
                        'horas'       => $op['horas'] ?: null,
                        'codigo'      => $op['codigo'] ?: null,
                        'descripcion' => $op['descripcion'] ?: null,
                        'turno'       => $op['turno'] ?: 'DIA',
                        'noche_hrs'   => $op['noche_hrs'] ?: null,
                        'dia_hrs'     => $op['dia_hrs'] ?: null,
                        'orden'       => $i,
                    ]);
                }
            }
        });

        $this->mensajeGuardado = 'Guardado ' . now()->format('H:i:s');
        $this->guardando = false;
    }

    // ── Helpers privados ──────────────────────────────────────────────

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

    private function calcularHorasFila(int $index): void
    {
        $desde = $this->operaciones[$index]['hora_desde'] ?? '';
        $hasta = $this->operaciones[$index]['hora_hasta'] ?? '';

        if (!$desde || !$hasta) {
            return;
        }

        [$hD, $mD] = array_map('intval', explode(':', $desde));
        [$hH, $mH] = array_map('intval', explode(':', $hasta));

        $minutos = ($hH * 60 + $mH) - ($hD * 60 + $mD);

        // Si cruza medianoche
        if ($minutos < 0) {
            $minutos += 1440;
        }

        $this->operaciones[$index]['horas'] = (string) round($minutos / 60, 2);
        $this->distribuirHorasTurno($index);
    }

    private function autoDetectarTurno(int $index): void
    {
        $desde = $this->operaciones[$index]['hora_desde'] ?? '';
        if (!$desde) {
            return;
        }
        [$h] = array_map('intval', explode(':', $desde));
        // NOCHE: 18:00 – 05:59 | DIA: 06:00 – 17:59
        $this->operaciones[$index]['turno'] = ($h >= 18 || $h < 6) ? 'NOCHE' : 'DIA';
        $this->distribuirHorasTurno($index);
    }

    private function distribuirHorasTurno(int $index): void
    {
        $horas = (float) ($this->operaciones[$index]['horas'] ?? 0);
        $turno = $this->operaciones[$index]['turno'] ?? 'DIA';

        $this->operaciones[$index]['noche_hrs'] = $turno === 'NOCHE' ? (string) $horas : '0';
        $this->operaciones[$index]['dia_hrs']   = $turno === 'DIA'   ? (string) $horas : '0';
    }

    private function calcularDiesel(): void
    {
        if (is_numeric($this->d_ayer) && is_numeric($this->d_recibido) && is_numeric($this->d_hoy)) {
            $this->d_usado = (string) round(
                (float)$this->d_ayer + (float)$this->d_recibido - (float)$this->d_hoy, 2
            );
        }
    }

    private function iniciarBombas(): void
    {
        $this->bombas = array_map(fn($n) => [
            'numero'         => "Bomba $n",
            'camisa_diametro'=> '',
            'profundidad_ft' => '',
            'peso_lodo_ppg'  => '',
            'spm'            => '',
            'presion_psi'    => '',
        ], [1, 2, 3]);
    }

    private function iniciarOperaciones(): void
    {
        // Empieza con 3 filas vacías
        for ($i = 0; $i < 3; $i++) {
            $this->operaciones[] = $this->filaVacia($i);
        }
    }

    private function filaVacia(int $orden): array
    {
        return [
            'hora_desde'  => '',
            'hora_hasta'  => '',
            'horas'       => '',
            'codigo'      => '',
            'descripcion' => '',
            'turno'       => 'DIA',
            'noche_hrs'   => '0',
            'dia_hrs'     => '0',
        ];
    }

    private function validarPasoActual(): void
    {
        match ($this->paso) {
            1 => $this->validate($this->reglaPaso1(), $this->mensajesPaso1()),
            2 => $this->validarTotalHoras(),
            default => null,
        };
    }

    private function validarTotalHoras(): void
    {
        if ($this->getTotalHorasProperty() > 24) {
            $this->addError('operaciones', 'El total de horas no puede superar 24h.');
            throw new \Livewire\Exceptions\PropertyNotFoundException('Total de horas inválido');
        }
    }

    private function cargarReporte(): void
    {
        $r = MorningReport::with([
            'personal', 'operaciones', 'lodo', 'bombas',
            'cable', 'diesel',
        ])->findOrFail($this->reporteId);

        $this->rig                            = $r->rig ?? '';
        $this->pozo                           = $r->pozo ?? '';
        $this->municipio                      = $r->municipio ?? '';
        $this->operador                       = $r->operador ?? '';
        $this->fecha                          = $r->fecha?->format('Y-m-d') ?? now()->format('Y-m-d');
        $this->dias_spud                      = $r->dias_spud;
        $this->prof_programada_ft             = $r->prof_programada_ft;
        $this->prof_ayer_ft                   = $r->prof_ayer_ft;
        $this->prof_hoy_ft                    = $r->prof_hoy_ft;
        $this->ft_perforados                  = $r->ft_perforados;
        $this->operacion_actual               = $r->operacion_actual ?? '';
        $this->hrs_rotacion                   = $r->hrs_rotacion;
        $this->horas_acum_rotacion            = $r->horas_acum_rotacion;
        $this->prueba_preventoras_fecha       = $r->prueba_preventoras_fecha?->format('Y-m-d') ?? '';
        $this->prueba_preventoras_comentarios = $r->prueba_preventoras_comentarios ?? '';

        if ($r->personal) {
            $this->p_rig_manager  = $r->personal->rig_manager ?? '';
            $this->p_dsm          = $r->personal->dsm ?? '';
            $this->p_supervisor   = $r->personal->supervisor ?? '';
            $this->p_hseq         = $r->personal->hseq ?? '';
            $this->p_dias_sin_lti = $r->personal->dias_sin_lti ?? 0;
            $this->p_dias_sin_rwc = $r->personal->dias_sin_rwc ?? 0;
        }

        // Lodo
        if ($r->lodo) {
            foreach (['tipo','viscosidad','geles','peso','pv','yp','torta','ph','cloruros','oil_pct','flu_loss','solidos','arena'] as $campo) {
                $this->{"l_$campo"} = $r->lodo->$campo ?? ($campo === 'tipo' || $campo === 'viscosidad' || $campo === 'geles' ? '' : null);
            }
        }

        // Bombas
        if ($r->bombas->isNotEmpty()) {
            $this->bombas = $r->bombas->map(fn($b) => [
                'numero'          => $b->numero ?? '',
                'camisa_diametro' => $b->camisa_diametro ?? '',
                'profundidad_ft'  => $b->profundidad_ft,
                'peso_lodo_ppg'   => $b->peso_lodo_ppg,
                'spm'             => $b->spm,
                'presion_psi'     => $b->presion_psi,
            ])->toArray();
        }

        // Cable
        if ($r->cable) {
            $this->c_diametro            = $r->cable->diametro ?? '';
            $this->c_ton_milla_acumulada = $r->cable->ton_milla_acumulada;
            $this->c_ton_milla_dia       = $r->cable->ton_milla_dia;
            $this->c_sobrante_ft         = $r->cable->sobrante_ft;
            $this->c_comentarios         = $r->cable->comentarios ?? '';
        }

        // Diesel
        if ($r->diesel) {
            $this->d_recibido  = $r->diesel->recibido;
            $this->d_ayer      = $r->diesel->ayer;
            $this->d_hoy       = $r->diesel->hoy;
            $this->d_usado     = $r->diesel->usado;
            $this->d_acumulado = $r->diesel->acumulado;
        }

        if ($r->operaciones->isNotEmpty()) {
            $this->operaciones = $r->operaciones->map(fn($op) => [
                'hora_desde'  => $op->hora_desde ?? '',
                'hora_hasta'  => $op->hora_hasta ?? '',
                'horas'       => $op->horas ?? '',
                'codigo'      => $op->codigo ?? '',
                'descripcion' => $op->descripcion ?? '',
                'turno'       => $op->turno ?? 'DIA',
                'noche_hrs'   => $op->noche_hrs ?? '0',
                'dia_hrs'     => $op->dia_hrs ?? '0',
            ])->toArray();
        }

        $this->cargarPozos();
    }

    // ── Render ────────────────────────────────────────────────────────
    public function render()
    {
        return view('livewire.wizard-reporte', [
            'codigos'     => self::CODIGOS,
            'totalHoras'  => $this->getTotalHorasProperty(),
        ]);
    }
}
