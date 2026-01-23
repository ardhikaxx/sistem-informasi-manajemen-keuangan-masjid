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
        
        // Validasi input secara kondisional
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'sometimes|string|max:255',
            'nomor_telfon' => 'sometimes|string|max:15',
            'pin_sekarang' => 'required_with:pin_baru|digits:4',
            'pin_baru' => 'nullable|digits:4|confirmed',
            'pin_baru_confirmation' => 'nullable|digits:4',
        ], [
            'nama_lengkap.string' => 'Nama lengkap harus berupa teks.',
            'nama_lengkap.max' => 'Nama lengkap maksimal 255 karakter.',
            'nomor_telfon.string' => 'Nomor telepon harus berupa teks.',
            'nomor_telfon.max' => 'Nomor telepon maksimal 15 karakter.',
            'pin_sekarang.required_with' => 'PIN saat ini wajib diisi jika ingin mengubah PIN.',
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

        // Ambil data admin untuk verifikasi
        $admin = DB::table('admins')->where('id', $adminId)->first();
        
        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Admin tidak ditemukan.'
            ], 404);
        }

        // Cek apakah ada perubahan data
        $hasChanges = false;
        $updateData = ['updated_at' => now()];

        // Update nama lengkap jika diisi dan berbeda
        if ($request->filled('nama_lengkap') && $admin->nama_lengkap !== $request->nama_lengkap) {
            $updateData['nama_lengkap'] = $request->nama_lengkap;
            $hasChanges = true;
        }

        // Update nomor telepon jika diisi dan berbeda
        if ($request->filled('nomor_telfon') && $admin->nomor_telfon !== $request->nomor_telfon) {
            $updateData['nomor_telfon'] = $request->nomor_telfon;
            $hasChanges = true;
        }

        // Update PIN jika diisi PIN baru
        if ($request->filled('pin_baru')) {
            // Verifikasi PIN saat ini
            if ($admin->pin !== $request->pin_sekarang) {
                return response()->json([
                    'success' => false,
                    'message' => 'PIN saat ini salah.',
                    'errors' => ['pin_sekarang' => ['PIN saat ini tidak valid.']]
                ], 422);
            }

            // Cek apakah PIN baru berbeda dengan PIN lama
            if ($admin->pin !== $request->pin_baru) {
                $updateData['pin'] = $request->pin_baru;
                $hasChanges = true;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'PIN baru harus berbeda dengan PIN saat ini.',
                    'errors' => ['pin_baru' => ['PIN baru harus berbeda dengan PIN saat ini.']]
                ], 422);
            }
        }

        // Jika tidak ada perubahan
        if (!$hasChanges) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada perubahan data.'
            ], 400);
        }

        try {
            // Update data admin
            $updated = DB::table('admins')
                ->where('id', $adminId)
                ->update($updateData);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profil berhasil diperbarui.',
                    'admin' => array_merge((array)$admin, $updateData)
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