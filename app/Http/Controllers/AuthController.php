<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Menangani proses login
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'login_as' => ['nullable', 'in:admin,guru,orang_tua,siswa'],
        ]);

        $credentials = [
            'email' => $validated['email'],
            'password' => $validated['password'],
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect berdasarkan role
            $user = Auth::user();
            $selectedRole = $validated['login_as'] ?? null;
            if ($selectedRole) {
                // Jika role user tidak sesuai pilihan, gagalkan login
                if (!$user->hasRole($selectedRole)) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return back()
                        ->withErrors(['login_as' => 'Role akun tidak sesuai dengan pilihan "Masuk sebagai".'])
                        ->withInput($request->only('email', 'login_as'));
                }
            }
            if ($user->hasRole('admin')) {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Menangani proses logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
