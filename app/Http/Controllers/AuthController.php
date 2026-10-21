<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use RuntimeException;

class AuthController extends Controller
{
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        if (app()->environment('production') && config('mail.default') === 'log') {
            report(new RuntimeException('Password reset email is disabled because the production mailer is configured to log.'));

            return back()->withErrors([
                'email' => 'La recuperación por correo no está disponible temporalmente. Contacta al consultorio.',
            ])->onlyInput('email');
        }

        try {
            $status = Password::sendResetLink($request->only('email'));
        } catch (\Throwable $exception) {
            report($exception);

            return back()->withErrors([
                'email' => 'No pudimos enviar el correo en este momento. Inténtalo más tarde o contacta al consultorio.',
            ])->onlyInput('email');
        }

        if (in_array($status, [Password::RESET_LINK_SENT, Password::INVALID_USER, Password::RESET_THROTTLED], true)) {
            return back()->with('status', 'Si el correo corresponde a una cuenta activa, recibirás un enlace para restablecer la contraseña.');
        }

        return back()->withErrors(['email' => 'No pudimos procesar la solicitud. Inténtalo más tarde.'])->onlyInput('email');
    }

    public function showResetForm(string $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password'], 'activo' => 1], $request->filled('remember'))) {
            
            $request->session()->regenerate();

            Cache::put('user-is-online-' . Auth::id(), true, now()->addMinutes(15));

            $user = Auth::user();

            switch ($user->role_id) {
                case 1:
                    return redirect()->route('dashboard.admin');
                case 2:
                    return redirect()->route('dashboard.asistente');
                case 3:
                    return redirect()->route('dashboard.paciente');
                default:
                    Auth::logout();
                    return redirect()->route('login')->withErrors(['username' => 'Rol no autorizado.']);
            }
        }

        return back()->withErrors([
            'username' => 'Las credenciales son incorrectas o la cuenta está desactivada.',
        ])->onlyInput('username');
    }   

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Cache::forget('user-is-online-' . Auth::id());
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}