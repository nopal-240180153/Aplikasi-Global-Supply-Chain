<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    /**
     * Tampilkan halaman login admin
     */
    public function showLoginForm()
    {
        // Jika sudah login dan admin, langsung ke admin portal
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('admin.sync');
        }

        return view('admin.login');
    }

    /**
     * Proses login admin
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Cek apakah user adalah admin
            if (Auth::user()->is_admin) {
                $request->session()->regenerate();
                return redirect()->route('admin.sync');
            } else {
                // Jika bukan admin, logout dan beri pesan error
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'email' => 'Akun ini tidak memiliki hak akses administrator.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }

    /**
     * Tampilkan halaman registrasi admin
     */
    public function showRegisterForm()
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('admin.sync');
        }

        return view('admin.register');
    }

    /**
     * Proses registrasi admin
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.\App\Models\User::class],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'is_admin' => true,
        ]);

        Auth::login($user);

        return redirect()->route('admin.sync');
    }

    /**
     * Logout khusus admin (opsional, bisa pakai logout bawaan)
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login');
    }
}
