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

    // ── Paso 4 — BHA + Broca ──────────────────────────────────────────
    public string  $bha_numero    = '';
    public string  $bha_tamano    = '';
    public string  $bha_tipo      = '';
    public string  $bha_jets      = '';
    public string  $bha_serie     = '';
    public ?string $bha_total_bha = null;

    // ── Paso 4 — Inventario de tubería (dinámico) ─────────────────────
    public array $inventario = [];

    // ── Paso 4 — Top Drive ────────────────────────────────────────────
    public ?string $td_hrs_rotacion = null;
    public ?string $td_hrs_unidad   = null;
    public ?string $td_hrs_motor    = null;
    public ?string $td_acum_rotacion = null;

    // ── Paso 4 — Equipos en reparación (dinámico) ─────────────────────
    public array $equipos = [];

    // ── Paso 5 — Parámetros de perforación DIA ────────────────────────
    public ?string $par_dia_peso_sub      = null;
    public ?string $par_dia_peso_baj      = null;
    public ?string $par_dia_peso_rot      = null;
    public ?string $par_dia_presion_bomba = null;
    public ?string $par_dia_rpm           = null;
    public ?string $par_dia_torque        = null;
    public ?string $par_dia_wob           = null;
    public ?string $par_dia_spm           = null;
    public ?string $par_dia_gpm           = null;
    public ?string $par_dia_rop           = null;

    // ── Paso 5 — Parámetros de perforación NOCHE ──────────────────────
    public ?string $par_noche_peso_sub      = null;
    public ?string $par_noche_peso_baj      = null;
    public ?string $par_noche_peso_rot      = null;
    public ?string $par_noche_presion_bomba = null;
    public ?string $par_noche_rpm           = null;
    public ?string $par_noche_torque        = null;
    public ?string $par_noche_wob           = null;
    public ?string $par_noche_spm           = null;
    public ?string $par_noche_gpm           = null;
    public ?string $par_noche_rop           = null;

    // ── Paso 5 — Horas rotación por tubería ───────────────────────────
    public ?string $hr_5dp   = null;
    public ?string $hr_5hwdp = null;
    public ?string $hr_6dc   = null;
    public ?string $hr_8dc   = null;
    public ?string $hr_jar   = null;
    public ?string $hr_monel = null;
    public ?string $hr_otro  = null;

    // ── Paso 5 — Personal en locación ─────────────────────────────────
    public int $personal_grs       = 0;
    public int $personal_ecopetrol = 0;
    public int $personal_flotantes = 0;

    // ── Paso 5 — Comentarios finales ──────────────────────────────────
    public string $com_faltantes = '';
    public string $com_npt       = '';
    public string $com_general   = '';

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
        $this->cargarPozos();

        $user = Auth::user();
        if ($user->rig) {
            $this->rig = $user->rig;
        }

        $this->fecha = now()->format('Y-m-d');
        $this->iniciarOperaciones();
        $this->iniciarBombas();
        $this->iniciarInventario();
        $this->iniciarEquipos();

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
        $this->resetErrorBag();

        try {
            $this->validarPasoActual();
        } catch (\Illuminate\Validation\ValidationException) {
            $this->dispatch('toast', type: 'error', message: 'Completa los campos requeridos antes de continuar.');
            $this->js("window.scrollTo({top:0,behavior:'smooth'})");
            return;
        }

        if ($this->getErrorBag()->isNotEmpty()) {
            $this->dispatch('toast', type: 'error', message: 'Completa los campos requeridos antes de continuar.');
            $this->js("window.scrollTo({top:0,behavior:'smooth'})");
            return;
        }

        try {
            $this->guardarBorrador();
        } catch (\Exception $e) {
            $this->addError('guardado', 'Error al guardar: ' . $e->getMessage());
            $this->dispatch('toast', type: 'error', message: 'Error al guardar: ' . $e->getMessage());
            $this->js("window.scrollTo({top:0,behavior:'smooth'})");
            return;
        }

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

    public function completarReporte(): void
    {
        $this->guardarBorrador();

        MorningReport::where('id', $this->reporteId)->update(['estado' => 'COMPLETADO']);

        session()->flash('flash.banner', 'Reporte completado y guardado exitosamente.');
        session()->flash('flash.bannerStyle', 'success');

        $this->redirect(route('reportes.ver', $this->reporteId), navigate: true);
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

    // ── Acciones paso 4 ───────────────────────────────────────────────

    public function addInventario(): void
    {
        $this->inventario[] = ['diametro' => '', 'torre' => 0, 'base_reparacion' => 0, 'locacion' => 0, 'total' => 0];
    }

    public function removeInventario(int $i): void
    {
        array_splice($this->inventario, $i, 1);
        $this->inventario = array_values($this->inventario);
    }

    public function updatedInventario($value, $key): void
    {
        $parts = explode('.', $key);
        $i     = (int) $parts[0];
        $field = $parts[1] ?? '';

        if (in_array($field, ['torre', 'base_reparacion', 'locacion'])) {
            $this->inventario[$i]['total'] =
                (int)($this->inventario[$i]['torre']          ?? 0) +
                (int)($this->inventario[$i]['base_reparacion'] ?? 0) +
                (int)($this->inventario[$i]['locacion']        ?? 0);
        }
    }

    public function addEquipo(): void
    {
        $this->equipos[] = ['equipo' => '', 'dias' => 0, 'motivo' => '', 'estado' => '', 'comentarios' => ''];
    }

    public function removeEquipo(int $i): void
    {
        array_splice($this->equipos, $i, 1);
        $this->equipos = array_values($this->equipos);
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
                'personal_grs'                   => $this->personal_grs,
                'personal_ecopetrol'             => $this->personal_ecopetrol,
                'personal_flotantes'             => $this->personal_flotantes,
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

            // Paso 5 — Parámetros perforación, horas tubería, comentarios
            if ($this->paso >= 5) {
                $turnosParams = [
                    'DIA'   => ['peso_sub' => $this->par_dia_peso_sub,   'peso_baj' => $this->par_dia_peso_baj,
                                'peso_rot' => $this->par_dia_peso_rot,   'presion_bomba_psi' => $this->par_dia_presion_bomba,
                                'rpm'      => $this->par_dia_rpm,        'torque' => $this->par_dia_torque,
                                'wob'      => $this->par_dia_wob,        'spm'    => $this->par_dia_spm,
                                'gpm'      => $this->par_dia_gpm,        'rop'    => $this->par_dia_rop],
                    'NOCHE' => ['peso_sub' => $this->par_noche_peso_sub, 'peso_baj' => $this->par_noche_peso_baj,
                                'peso_rot' => $this->par_noche_peso_rot, 'presion_bomba_psi' => $this->par_noche_presion_bomba,
                                'rpm'      => $this->par_noche_rpm,      'torque' => $this->par_noche_torque,
                                'wob'      => $this->par_noche_wob,      'spm'    => $this->par_noche_spm,
                                'gpm'      => $this->par_noche_gpm,      'rop'    => $this->par_noche_rop],
                ];

                foreach ($turnosParams as $turno => $vals) {
                    \App\Models\ParametrosPerforacion::updateOrCreate(
                        ['reporte_id' => $this->reporteId, 'turno' => $turno],
                        array_map(fn($v) => $v ?: null, $vals)
                    );
                }

                \App\Models\ComentarioReporte::updateOrCreate(
                    ['reporte_id' => $this->reporteId, 'tipo' => 'HORAS_ROTACION'],
                    [
                        'contenido'  => null,
                        'hrs_5dp'    => $this->hr_5dp    ?: null,
                        'hrs_5hwdp'  => $this->hr_5hwdp  ?: null,
                        'hrs_6dc'    => $this->hr_6dc    ?: null,
                        'hrs_8dc'    => $this->hr_8dc    ?: null,
                        'hrs_jar'    => $this->hr_jar    ?: null,
                        'hrs_monel'  => $this->hr_monel  ?: null,
                        'hrs_otro'   => $this->hr_otro   ?: null,
                    ]
                );

                foreach (['FALTANTES' => $this->com_faltantes, 'NPT' => $this->com_npt, 'GENERAL' => $this->com_general] as $tipo => $contenido) {
                    \App\Models\ComentarioReporte::updateOrCreate(
                        ['reporte_id' => $this->reporteId, 'tipo' => $tipo],
                        ['contenido' => $contenido ?: null]
                    );
                }
            }

            // Paso 4 — BHA, inventario, top drive, equipos reparación
            if ($this->paso >= 4) {
                \App\Models\BhaBroca::updateOrCreate(
                    ['reporte_id' => $this->reporteId],
                    [
                        'numero'    => $this->bha_numero    ?: null,
                        'tamano'    => $this->bha_tamano    ?: null,
                        'tipo'      => $this->bha_tipo      ?: null,
                        'jets'      => $this->bha_jets      ?: null,
                        'serie'     => $this->bha_serie     ?: null,
                        'total_bha' => $this->bha_total_bha ?: null,
                    ]
                );

                \App\Models\InventarioTuberia::where('reporte_id', $this->reporteId)->delete();
                foreach ($this->inventario as $inv) {
                    if (empty($inv['diametro'])) continue;
                    \App\Models\InventarioTuberia::create(['reporte_id' => $this->reporteId] + [
                        'diametro'         => $inv['diametro'],
                        'torre'            => (int)($inv['torre']           ?? 0),
                        'base_reparacion'  => (int)($inv['base_reparacion'] ?? 0),
                        'locacion'         => (int)($inv['locacion']        ?? 0),
                        'total'            => (int)($inv['total']           ?? 0),
                    ]);
                }

                \App\Models\TopDrive::updateOrCreate(
                    ['reporte_id' => $this->reporteId],
                    [
                        'hrs_rotacion'  => $this->td_hrs_rotacion  ?: null,
                        'hrs_unidad'    => $this->td_hrs_unidad    ?: null,
                        'hrs_motor'     => $this->td_hrs_motor     ?: null,
                        'acum_rotacion' => $this->td_acum_rotacion ?: null,
                    ]
                );

                \App\Models\EquipoReparacion::where('reporte_id', $this->reporteId)->delete();
                foreach ($this->equipos as $eq) {
                    if (empty($eq['equipo'])) continue;
                    \App\Models\EquipoReparacion::create(['reporte_id' => $this->reporteId] + [
                        'equipo'      => $eq['equipo'],
                        'dias'        => (int)($eq['dias']        ?? 0),
                        'motivo'      => $eq['motivo']      ?: null,
                        'estado'      => $eq['estado']      ?: null,
                        'comentarios' => $eq['comentarios'] ?: null,
                    ]);
                }
            }

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
        $this->dispatch('toast', type: 'success', message: 'Borrador guardado correctamente.');
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
            'numero'          => "Bomba $n",
            'camisa_diametro' => '',
            'profundidad_ft'  => '',
            'peso_lodo_ppg'   => '',
            'spm'             => '',
            'presion_psi'     => '',
        ], [1, 2, 3]);
    }

    private function iniciarInventario(): void
    {
        // Diámetros comunes de tubería en perforación
        $this->inventario = [
            ['diametro' => '5" DP NC-50',       'torre' => 0, 'base_reparacion' => 0, 'locacion' => 0, 'total' => 0],
            ['diametro' => '5" HWDP NC-50',      'torre' => 0, 'base_reparacion' => 0, 'locacion' => 0, 'total' => 0],
            ['diametro' => '6½" DC NC-50',       'torre' => 0, 'base_reparacion' => 0, 'locacion' => 0, 'total' => 0],
            ['diametro' => '8" DC NC-61',        'torre' => 0, 'base_reparacion' => 0, 'locacion' => 0, 'total' => 0],
        ];
    }

    private function iniciarEquipos(): void
    {
        $this->equipos = [];
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
        }
    }

    private function cargarReporte(): void
    {
        $r = MorningReport::with([
            'personal', 'operaciones', 'lodo', 'bombas',
            'cable', 'diesel', 'bhaBroca', 'inventarioTuberia',
            'topDrive', 'equiposReparacion', 'parametros', 'comentarios',
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

        // BHA + Broca
        if ($r->bhaBroca) {
            $this->bha_numero    = $r->bhaBroca->numero    ?? '';
            $this->bha_tamano    = $r->bhaBroca->tamano    ?? '';
            $this->bha_tipo      = $r->bhaBroca->tipo      ?? '';
            $this->bha_jets      = $r->bhaBroca->jets      ?? '';
            $this->bha_serie     = $r->bhaBroca->serie     ?? '';
            $this->bha_total_bha = $r->bhaBroca->total_bha;
        }

        // Inventario tubería
        if ($r->inventarioTuberia->isNotEmpty()) {
            $this->inventario = $r->inventarioTuberia->map(fn($inv) => [
                'diametro'        => $inv->diametro        ?? '',
                'torre'           => $inv->torre           ?? 0,
                'base_reparacion' => $inv->base_reparacion ?? 0,
                'locacion'        => $inv->locacion        ?? 0,
                'total'           => $inv->total           ?? 0,
            ])->toArray();
        }

        // Top Drive
        if ($r->topDrive) {
            $this->td_hrs_rotacion  = $r->topDrive->hrs_rotacion;
            $this->td_hrs_unidad    = $r->topDrive->hrs_unidad;
            $this->td_hrs_motor     = $r->topDrive->hrs_motor;
            $this->td_acum_rotacion = $r->topDrive->acum_rotacion;
        }

        // Equipos en reparación
        if ($r->equiposReparacion->isNotEmpty()) {
            $this->equipos = $r->equiposReparacion->map(fn($eq) => [
                'equipo'      => $eq->equipo      ?? '',
                'dias'        => $eq->dias        ?? 0,
                'motivo'      => $eq->motivo      ?? '',
                'estado'      => $eq->estado      ?? '',
                'comentarios' => $eq->comentarios ?? '',
            ])->toArray();
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

        // Personal en locación
        $this->personal_grs       = $r->personal_grs       ?? 0;
        $this->personal_ecopetrol = $r->personal_ecopetrol ?? 0;
        $this->personal_flotantes = $r->personal_flotantes ?? 0;

        // Parámetros de perforación
        foreach ($r->parametros as $p) {
            $prefix = strtolower($p->turno === 'DIA' ? 'par_dia' : 'par_noche');
            $this->{"{$prefix}_peso_sub"}      = $p->peso_subiendo;
            $this->{"{$prefix}_peso_baj"}      = $p->peso_bajando;
            $this->{"{$prefix}_peso_rot"}      = $p->peso_rotacion;
            $this->{"{$prefix}_presion_bomba"} = $p->presion_bomba_psi;
            $this->{"{$prefix}_rpm"}           = $p->rpm;
            $this->{"{$prefix}_torque"}        = $p->torque;
            $this->{"{$prefix}_wob"}           = $p->wob;
            $this->{"{$prefix}_spm"}           = $p->spm;
            $this->{"{$prefix}_gpm"}           = $p->gpm;
            $this->{"{$prefix}_rop"}           = $p->rop;
        }

        // Comentarios y horas tubería
        foreach ($r->comentarios as $c) {
            match ($c->tipo) {
                'FALTANTES'     => $this->com_faltantes = $c->contenido ?? '',
                'NPT'           => $this->com_npt       = $c->contenido ?? '',
                'GENERAL'       => $this->com_general   = $c->contenido ?? '',
                'HORAS_ROTACION' => (function () use ($c) {
                    $this->hr_5dp   = $c->hrs_5dp;
                    $this->hr_5hwdp = $c->hrs_5hwdp;
                    $this->hr_6dc   = $c->hrs_6dc;
                    $this->hr_8dc   = $c->hrs_8dc;
                    $this->hr_jar   = $c->hrs_jar;
                    $this->hr_monel = $c->hrs_monel;
                    $this->hr_otro  = $c->hrs_otro;
                })(),
                default => null,
            };
        }

        $this->cargarPozos();
    }

    // ── Render ────────────────────────────────────────────────────────
    public function render()
    {
        // Recalcular siempre en render para garantizar sincronía
        $this->calcularFtPerforados();
        $this->calcularDiesel();

        return view('livewire.wizard-reporte', [
            'codigos'     => self::CODIGOS,
            'totalHoras'  => $this->getTotalHorasProperty(),
        ]);
    }
}
