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
            if (!Schema::hasColumn('paciente_examen_laboratorio', 'estado')) {
                $table->string('estado', 20)->default('ingresado')->after('id_examen');
                $table->index('estado');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('paciente_examen_laboratorio')) {
            return;
        }

        Schema::table('paciente_examen_laboratorio', function (Blueprint $table): void {
            if (Schema::hasColumn('paciente_examen_laboratorio', 'estado')) {
                $table->dropIndex(['estado']);
                $table->dropColumn('estado');
            }
        });
    }
};
