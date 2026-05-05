<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class PasswordController extends Controller
{
    public function showForceChange(): View
    {
        $usuarioId = Session::get('auth_usuario_id');
        $usuario = Usuario::findOrFail($usuarioId);

        return view('auth.force-password', ['correo' => $usuario->correo]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'correo' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $usuarioId = Session::get('auth_usuario_id');
        $usuario = Usuario::findOrFail($usuarioId);

        if (mb_strtolower(trim((string) $usuario->correo)) !== mb_strtolower(trim($data['correo']))) {
            return back()->withErrors(['correo' => 'El correo no coincide con tu cuenta.'])->withInput();
        }

        $usuario->contrasena = Hash::make($data['password']);
        $usuario->password_changed_at = Carbon::now();
        $usuario->save();

        return redirect()->route('dashboard')->with('status', 'Contrasena actualizada correctamente.');
    }
}
