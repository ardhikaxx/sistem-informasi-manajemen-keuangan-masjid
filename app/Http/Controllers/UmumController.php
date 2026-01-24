<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Carbon\Carbon;

class UmumController extends Controller
{
    public function index()
    {
        $tahunSekarang = Carbon::now()->year;

        // Ambil transaksi terakhir (berdasarkan ID atau Tanggal) untuk menentukan saldo akhir terbaru
        // Ini menggambarkan "saldo saat ini" berdasarkan catatan transaksi terakhir
        $transaksiAkhir = Transaksi::latest('tanggal')->latest('id')->first();

        if ($transaksiAkhir) {
            // Gunakan saldo_sesudah dari transaksi terakhir sebagai saldo saat ini
            $saldoSaatIni = $transaksiAkhir->saldo_sesudah;
            $tanggalUpdate = $transaksiAkhir->tanggal->translatedFormat('d F Y');
        } else {
            // Jika tidak ada transaksi, saldo adalah 0
            $saldoSaatIni = 0;
            $tanggalUpdate = Carbon::now()->translatedFormat('d F Y');
        }

        // Hitung total pemasukan dan pengeluaran untuk tahun ini
        $totalPemasukanTahunIni = Transaksi::pemasukan()
            ->whereYear('tanggal', $tahunSekarang)
            ->sum('jumlah');

        $totalPengeluaranTahunIni = Transaksi::pengeluaran()
            ->whereYear('tanggal', $tahunSekarang)
            ->sum('jumlah');

        $data = [
            'saldoSaatIni' => $saldoSaatIni,
            'totalPemasukanTahunIni' => $totalPemasukanTahunIni,
            'totalPengeluaranTahunIni' => $totalPengeluaranTahunIni,
            'tanggalUpdate' => $tanggalUpdate,
        ];

        return view('index', $data);
    }
}