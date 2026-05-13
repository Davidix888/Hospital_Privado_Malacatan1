<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('examen_laboratorio')) {
            return;
        }

        if (!Schema::hasColumn('examen_laboratorio', 'tipo_muestra')) {
            Schema::table('examen_laboratorio', function (Blueprint $table): void {
                $table->string('tipo_muestra', 120)->nullable()->after('costo');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('examen_laboratorio') && Schema::hasColumn('examen_laboratorio', 'tipo_muestra')) {
            Schema::table('examen_laboratorio', function (Blueprint $table): void {
                $table->dropColumn('tipo_muestra');
            });
        }
    }
};
