<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserPasswordPolicyTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
        $this->buildSchema();
    }

    private function buildSchema(): void
    {
        Schema::dropIfExists('usuario');
        Schema::dropIfExists('rol');

        Schema::create('rol', function (Blueprint $table): void {
            $table->increments('id_rol');
            $table->string('nombre_rol', 80);
        });

        Schema::create('usuario', function (Blueprint $table): void {
            $table->increments('id_usuario');
            $table->string('nombre_usuario', 80)->nullable();
            $table->string('correo', 150)->unique();
            $table->string('contrasena', 255);
            $table->unsignedInteger('id_rol');
            $table->string('nombres', 120);
            $table->string('apellidos', 120);
            $table->boolean('activo')->default(true);
            $table->timestamp('password_changed_at')->nullable();
        });
    }

    public function test_forgot_and_force_password_routes_are_disabled(): void
    {
        $this->get('/forgot-password')->assertStatus(404);
        $this->post('/forgot-password')->assertStatus(404);
        $this->get('/password/force-change')->assertStatus(404);
        $this->post('/password/force-change')->assertStatus(404);
    }

    public function test_non_admin_cannot_change_other_user_password(): void
    {
        DB::table('rol')->insert([
            ['id_rol' => 1, 'nombre_rol' => 'administrador'],
            ['id_rol' => 2, 'nombre_rol' => 'farmacia'],
        ]);

        $actorId = DB::table('usuario')->insertGetId([
            'nombre_usuario' => 'ffarma',
            'correo' => 'farmacia@example.com',
            'contrasena' => Hash::make('secret6'),
            'id_rol' => 2,
            'nombres' => 'Felipe',
            'apellidos' => 'Lopez',
            'activo' => true,
            'password_changed_at' => now(),
        ], 'id_usuario');

        $targetId = DB::table('usuario')->insertGetId([
            'nombre_usuario' => 'madmin',
            'correo' => 'target@example.com',
            'contrasena' => Hash::make('vieja66'),
            'id_rol' => 2,
            'nombres' => 'Mario',
            'apellidos' => 'Perez',
            'activo' => true,
            'password_changed_at' => now(),
        ], 'id_usuario');

        $response = $this->withSession([
            'auth_usuario_id' => $actorId,
            'auth_rol' => 'farmacia',
        ])->put(route('users.update', $targetId), [
            'nombres' => 'Mario',
            'apellidos' => 'Perez',
            'correo' => 'target@example.com',
            'id_rol' => 2,
            'activo' => 1,
            'nueva_contrasena' => 'nueva66',
            'nueva_contrasena_confirmation' => 'nueva66',
        ]);

        $response->assertStatus(403);

        $storedHash = (string) DB::table('usuario')->where('id_usuario', $targetId)->value('contrasena');
        $this->assertTrue(Hash::check('vieja66', $storedHash));
    }

    public function test_admin_can_change_other_user_password_from_edit_user_flow(): void
    {
        DB::table('rol')->insert([
            ['id_rol' => 1, 'nombre_rol' => 'administrador'],
            ['id_rol' => 2, 'nombre_rol' => 'farmacia'],
        ]);

        $adminId = DB::table('usuario')->insertGetId([
            'nombre_usuario' => 'admin',
            'correo' => 'admin@example.com',
            'contrasena' => Hash::make('admin66'),
            'id_rol' => 1,
            'nombres' => 'Ana',
            'apellidos' => 'Admin',
            'activo' => true,
            'password_changed_at' => now(),
        ], 'id_usuario');

        $targetId = DB::table('usuario')->insertGetId([
            'nombre_usuario' => 'mlopez',
            'correo' => 'mario@example.com',
            'contrasena' => Hash::make('vieja66'),
            'id_rol' => 2,
            'nombres' => 'Mario',
            'apellidos' => 'Lopez',
            'activo' => true,
            'password_changed_at' => now()->subMonth(),
        ], 'id_usuario');

        $response = $this->withSession([
            'auth_usuario_id' => $adminId,
            'auth_rol' => 'administrador',
        ])->put(route('users.update', $targetId), [
            'nombres' => 'Mario',
            'apellidos' => 'Lopez',
            'correo' => 'mario@example.com',
            'id_rol' => 2,
            'activo' => 1,
            'nueva_contrasena' => 'nueva66',
            'nueva_contrasena_confirmation' => 'nueva66',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('users.index'));

        $updated = DB::table('usuario')->where('id_usuario', $targetId)->first();
        $this->assertNotNull($updated);
        $this->assertTrue(Hash::check('nueva66', (string) $updated->contrasena));
    }
}
