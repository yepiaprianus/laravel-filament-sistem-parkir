<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if (!$user->status_aktif) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'username' => 'Akun Anda dinonaktifkan. Silakan hubungi Administrator.',
                ]);
            }

            $request->session()->regenerate();

            LogAktivitas::catat($user->id_user, "User '{$user->username}' ({$user->role}) berhasil login.");

            return match ($user->role) {
                'admin' => redirect()->intended(route('admin.dashboard')),
                'petugas' => redirect()->intended(route('petugas.dashboard')),
                'owner' => redirect()->intended(route('owner.dashboard')),
                default => redirect()->route('dashboard'),
            };
        }

        return back()->withErrors([
            'username' => 'Kombinasi username atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            LogAktivitas::catat($user->id_user, "User '{$user->username}' logout.");
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}
