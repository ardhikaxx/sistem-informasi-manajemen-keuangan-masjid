<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PengaturanProfilController extends Controller
{
    /**
     * Menampilkan halaman pengaturan profil
     */
    public function index()
    {
        // Ambil data admin dari session atau query langsung
        $admin = DB::table('admins')->where('id', Auth::guard('admin')->id())->first();
        return view('admins.pengaturan-profil.index', compact('admin'));
    }

    /**
     * Memperbarui profil admin
     */
    public function update(Request $request)
    {
        $adminId = Auth::guard('admin')->id();
        
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'nomor_telfon' => 'required|string|max:15',
            'pin_sekarang' => 'required|digits:4',
            'pin_baru' => 'nullable|digits:4|confirmed',
            'pin_baru_confirmation' => 'nullable|digits:4',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nomor_telfon.required' => 'Nomor telepon wajib diisi.',
            'pin_sekarang.required' => 'PIN saat ini wajib diisi.',
            'pin_sekarang.digits' => 'PIN harus terdiri dari 4 digit.',
            'pin_baru.digits' => 'PIN baru harus terdiri dari 4 digit.',
            'pin_baru.confirmed' => 'Konfirmasi PIN baru tidak cocok.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Ambil data admin untuk verifikasi PIN
        $admin = DB::table('admins')->where('id', $adminId)->first();
        
        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Admin tidak ditemukan.'
            ], 404);
        }

        // Verifikasi PIN saat ini
        if ($admin->pin !== $request->pin_sekarang) {
            return response()->json([
                'success' => false,
                'message' => 'PIN saat ini salah.',
                'errors' => ['pin_sekarang' => ['PIN saat ini tidak valid.']]
            ], 422);
        }

        try {
            // Data untuk update
            $updateData = [
                'nama_lengkap' => $request->nama_lengkap,
                'nomor_telfon' => $request->nomor_telfon,
                'updated_at' => now(),
            ];

            // Jika PIN baru diisi, update PIN
            if ($request->filled('pin_baru')) {
                $updateData['pin'] = $request->pin_baru;
            }

            // Update data admin menggunakan query builder
            $updated = DB::table('admins')
                ->where('id', $adminId)
                ->update($updateData);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profil berhasil diperbarui.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui profil.'
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui profil.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}