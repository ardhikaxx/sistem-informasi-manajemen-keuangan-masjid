<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil transaksi terakhir untuk saldo saat ini
        $transaksiAkhir = Transaksi::latest('tanggal')->latest('id')->first();

        if ($transaksiAkhir) {
            $saldoSaatIni = $transaksiAkhir->saldo_sesudah;
        } else {
            $saldoSaatIni = 0;
        }

        // Hitung total keseluruhan untuk statistik
        $totalPemasukan = Transaksi::pemasukan()->sum('jumlah');
        $totalPengeluaran = Transaksi::pengeluaran()->sum('jumlah');

        // Hitung perubahan bulan lalu vs sekarang
        $pemasukanBulanIni = Transaksi::pemasukan()->bulanIni()->sum('jumlah');
        $pemasukanBulanLalu = Transaksi::pemasukan()
            ->whereBetween('tanggal', [
                Carbon::now()->subMonth()->startOfMonth(),
                Carbon::now()->subMonth()->endOfMonth()
            ])
            ->sum('jumlah');
        $perubahanPemasukan = $pemasukanBulanLalu > 0
            ? (($pemasukanBulanIni - $pemasukanBulanLalu) / $pemasukanBulanLalu) * 100
            : 100;

        $pengeluaranBulanIni = Transaksi::pengeluaran()->bulanIni()->sum('jumlah');
        $pengeluaranBulanLalu = Transaksi::pengeluaran()
            ->whereBetween('tanggal', [
                Carbon::now()->subMonth()->startOfMonth(),
                Carbon::now()->subMonth()->endOfMonth()
            ])
            ->sum('jumlah');
        $perubahanPengeluaran = $pengeluaranBulanLalu > 0
            ? (($pengeluaranBulanIni - $pengeluaranBulanLalu) / $pengeluaranBulanLalu) * 100
            : 100;

        // Data untuk grafik (6 bulan terakhir)
        $dataGrafik = $this->getDataGrafik(6);

        // Total untuk periode grafik
        $totalPemasukanGrafik = array_sum(array_column($dataGrafik, 'pemasukan'));
        $totalPengeluaranGrafik = array_sum(array_column($dataGrafik, 'pengeluaran'));

        return view('admins.dashboard.index', [
            'saldoSaatIni' => $saldoSaatIni, // Gunakan saldo dari transaksi terakhir
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'perubahanPemasukan' => abs($perubahanPemasukan),
            'perubahanPengeluaran' => abs($perubahanPengeluaran),
            'arahPemasukan' => $perubahanPemasukan >= 0 ? 'naik' : 'turun',
            'arahPengeluaran' => $perubahanPengeluaran >= 0 ? 'naik' : 'turun',
            'dataGrafik' => $dataGrafik,
            'totalPemasukanGrafik' => $totalPemasukanGrafik,
            'totalPengeluaranGrafik' => $totalPengeluaranGrafik
        ]);
    }

    public function getChartData(Request $request)
    {
        $periode = $request->input('periode', 6);

        $dataGrafik = $this->getDataGrafik($periode);

        return response()->json([
            'labels' => array_column($dataGrafik, 'label'),
            'datasets' => [
                [
                    'label' => 'Pemasukan',
                    'data' => array_column($dataGrafik, 'pemasukan'),
                    'backgroundColor' => 'rgba(40, 167, 69, 0.7)',
                    'borderColor' => 'rgba(40, 167, 69, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Pengeluaran',
                    'data' => array_column($dataGrafik, 'pengeluaran'),
                    'backgroundColor' => 'rgba(255, 193, 7, 0.7)',
                    'borderColor' => 'rgba(255, 193, 7, 1)',
                    'borderWidth' => 1,
                ]
            ]
        ]);
    }

    private function getDataGrafik($periode)
    {
        $dataGrafik = [];
        for ($i = $periode - 1; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $awalBulan = $bulan->copy()->startOfMonth();
            $akhirBulan = $bulan->copy()->endOfMonth();

            $pemasukan = Transaksi::pemasukan()
                ->whereBetween('tanggal', [$awalBulan, $akhirBulan])
                ->sum('jumlah');

            $pengeluaran = Transaksi::pengeluaran()
                ->whereBetween('tanggal', [$awalBulan, $akhirBulan])
                ->sum('jumlah');

            $dataGrafik[] = [
                'label' => $bulan->isoFormat('MMM YYYY'),
                'pemasukan' => (int)$pemasukan,
                'pengeluaran' => (int)$pengeluaran
            ];
        }

        return $dataGrafik;
    }
}