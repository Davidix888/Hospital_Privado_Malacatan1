<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paciente', function (Blueprint $table): void {
            if (!Schema::hasColumn('paciente', 'fecha_nacimiento')) {
                $table->date('fecha_nacimiento')->nullable();
            }
            if (!Schema::hasColumn('paciente', 'nit')) {
                $table->string('nit', 30)->nullable();
            }
            if (!Schema::hasColumn('paciente', 'genero')) {
                $table->string('genero', 20)->nullable();
            }
            if (!Schema::hasColumn('paciente', 'dpi')) {
                $table->string('dpi', 30)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('paciente', function (Blueprint $table): void {
            foreach (['fecha_nacimiento', 'nit', 'genero', 'dpi'] as $column) {
                if (Schema::hasColumn('paciente', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};