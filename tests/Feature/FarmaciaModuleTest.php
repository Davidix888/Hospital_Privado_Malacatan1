<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class FarmaciaModuleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware();
        $this->buildSchema();
    }

    private function buildSchema(): void
    {
        Schema::dropIfExists('lote');
        Schema::dropIfExists('medicamento');
        Schema::dropIfExists('categoria_medicamento');

        Schema::create('categoria_medicamento', function (Blueprint $table): void {
            $table->increments('id_categoria');
            $table->string('nombre_categoria', 120);
        });

        Schema::create('medicamento', function (Blueprint $table): void {
            $table->increments('id_medicamento');
            $table->string('nombre', 180);
            $table->unsignedInteger('id_categoria')->nullable();
            $table->string('presentacion', 100)->nullable();
            $table->string('concentracion', 100)->nullable();
            $table->string('via_administracion', 80)->nullable();
            $table->string('unidad_medida', 40)->nullable();
            $table->string('codigo_interno', 30)->nullable();
            $table->string('descripcion', 400)->nullable();
            $table->unsignedTinyInteger('activo')->default(1);
        });

        Schema::create('lote', function (Blueprint $table): void {
            $table->increments('id_lote');
            $table->integer('stock')->default(0);
            $table->date('fecha_vencimiento')->nullable();
            $table->decimal('precio_venta', 10, 2)->nullable();
            $table->unsignedInteger('id_medicamento');
        });
    }

    public function test_store_medicamento_creates_record_and_internal_code(): void
    {
        $response = $this->post(route('farmacia.medicamentos.store'), [
            'nombre' => 'Paracetamol',
            'presentacion' => 'Tableta',
            'concentracion' => '500 mg',
            'via_administracion' => 'Oral',
            'descripcion' => 'Analgésico',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('status');

        $created = DB::table('medicamento')->where('nombre', 'Paracetamol')->first();
        $this->assertNotNull($created);
        $this->assertSame('500 mg', $created->concentracion);
        $this->assertSame('Oral', $created->via_administracion);
        $this->assertStringStartsWith('ID-', (string) $created->codigo_interno);
    }

    public function test_update_medicamento_updates_fields(): void
    {
        $id = DB::table('medicamento')->insertGetId([
            'nombre' => 'Ibuprofeno',
            'presentacion' => 'Tableta',
            'concentracion' => '400 mg',
            'via_administracion' => 'Oral',
            'codigo_interno' => 'ID-00001',
            'activo' => 1,
        ], 'id_medicamento');

        $response = $this->put(route('farmacia.medicamentos.update', $id), [
            'nombre' => 'Ibuprofeno Forte',
            'presentacion' => 'Cápsula',
            'concentracion' => '600 mg',
            'via_administracion' => 'oral',
            'descripcion' => 'Actualizado',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('status');

        $updated = DB::table('medicamento')->where('id_medicamento', $id)->first();
        $this->assertSame('Ibuprofeno Forte', $updated->nombre);
        $this->assertSame('Cápsula', $updated->presentacion);
        $this->assertSame('600 mg', $updated->concentracion);
        $this->assertSame('oral', $updated->via_administracion);
    }

    public function test_destroy_medicamento_is_blocked_if_it_has_lotes(): void
    {
        $id = DB::table('medicamento')->insertGetId([
            'nombre' => 'Amoxicilina',
            'codigo_interno' => 'ID-00002',
            'activo' => 1,
        ], 'id_medicamento');

        DB::table('lote')->insert([
            'stock' => 10,
            'id_medicamento' => $id,
            'precio_venta' => 12.50,
        ]);

        $response = $this->delete(route('farmacia.medicamentos.destroy', $id));
        $response->assertStatus(302);
        $response->assertSessionHasErrors('medicamento');

        $this->assertTrue(DB::table('medicamento')->where('id_medicamento', $id)->exists());
    }

    public function test_destroy_medicamento_deletes_when_it_has_no_lotes(): void
    {
        $id = DB::table('medicamento')->insertGetId([
            'nombre' => 'Loratadina',
            'codigo_interno' => 'ID-00003',
            'activo' => 1,
        ], 'id_medicamento');

        $response = $this->delete(route('farmacia.medicamentos.destroy', $id));
        $response->assertStatus(302);
        $response->assertSessionHas('status');

        $this->assertFalse(DB::table('medicamento')->where('id_medicamento', $id)->exists());
    }
}

