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

        // Data pie chart per aliran kas (bulan ini)
        $dataPieAliran = $this->getDataPieAliran();

        // Tren 12 bulan terakhir
        $tren12Bulan = $this->getDataGrafik(12);

        // Rata-rata pemasukan & pengeluaran
        $rataPemasukan = count($dataGrafik) > 0 ? $totalPemasukanGrafik / count($dataGrafik) : 0;
        $rataPengeluaran = count($dataGrafik) > 0 ? $totalPengeluaranGrafik / count($dataGrafik) : 0;

        // Transaksi terbanyak per uraian (top 5)
        $topTransaksi = $this->getTopTransaksi();

        return view('admins.dashboard.index', [
            'saldoSaatIni' => $saldoSaatIni,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'perubahanPemasukan' => abs($perubahanPemasukan),
            'perubahanPengeluaran' => abs($perubahanPengeluaran),
            'arahPemasukan' => $perubahanPemasukan >= 0 ? 'naik' : 'turun',
            'arahPengeluaran' => $perubahanPengeluaran >= 0 ? 'naik' : 'turun',
            'dataGrafik' => $dataGrafik,
            'totalPemasukanGrafik' => $totalPemasukanGrafik,
            'totalPengeluaranGrafik' => $totalPengeluaranGrafik,
            'dataPieAliran' => $dataPieAliran,
            'tren12Bulan' => $tren12Bulan,
            'rataPemasukan' => $rataPemasukan,
            'rataPengeluaran' => $rataPengeluaran,
            'topTransaksi' => $topTransaksi,
            'pemasukanBulanIni' => $pemasukanBulanIni,
            'pengeluaranBulanIni' => $pengeluaranBulanIni,
            'pemasukanBulanLalu' => $pemasukanBulanLalu,
            'pengeluaranBulanLalu' => $pengeluaranBulanLalu,
        ]);
    }

    public function getChartData(Request $request)
    {
        $periode = $request->input('periode', 6);
        $chartType = $request->input('type', 'bar');

        if ($chartType === 'pie') {
            $dataPieAliran = $this->getDataPieAliran();
            return response()->json($dataPieAliran);
        }

        if ($chartType === 'trend') {
            $trenData = $this->getDataGrafik($periode);
            return response()->json([
                'labels' => array_column($trenData, 'label'),
                'datasets' => [
                    [
                        'label' => 'Pemasukan',
                        'data' => array_column($trenData, 'pemasukan'),
                        'borderColor' => 'rgba(40, 167, 69, 1)',
                        'backgroundColor' => 'rgba(40, 167, 69, 0.1)',
                        'fill' => true,
                        'tension' => 0.4,
                    ],
                    [
                        'label' => 'Pengeluaran',
                        'data' => array_column($trenData, 'pengeluaran'),
                        'borderColor' => 'rgba(220, 53, 69, 1)',
                        'backgroundColor' => 'rgba(220, 53, 69, 0.1)',
                        'fill' => true,
                        'tension' => 0.4,
                    ]
                ]
            ]);
        }

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

    private function getDataPieAliran()
    {
        $bulanIni = Carbon::now();
        $awalBulan = $bulanIni->copy()->startOfMonth();
        $akhirBulan = $bulanIni->copy()->endOfMonth();

        $aliranLabels = [
            'Aktivitas Operasi',
            'Aktivitas Investasi',
            'Aktivitas Pendanaan',
            'Aktivitas Pendanaan Lain',
        ];

        $colors = [
            'rgba(40, 167, 69, 0.8)',   // Green
            'rgba(0, 123, 255, 0.8)',   // Blue
            'rgba(253, 126, 20, 0.8)',  // Orange
            'rgba(111, 66, 193, 0.8)',  // Purple
        ];

        $borderColors = [
            'rgba(40, 167, 69, 1)',
            'rgba(0, 123, 255, 1)',
            'rgba(253, 126, 20, 1)',
            'rgba(111, 66, 193, 1)',
        ];

        $data = [];
        foreach ($aliranLabels as $index => $aliran) {
            $jumlah = Transaksi::where('aliran', $aliran)
                ->whereBetween('tanggal', [$awalBulan, $akhirBulan])
                ->sum('jumlah');

            $data[] = [
                'label' => $aliran,
                'value' => (int)$jumlah,
                'backgroundColor' => $colors[$index],
                'borderColor' => $borderColors[$index],
            ];
        }

        return [
            'labels' => array_column($data, 'label'),
            'values' => array_column($data, 'value'),
            'backgroundColor' => array_column($data, 'backgroundColor'),
            'borderColor' => array_column($data, 'borderColor'),
        ];
    }

    private function getTopTransaksi()
    {
        $bulanIni = Carbon::now();
        $awalBulan = $bulanIni->copy()->startOfMonth();
        $akhirBulan = $bulanIni->copy()->endOfMonth();

        return Transaksi::select('uraian', \Illuminate\Support\Facades\DB::raw('SUM(jumlah) as total, COUNT(*) as frequency'))
            ->whereBetween('tanggal', [$awalBulan, $akhirBulan])
            ->groupBy('uraian')
            ->orderBy('frequency', 'desc')
            ->limit(5)
            ->get();
    }
}