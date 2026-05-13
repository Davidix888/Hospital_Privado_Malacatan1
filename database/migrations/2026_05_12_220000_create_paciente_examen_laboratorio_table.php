<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('paciente_examen_laboratorio')) {
            return;
        }

        Schema::create('paciente_examen_laboratorio', function (Blueprint $table): void {
            $table->id('id_paciente_examen');
            $table->unsignedBigInteger('id_paciente');
            $table->unsignedBigInteger('id_examen');
            $table->timestamps();

            $table->index(['id_paciente']);
            $table->index(['id_examen']);
            $table->foreign('id_paciente')->references('id_paciente')->on('paciente')->onDelete('cascade');
            $table->foreign('id_examen')->references('id_examen')->on('examen_laboratorio')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paciente_examen_laboratorio');
    }
};
