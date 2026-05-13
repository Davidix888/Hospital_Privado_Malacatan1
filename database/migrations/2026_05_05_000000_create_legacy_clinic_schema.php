<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('usuario')) {
            return;
        }

        $schemaPath = database_path('schema_missing_tables.sql');

        if (! file_exists($schemaPath)) {
            throw new RuntimeException("Schema file not found: {$schemaPath}");
        }

        $rawSql = file_get_contents($schemaPath);

        if ($rawSql === false) {
            throw new RuntimeException("Could not read schema file: {$schemaPath}");
        }

        // Remove psql meta-commands (e.g. \restrict and \unrestrict) that are not valid SQL.
        $cleanSql = preg_replace('/^\\\\.*$/m', '', $rawSql);
        $cleanSql = preg_replace('/^SET transaction_timeout = .*;$/m', '', $cleanSql);

        if (! is_string($cleanSql)) {
            throw new RuntimeException('Failed to clean SQL schema content.');
        }

        DB::unprepared($cleanSql);
    }

    public function down(): void
    {
        $tables = [
            'detalle_receta',
            'receta',
            'consulta',
            'cita',
            'detalle_venta',
            'venta_farmacia',
            'detalle_devolucion',
            'devolucion',
            'detalle_compra',
            'compra_abastecimiento',
            'lote',
            'medicamento',
            'categoria_medicamento',
            'proveedor',
            'medico',
            'usuario_modulo_permiso',
            'usuario_api_token',
            'usuario',
            'rol',
            'paciente',
        ];

        foreach ($tables as $table) {
            DB::statement("DROP TABLE IF EXISTS public.{$table} CASCADE");
        }
    }
};
