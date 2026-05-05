<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'usuario' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $usuarioInput = mb_strtolower(trim($credentials['usuario']));

        $usuario = Usuario::with('rol')
            ->whereRaw('LOWER(correo) = ?', [$usuarioInput])
            ->orWhereRaw('LOWER(nombre_usuario) = ?', [$usuarioInput])
            ->first();

        if (!$usuario || !$usuario->activo) {
            return back()->withErrors(['usuario' => 'Usuario no encontrado o inactivo.'])->onlyInput('usuario');
        }

        $plainMatch = hash_equals((string) $usuario->contrasena, $credentials['password']);
        $hashMatch = Hash::check($credentials['password'], (string) $usuario->contrasena);

        if (!$plainMatch && !$hashMatch) {
            return back()->withErrors(['usuario' => 'Credenciales incorrectas.'])->onlyInput('usuario');
        }

        if ($plainMatch && !$hashMatch) {
            $usuario->contrasena = Hash::make($credentials['password']);
            $usuario->save();
        }

        $request->session()->regenerate();
        Session::put('auth_usuario_id', $usuario->id_usuario);
        Session::put('auth_rol', Str::of((string) optional($usuario->rol)->nombre_rol)->lower()->ascii()->replace(' ', '')->value());

        if (
            !$usuario->password_changed_at ||
            Carbon::parse($usuario->password_changed_at)->addMonths(3)->isPast()
        ) {
            if ($request->boolean('remember')) {
                Cookie::queue('remembered_usuario', $credentials['usuario'], 60 * 24 * 30);
            } else {
                Cookie::queue(Cookie::forget('remembered_usuario'));
            }
            return redirect()->route('password.force');
        }

        if ($request->boolean('remember')) {
            Cookie::queue('remembered_usuario', $credentials['usuario'], 60 * 24 * 30);
        } else {
            Cookie::queue(Cookie::forget('remembered_usuario'));
        }

        return redirect()->route('dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
