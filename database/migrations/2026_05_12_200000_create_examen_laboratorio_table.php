<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('examen_laboratorio')) {
            return;
        }

        Schema::create('examen_laboratorio', function (Blueprint $table): void {
            $table->id('id_examen');
            $table->string('codigo_examen', 20)->nullable()->unique();
            $table->string('nombre_examen', 180)->unique();
            $table->decimal('costo', 10, 2)->default(0);
            $table->string('tipo_muestra', 120)->nullable();
            $table->text('informacion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examen_laboratorio');
    }
};

