<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // VALIDASI
        $request->validate([
            'email_pengguna' => 'required|email',
            'password_admin' => 'required',
        ]);

        // CARI USER
        $pengguna = Pengguna::where('email_pengguna', $request->email_pengguna)->first();

        if (!$pengguna) {
            return back()
                ->withErrors(['login' => 'Email tidak ditemukan'])
                ->withInput();
        }

        if ($pengguna->status !== 'aktif') {
            return back()
                ->withErrors(['login' => 'Akun Anda tidak aktif'])
                ->withInput();
        }

        if (!Hash::check($request->password_admin, $pengguna->password_admin)) {
            return back()
                ->withErrors(['login' => 'Password salah'])
                ->withInput();
        }

        // LOGIN BERHASIL
        Auth::login($pengguna);
        $request->session()->regenerate();

        return redirect()->route('pengguna.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.show');
    }
}
