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
            if (!Schema::hasColumn('paciente_examen_laboratorio', 'id_usuario')) {
                $table->unsignedInteger('id_usuario')->nullable()->after('id_examen');
                $table->index('id_usuario');
                $table->foreign('id_usuario')->references('id_usuario')->on('usuario')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('paciente_examen_laboratorio')) {
            return;
        }

        Schema::table('paciente_examen_laboratorio', function (Blueprint $table): void {
            if (Schema::hasColumn('paciente_examen_laboratorio', 'id_usuario')) {
                $table->dropForeign(['id_usuario']);
                $table->dropIndex(['id_usuario']);
                $table->dropColumn('id_usuario');
            }
        });
    }
};

