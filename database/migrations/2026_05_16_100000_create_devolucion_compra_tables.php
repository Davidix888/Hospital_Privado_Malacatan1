<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('devolucion_compra')) {
            Schema::create('devolucion_compra', function (Blueprint $table): void {
                $table->increments('id_devolucion_compra');
                $table->date('fecha');
                $table->string('motivo', 180)->nullable();
                $table->unsignedInteger('id_compra_abastecimiento');
            });
        }

        if (!Schema::hasTable('detalle_devolucion_compra')) {
            Schema::create('detalle_devolucion_compra', function (Blueprint $table): void {
                $table->increments('id_detalle_devolucion_compra');
                $table->unsignedInteger('id_devolucion_compra');
                $table->unsignedInteger('id_lote');
                $table->integer('cantidad');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('detalle_devolucion_compra')) {
            Schema::drop('detalle_devolucion_compra');
        }
        if (Schema::hasTable('devolucion_compra')) {
            Schema::drop('devolucion_compra');
        }
    }
};

