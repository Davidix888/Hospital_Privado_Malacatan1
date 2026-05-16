<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(): View
    {
        return view('users.index', [
            'usuarios' => Usuario::with('rol')->orderByDesc('id_usuario')->get(),
        ]);
    }

    public function create(): View
    {
        return view('users.create', [
            'roles' => Rol::orderBy('nombre_rol')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombres' => ['required', 'string', 'max:120', 'regex:/^(?=.*\pL)[\pL\s\'\.-]+$/u'],
            'apellidos' => ['required', 'string', 'max:120', 'regex:/^(?=.*\pL)[\pL\s\'\.-]+$/u'],
            'correo' => ['required', 'email', 'max:150', 'unique:usuario,correo'],
            'id_rol' => ['required', 'integer', 'exists:rol,id_rol'],
            'contrasena' => ['required', 'string', 'min:6'],
            'activo' => ['nullable', 'boolean'],
        ], [
            'nombres.required' => 'Debes ingresar los nombres.',
            'nombres.regex' => 'Los nombres solo pueden contener letras.',
            'apellidos.required' => 'Debes ingresar los apellidos.',
            'apellidos.regex' => 'Los apellidos solo pueden contener letras.',
            'correo.required' => 'Debes ingresar el correo electrónico.',
            'correo.email' => 'El correo electrónico no tiene un formato válido.',
            'correo.unique' => 'El correo electrónico ya está registrado.',
            'id_rol.required' => 'Debes seleccionar un rol.',
            'id_rol.exists' => 'El rol seleccionado no es válido.',
            'contrasena.required' => 'Debes ingresar la contraseña temporal.',
            'contrasena.min' => 'La contraseña temporal debe tener al menos 6 caracteres.',
        ]);

        $usuario = new Usuario();
        $usuario->nombres = $data['nombres'];
        $usuario->apellidos = $data['apellidos'];
        $usuario->correo = mb_strtolower($data['correo']);
        $usuario->id_rol = $data['id_rol'];
        $usuario->contrasena = Hash::make($data['contrasena']);
        $usuario->activo = (bool) ($data['activo'] ?? true);
        $usuario->password_changed_at = now();
        $usuario->save();

        return redirect()->route('users.index')->with('status', 'Usuario creado. Username generado: '.$usuario->nombre_usuario);
    }

    public function edit(Usuario $usuario): View
    {
        return view('users.edit', [
            'usuario' => $usuario,
            'roles' => Rol::orderBy('nombre_rol')->get(),
        ]);
    }

    public function update(Request $request, Usuario $usuario): RedirectResponse
    {
        $data = $request->validate([
            'nombres' => ['required', 'string', 'max:120', 'regex:/^(?=.*\pL)[\pL\s\'\.-]+$/u'],
            'apellidos' => ['required', 'string', 'max:120', 'regex:/^(?=.*\pL)[\pL\s\'\.-]+$/u'],
            'correo' => ['required', 'email', 'max:150', 'unique:usuario,correo,'.$usuario->id_usuario.',id_usuario'],
            'id_rol' => ['required', 'integer', 'exists:rol,id_rol'],
            'activo' => ['nullable', 'boolean'],
            'nueva_contrasena' => ['nullable', 'string', 'min:6', 'confirmed'],
        ], [
            'nombres.required' => 'Debes ingresar los nombres.',
            'nombres.regex' => 'Los nombres solo pueden contener letras.',
            'apellidos.required' => 'Debes ingresar los apellidos.',
            'apellidos.regex' => 'Los apellidos solo pueden contener letras.',
            'correo.required' => 'Debes ingresar el correo electrónico.',
            'correo.email' => 'El correo electrónico no tiene un formato válido.',
            'correo.unique' => 'El correo electrónico ya está registrado.',
            'id_rol.required' => 'Debes seleccionar un rol.',
            'id_rol.exists' => 'El rol seleccionado no es válido.',
            'nueva_contrasena.min' => 'La nueva contraseña debe tener al menos 6 caracteres.',
            'nueva_contrasena.confirmed' => 'La confirmación de contraseña no coincide.',
        ]);

        $nameChanged =
            trim((string) $usuario->nombres) !== trim($data['nombres']) ||
            trim((string) $usuario->apellidos) !== trim($data['apellidos']);

        $usuario->nombres = $data['nombres'];
        $usuario->apellidos = $data['apellidos'];
        $usuario->correo = mb_strtolower($data['correo']);
        $usuario->id_rol = $data['id_rol'];
        $usuario->activo = (bool) ($data['activo'] ?? false);

        if ($nameChanged) {
            $usuario->nombre_usuario = Usuario::buildUniqueUsername($data['nombres'], $data['apellidos']);
        }

        if (!empty($data['nueva_contrasena'])) {
            $usuario->contrasena = Hash::make($data['nueva_contrasena']);
            $usuario->password_changed_at = now();
        }

        $usuario->save();

        return redirect()->route('users.index')->with('status', 'Usuario actualizado correctamente.');
    }

    public function toggle(Usuario $usuario): RedirectResponse
    {
        $usuario->activo = !$usuario->activo;
        $usuario->save();

        return back()->with('status', 'Estado de usuario actualizado.');
    }

    public function destroy(Usuario $usuario): RedirectResponse
    {
        if ((int) Session::get('auth_usuario_id') === (int) $usuario->id_usuario) {
            return back()->withErrors(['usuario' => 'No puedes eliminar tu propio usuario en sesión.']);
        }

        $usuario->delete();

        return back()->with('status', 'Usuario eliminado correctamente.');
    }
}
