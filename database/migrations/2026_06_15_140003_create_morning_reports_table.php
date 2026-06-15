<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('morning_reports', function (Blueprint $table) {
            $table->id();
            $table->string('rig');
            $table->string('pozo');
            $table->string('municipio')->nullable();
            $table->string('operador')->nullable();
            $table->date('fecha');
            $table->decimal('dias_spud', 8, 2)->nullable();
            $table->decimal('prof_programada_ft', 10, 2)->nullable();
            $table->decimal('prof_ayer_ft', 10, 2)->nullable();
            $table->decimal('prof_hoy_ft', 10, 2)->nullable();
            $table->decimal('ft_perforados', 10, 2)->nullable();
            $table->string('operacion_actual')->nullable();
            $table->decimal('hrs_rotacion', 8, 2)->nullable();
            $table->decimal('horas_acum_rotacion', 10, 2)->nullable();
            $table->date('prueba_preventoras_fecha')->nullable();
            $table->text('prueba_preventoras_comentarios')->nullable();
            $table->foreignId('creado_por')->constrained('users');
            $table->enum('estado', ['BORRADOR', 'COMPLETADO'])->default('BORRADOR');
            $table->string('pdf_path')->nullable();
            $table->timestamps();

            $table->unique(['rig', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('morning_reports');
    }
};
