<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Tampilkan halaman login
    public function index()
    {
        return view('login');
    }

    // Proses masuk (Login)
    public function authenticate(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);

        // Cek database & buat session
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/home'); // Ke halaman home setelah login
        }

        // Jika gagal, balik ke login dengan pesan error
        return back()->withErrors([
            'name' => 'Email atau password tidak sesuai dengan data kami.',
        ])->onlyInput('name');
    }

    // Keluar (Logout)
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}