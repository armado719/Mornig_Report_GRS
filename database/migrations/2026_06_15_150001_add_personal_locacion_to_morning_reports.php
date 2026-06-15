<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('morning_reports', function (Blueprint $table) {
            $table->integer('personal_grs')->default(0)->after('estado');
            $table->integer('personal_ecopetrol')->default(0)->after('personal_grs');
            $table->integer('personal_flotantes')->default(0)->after('personal_ecopetrol');
        });
    }

    public function down(): void
    {
        Schema::table('morning_reports', function (Blueprint $table) {
            $table->dropColumn(['personal_grs', 'personal_ecopetrol', 'personal_flotantes']);
        });
    }
};
