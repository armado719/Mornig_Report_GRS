<?php

namespace App\Exports;

use App\Models\MorningReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class ReporteExport implements WithMultipleSheets
{
    public function __construct(protected MorningReport $reporte) {}

    public function sheets(): array
    {
        return [
            new ReporteEncabezadoSheet($this->reporte),
            new ReporteOperacionesSheet($this->reporte),
            new ReporteTecnicosSheet($this->reporte),
        ];
    }
}

class ReporteEncabezadoSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    public function __construct(protected MorningReport $r) {}

    public function title(): string { return 'Encabezado'; }

    public function headings(): array
    {
        return ['Campo', 'Valor'];
    }

    public function collection(): Collection
    {
        $r = $this->r;
        $p = $r->personal;
        return collect([
            ['RIG',                   $r->rig],
            ['Pozo',                  $r->pozo],
            ['Municipio',             $r->municipio],
            ['Operador',              $r->operador],
            ['Fecha',                 $r->fecha?->format('d/m/Y')],
            ['Días SPUD',             $r->dias_spud],
            ['Prof. Programada (ft)', $r->prof_programada_ft],
            ['Prof. Ayer (ft)',       $r->prof_ayer_ft],
            ['Prof. Hoy (ft)',        $r->prof_hoy_ft],
            ['Ft. Perforados',        $r->ft_perforados],
            ['Operación Actual',      $r->operacion_actual],
            ['Horas Rotación',        $r->hrs_rotacion],
            ['Horas Acum. Rotación',  $r->horas_acum_rotacion],
            ['Prueba Preventoras',    $r->prueba_preventoras_fecha?->format('d/m/Y')],
            ['Rig Manager',           $p?->rig_manager],
            ['DSM',                   $p?->dsm],
            ['Supervisor',            $p?->supervisor],
            ['HSEQ',                  $p?->hseq],
            ['Días sin LTI',          $p?->dias_sin_lti],
            ['Días sin RWC',          $p?->dias_sin_rwc],
            ['Personal GRS',          $r->personal_grs],
            ['Personal Ecopetrol',    $r->personal_ecopetrol],
            ['Personal Flotantes',    $r->personal_flotantes],
            ['Estado',                $r->estado],
        ]);
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1B4D35']]],
        ];
    }

    public function columnWidths(): array
    {
        return ['A' => 28, 'B' => 40];
    }
}

class ReporteOperacionesSheet implements FromCollection, WithTitle, WithHeadings, WithStyles
{
    public function __construct(protected MorningReport $r) {}

    public function title(): string { return 'Cronología'; }

    public function headings(): array
    {
        return ['Desde', 'Hasta', 'Horas', 'Código', 'Descripción', 'Turno', 'Hrs Noche', 'Hrs Día'];
    }

    public function collection(): Collection
    {
        return $this->r->operaciones->map(fn($op) => [
            $op->hora_desde,
            $op->hora_hasta,
            $op->horas,
            $op->codigo,
            $op->descripcion,
            $op->turno,
            $op->noche_hrs,
            $op->dia_hrs,
        ]);
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1B4D35']]],
        ];
    }
}

class ReporteTecnicosSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    public function __construct(protected MorningReport $r) {}

    public function title(): string { return 'Datos Técnicos'; }

    public function headings(): array
    {
        return ['Sección', 'Campo', 'Valor', 'Unidad'];
    }

    public function collection(): Collection
    {
        $rows = collect();
        $lodo = $this->r->lodo;

        if ($lodo) {
            foreach ([
                ['Lodo', 'Tipo', $lodo->tipo, ''],
                ['Lodo', 'Peso', $lodo->peso, 'ppg'],
                ['Lodo', 'PV', $lodo->pv, 'cP'],
                ['Lodo', 'YP', $lodo->yp, 'lb/100ft²'],
                ['Lodo', 'pH', $lodo->ph, ''],
                ['Lodo', 'Viscosidad', $lodo->viscosidad, 'seg/qt'],
                ['Lodo', 'Geles', $lodo->geles, ''],
                ['Lodo', 'Sólidos', $lodo->solidos, '%'],
                ['Lodo', 'Oil %', $lodo->oil_pct, '%'],
                ['Lodo', 'Flu Loss', $lodo->flu_loss, 'cc'],
                ['Lodo', 'Cloruros', $lodo->cloruros, 'mg/L'],
                ['Lodo', 'Arena', $lodo->arena, '%'],
                ['Lodo', 'Torta', $lodo->torta, '32nds'],
            ] as $row) {
                $rows->push($row);
            }
        }

        $diesel = $this->r->diesel;
        if ($diesel) {
            foreach ([
                ['Diesel', 'Inventario Ayer', $diesel->ayer, 'gal'],
                ['Diesel', 'Recibido', $diesel->recibido, 'gal'],
                ['Diesel', 'Inventario Hoy', $diesel->hoy, 'gal'],
                ['Diesel', 'Usado', $diesel->usado, 'gal'],
                ['Diesel', 'Acumulado', $diesel->acumulado, 'gal'],
            ] as $row) {
                $rows->push($row);
            }
        }

        foreach ($this->r->bombas as $i => $b) {
            $n = $i + 1;
            $rows->push(["Bomba $n", 'Número', $b->numero, '']);
            $rows->push(["Bomba $n", 'Camisa', $b->camisa_diametro, '']);
            $rows->push(["Bomba $n", 'SPM', $b->spm, 'spm']);
            $rows->push(["Bomba $n", 'Presión', $b->presion_psi, 'PSI']);
        }

        $bha = $this->r->bhaBroca;
        if ($bha) {
            $rows->push(['BHA', 'No. Broca', $bha->numero, '']);
            $rows->push(['BHA', 'Tamaño', $bha->tamano, '']);
            $rows->push(['BHA', 'Tipo', $bha->tipo, '']);
            $rows->push(['BHA', 'Jets', $bha->jets, '']);
            $rows->push(['BHA', 'Total BHA', $bha->total_bha, 'ft']);
        }

        foreach ($this->r->parametros as $p) {
            $t = $p->turno;
            $rows->push(["Params $t", 'Peso Subiendo', $p->peso_subiendo, 'klbf']);
            $rows->push(["Params $t", 'Peso Bajando', $p->peso_bajando, 'klbf']);
            $rows->push(["Params $t", 'RPM', $p->rpm, 'rpm']);
            $rows->push(["Params $t", 'WOB', $p->wob, 'klbf']);
            $rows->push(["Params $t", 'SPM', $p->spm, 'spm']);
            $rows->push(["Params $t", 'GPM', $p->gpm, 'gal/m']);
            $rows->push(["Params $t", 'ROP', $p->rop, 'ft/h']);
            $rows->push(["Params $t", 'Torque', $p->torque, 'kft·lb']);
            $rows->push(["Params $t", 'Presión Bomba', $p->presion_bomba_psi, 'PSI']);
        }

        $comentariosMap = $this->r->comentarios->keyBy('tipo');
        foreach (['FALTANTES' => 'Faltantes', 'NPT' => 'NPT', 'GENERAL' => 'General'] as $tipo => $label) {
            if ($comentariosMap->has($tipo)) {
                $rows->push(['Comentarios', $label, $comentariosMap[$tipo]->contenido, '']);
            }
        }

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1B4D35']]],
        ];
    }

    public function columnWidths(): array
    {
        return ['A' => 18, 'B' => 24, 'C' => 30, 'D' => 14];
    }
}
