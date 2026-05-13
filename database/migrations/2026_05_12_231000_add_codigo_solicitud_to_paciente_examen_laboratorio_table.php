<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('paciente_examen_laboratorio')) {
            return;
        }

        Schema::table('paciente_examen_laboratorio', function (Blueprint $table): void {
            if (!Schema::hasColumn('paciente_examen_laboratorio', 'codigo_solicitud')) {
                $table->string('codigo_solicitud', 40)->nullable()->after('id_examen');
                $table->index('codigo_solicitud');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('paciente_examen_laboratorio')) {
            return;
        }

        Schema::table('paciente_examen_laboratorio', function (Blueprint $table): void {
            if (Schema::hasColumn('paciente_examen_laboratorio', 'codigo_solicitud')) {
                $table->dropIndex(['codigo_solicitud']);
                $table->dropColumn('codigo_solicitud');
            }
        });
    }
};
