<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
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

        return redirect()->route('dashboard')->with('status', 'Contraseña actualizada correctamente.');
    }

    public function showForgotForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'correo' => ['required', 'email'],
        ]);

        $correo = mb_strtolower(trim($data['correo']));
        $usuario = Usuario::where('correo', $correo)->first();

        if (!$usuario) {
            return back()->withErrors(['correo' => 'No existe una cuenta con ese correo.'])->withInput();
        }

        $plainToken = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $correo],
            ['token' => Hash::make($plainToken), 'created_at' => now()]
        );

        $resetUrl = route('password.reset.form', ['token' => $plainToken, 'email' => $correo]);

        Mail::raw("Hola {$usuario->nombres},\n\nPara cambiar tu contraseña ingresa al siguiente enlace:\n{$resetUrl}\n\nSi no solicitaste este cambio, ignora este correo.", function ($message) use ($correo) {
            $message->to($correo)->subject('Cambio de contraseña - Hospital Privado Malacatán');
        });

        return back()->with('status', 'Te enviamos un correo con el enlace para cambiar tu contraseña.');
    }

    public function showResetForm(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function resetWithToken(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $email = mb_strtolower(trim($data['email']));
        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$record || !Hash::check($data['token'], $record->token)) {
            return back()->withErrors(['email' => 'El enlace no es válido o expiró.'])->withInput();
        }

        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            return back()->withErrors(['email' => 'El enlace ya expiró. Solicita uno nuevo.'])->withInput();
        }

        $usuario = Usuario::where('correo', $email)->first();

        if (!$usuario) {
            return back()->withErrors(['email' => 'No existe una cuenta con ese correo.'])->withInput();
        }

        $usuario->contrasena = Hash::make($data['password']);
        $usuario->password_changed_at = now();
        $usuario->save();

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return redirect()->route('login')->with('status', 'Contraseña restablecida correctamente.');
    }
}
