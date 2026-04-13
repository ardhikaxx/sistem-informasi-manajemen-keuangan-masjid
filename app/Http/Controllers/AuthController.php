<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admins;
use Illuminate\Support\Facades\Hash;

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
     * Show forgot PIN form
     */
    public function showForgotPin()
    {
        return view('auth.forgot-pin');
    }

    /**
     * Verify phone number
     */
    public function verifyPhone(Request $request)
    {
        $request->validate([
            'nomor_telfon' => 'required|string'
        ], [
            'nomor_telfon.required' => 'Nomor telepon wajib diisi'
        ]);

        $admin = Admins::where('nomor_telfon', $request->nomor_telfon)->first();

        if (!$admin) {
            return back()->withErrors(['nomor_telfon' => 'Nomor telepon tidak terdaftar dalam sistem']);
        }

        // Store phone number in session for next step
        $request->session()->put('reset_phone', $request->nomor_telfon);

        return redirect()->route('auth.reset-pin');
    }

    /**
     * Show reset PIN form
     */
    public function showResetPin()
    {
        $phone = session('reset_phone');
        
        if (!$phone) {
            return redirect()->route('auth.forgot-pin');
        }

        return view('auth.reset-pin', compact('phone'));
    }

    /**
     * Reset PIN
     */
    public function resetPin(Request $request)
    {
        $request->validate([
            'new_pin' => 'required|digits:4',
            'confirm_pin' => 'required|same:new_pin'
        ], [
            'new_pin.required' => 'PIN baru wajib diisi',
            'new_pin.digits' => 'PIN harus 4 digit angka',
            'confirm_pin.required' => 'Konfirmasi PIN wajib diisi',
            'confirm_pin.same' => 'PIN dan konfirmasi PIN tidak cocok'
        ]);

        $phone = session('reset_phone');
        
        if (!$phone) {
            return redirect()->route('auth.forgot-pin');
        }

        $admin = Admins::where('nomor_telfon', $phone)->first();

        if (!$admin) {
            return redirect()->route('auth.forgot-pin')->withErrors(['error' => 'Nomor telepon tidak ditemukan']);
        }

        // Update PIN
        $admin->pin = $request->new_pin;
        $admin->save();

        // Clear session
        $request->session()->forget('reset_phone');

        return redirect()->route('auth.login')->with('success', 'PIN berhasil diubah. Silakan login dengan PIN baru Anda.');
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
}