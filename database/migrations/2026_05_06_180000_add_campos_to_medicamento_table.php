<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medicamento', function (Blueprint $table): void {
            if (!Schema::hasColumn('medicamento', 'presentacion')) {
                $table->string('presentacion', 100)->nullable();
            }
            if (!Schema::hasColumn('medicamento', 'concentracion')) {
                $table->string('concentracion', 100)->nullable();
            }
            if (!Schema::hasColumn('medicamento', 'via_administracion')) {
                $table->string('via_administracion', 80)->nullable();
            }
            if (!Schema::hasColumn('medicamento', 'unidad_medida')) {
                $table->string('unidad_medida', 40)->nullable();
            }
            if (!Schema::hasColumn('medicamento', 'codigo_interno')) {
                $table->string('codigo_interno', 40)->nullable();
            }
            if (!Schema::hasColumn('medicamento', 'descripcion')) {
                $table->string('descripcion', 400)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('medicamento', function (Blueprint $table): void {
            foreach (['presentacion', 'concentracion', 'via_administracion', 'unidad_medida', 'codigo_interno', 'descripcion'] as $column) {
                if (Schema::hasColumn('medicamento', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};