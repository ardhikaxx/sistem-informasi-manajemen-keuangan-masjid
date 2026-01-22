<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admins;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'pin' => 'required|digits:4'
        ], [
            'pin.required' => 'PIN wajib diisi',
            'pin.digits' => 'PIN harus 4 digit angka'
        ]);

        // Cari admin berdasarkan PIN
        $admin = Admins::where('pin', $request->pin)->first();

        if ($admin) {
            // Login berhasil
            Auth::login($admin, $request->remember ?? false);
            
            // Regenerate session
            $request->session()->regenerate();
            
            // Redirect ke dashboard
            return redirect()->route('admins.dashboard')
                ->with('success', 'Login berhasil! Selamat datang ' . $admin->nama_lengkap);
        }

        // Login gagal
        return back()
            ->withInput($request->only('pin'))
            ->withErrors([
                'pin' => 'PIN yang dimasukkan salah.',
            ]);
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('index')
            ->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Show dashboard
     */
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login');
        }
        
        $admin = Auth::user();
        return view('admins.dashboard.index', compact('admin'));
    }
}