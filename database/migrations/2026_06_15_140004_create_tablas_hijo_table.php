<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Personal del reporte
        Schema::create('personal_reporte', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporte_id')->constrained('morning_reports')->cascadeOnDelete();
            $table->string('rig_manager')->nullable();
            $table->string('dsm')->nullable();
            $table->string('supervisor')->nullable();
            $table->string('hseq')->nullable();
            $table->integer('dias_sin_lti')->default(0);
            $table->integer('dias_sin_rwc')->default(0);
            $table->timestamps();
        });

        // Información del lodo
        Schema::create('lodo_reporte', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporte_id')->constrained('morning_reports')->cascadeOnDelete();
            $table->string('tipo')->nullable();
            $table->string('viscosidad')->nullable();
            $table->decimal('peso', 8, 3)->nullable();
            $table->decimal('pv', 8, 2)->nullable();
            $table->decimal('yp', 8, 2)->nullable();
            $table->decimal('torta', 8, 2)->nullable();
            $table->decimal('ph', 5, 2)->nullable();
            $table->decimal('cloruros', 10, 2)->nullable();
            $table->decimal('oil_pct', 5, 2)->nullable();
            $table->decimal('flu_loss', 8, 2)->nullable();
            $table->decimal('solidos', 5, 2)->nullable();
            $table->decimal('arena', 5, 2)->nullable();
            $table->string('geles')->nullable();
            $table->timestamps();
        });

        // Bombas de lodo (hasta 3 por reporte)
        Schema::create('bombas_lodo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporte_id')->constrained('morning_reports')->cascadeOnDelete();
            $table->string('numero')->nullable();
            $table->string('camisa_diametro')->nullable();
            $table->decimal('profundidad_ft', 10, 2)->nullable();
            $table->decimal('peso_lodo_ppg', 8, 3)->nullable();
            $table->decimal('spm', 8, 2)->nullable();
            $table->decimal('presion_psi', 10, 2)->nullable();
            $table->timestamps();
        });

        // Cronología de operaciones
        Schema::create('operaciones_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporte_id')->constrained('morning_reports')->cascadeOnDelete();
            $table->string('hora_desde', 5)->nullable();
            $table->string('hora_hasta', 5)->nullable();
            $table->decimal('horas', 5, 2)->nullable();
            $table->string('codigo', 5)->nullable();
            $table->text('descripcion')->nullable();
            $table->enum('turno', ['NOCHE', 'DIA'])->nullable();
            $table->decimal('noche_hrs', 5, 2)->nullable();
            $table->decimal('dia_hrs', 5, 2)->nullable();
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        // Cable de perforación
        Schema::create('cable_perforacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporte_id')->constrained('morning_reports')->cascadeOnDelete();
            $table->string('diametro')->nullable();
            $table->decimal('ton_milla_acumulada', 10, 2)->nullable();
            $table->decimal('ton_milla_dia', 10, 2)->nullable();
            $table->decimal('sobrante_ft', 10, 2)->nullable();
            $table->text('comentarios')->nullable();
            $table->timestamps();
        });

        // Diesel
        Schema::create('diesel_reporte', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporte_id')->constrained('morning_reports')->cascadeOnDelete();
            $table->decimal('recibido', 10, 2)->nullable();
            $table->decimal('ayer', 10, 2)->nullable();
            $table->decimal('hoy', 10, 2)->nullable();
            $table->decimal('usado', 10, 2)->nullable();
            $table->decimal('acumulado', 10, 2)->nullable();
            $table->timestamps();
        });

        // Top Drive
        Schema::create('top_drive', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporte_id')->constrained('morning_reports')->cascadeOnDelete();
            $table->decimal('hrs_rotacion', 8, 2)->nullable();
            $table->decimal('hrs_unidad', 8, 2)->nullable();
            $table->decimal('hrs_motor', 8, 2)->nullable();
            $table->decimal('acum_rotacion', 10, 2)->nullable();
            $table->timestamps();
        });

        // Inventario de tubería
        Schema::create('inventario_tuberia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporte_id')->constrained('morning_reports')->cascadeOnDelete();
            $table->string('diametro');
            $table->integer('torre')->default(0);
            $table->integer('base_reparacion')->default(0);
            $table->integer('locacion')->default(0);
            $table->integer('total')->default(0);
            $table->timestamps();
        });

        // BHA + Broca
        Schema::create('bha_broca', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporte_id')->constrained('morning_reports')->cascadeOnDelete();
            $table->string('numero')->nullable();
            $table->string('tamano')->nullable();
            $table->string('tipo')->nullable();
            $table->string('jets')->nullable();
            $table->string('serie')->nullable();
            $table->decimal('total_bha', 10, 2)->nullable();
            $table->timestamps();
        });

        // Parámetros de perforación (turno DIA y NOCHE)
        Schema::create('parametros_perforacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporte_id')->constrained('morning_reports')->cascadeOnDelete();
            $table->enum('turno', ['DIA', 'NOCHE']);
            $table->decimal('peso_subiendo', 8, 2)->nullable();
            $table->decimal('peso_bajando', 8, 2)->nullable();
            $table->decimal('peso_rotacion', 8, 2)->nullable();
            $table->decimal('presion_bomba_psi', 10, 2)->nullable();
            $table->decimal('rpm', 8, 2)->nullable();
            $table->decimal('torque', 10, 2)->nullable();
            $table->decimal('wob', 8, 2)->nullable();
            $table->decimal('spm', 8, 2)->nullable();
            $table->decimal('gpm', 8, 2)->nullable();
            $table->decimal('rop', 8, 2)->nullable();
            $table->timestamps();
        });

        // Equipos en reparación
        Schema::create('equipos_reparacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporte_id')->constrained('morning_reports')->cascadeOnDelete();
            $table->string('equipo');
            $table->integer('dias')->default(0);
            $table->string('motivo')->nullable();
            $table->string('estado')->nullable();
            $table->text('comentarios')->nullable();
            $table->timestamps();
        });

        // Comentarios del reporte
        Schema::create('comentarios_reporte', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporte_id')->constrained('morning_reports')->cascadeOnDelete();
            $table->enum('tipo', ['FALTANTES', 'NPT', 'GENERAL', 'HORAS_ROTACION']);
            $table->text('contenido')->nullable();
            $table->decimal('hrs_5dp', 8, 2)->nullable();
            $table->decimal('hrs_5hwdp', 8, 2)->nullable();
            $table->decimal('hrs_6dc', 8, 2)->nullable();
            $table->decimal('hrs_8dc', 8, 2)->nullable();
            $table->decimal('hrs_jar', 8, 2)->nullable();
            $table->decimal('hrs_monel', 8, 2)->nullable();
            $table->decimal('hrs_otro', 8, 2)->nullable();
            $table->timestamps();
        });

        // Audit log
        Schema::create('audit_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('accion');
            $table->string('tabla');
            $table->unsignedBigInteger('registro_id');
            $table->json('valores_anteriores')->nullable();
            $table->json('valores_nuevos')->nullable();
            $table->string('ip')->nullable();
            $table->timestamp('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_log');
        Schema::dropIfExists('comentarios_reporte');
        Schema::dropIfExists('equipos_reparacion');
        Schema::dropIfExists('parametros_perforacion');
        Schema::dropIfExists('bha_broca');
        Schema::dropIfExists('inventario_tuberia');
        Schema::dropIfExists('top_drive');
        Schema::dropIfExists('diesel_reporte');
        Schema::dropIfExists('cable_perforacion');
        Schema::dropIfExists('operaciones_log');
        Schema::dropIfExists('bombas_lodo');
        Schema::dropIfExists('lodo_reporte');
        Schema::dropIfExists('personal_reporte');
    }
};
