<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanArusKasExport;
use Illuminate\Support\Facades\DB;

class ManajemenLaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $jenisLaporan = $request->input('jenis_laporan', 'bulanan');
            $bulan        = $request->input('bulan');
            $tahun        = $request->input('tahun');
            $startDate    = $request->input('start_date');
            $endDate      = $request->input('end_date');

            $query = $this->buildQuery($jenisLaporan, $bulan, $tahun, $startDate, $endDate);

            $transaksis = $query->orderBy('tanggal')->orderBy('id')->paginate(15)->withQueryString();

            // Ambil SEMUA data periode (tanpa paginasi) untuk summary & laporan arus kas
            $queryAll   = $this->buildQuery($jenisLaporan, $bulan, $tahun, $startDate, $endDate);
            $allData    = $queryAll->orderBy('tanggal')->orderBy('id')->get();

            $summary         = $this->calculateSummary($allData);
            $laporanArusKas  = $this->buildLaporanArusKas($allData, $summary);
            $periodeLabel    = $this->generatePeriodLabel($jenisLaporan, $bulan, $tahun, $startDate, $endDate);

            $tahunList = Transaksi::selectRaw('YEAR(tanggal) as tahun')
                ->groupBy('tahun')->orderBy('tahun', 'desc')->pluck('tahun');

            return view('admins.manajemen-laporan.index', compact(
                'transaksis', 'summary', 'laporanArusKas',
                'tahunList', 'jenisLaporan', 'bulan', 'tahun',
                'startDate', 'endDate', 'periodeLabel'
            ));
        } catch (\Exception $e) {
            Log::error('ManajemenLaporanController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat laporan.');
        }
    }

    // ──────────────────────────────────────────────
    // EXPORT PDF
    // ──────────────────────────────────────────────
    public function exportPDF(Request $request)
    {
        $jenisLaporan = $request->input('jenis_laporan', 'bulanan');
        $bulan        = $request->input('bulan');
        $tahun        = $request->input('tahun');
        $startDate    = $request->input('start_date');
        $endDate      = $request->input('end_date');

        $allData         = $this->buildQuery($jenisLaporan, $bulan, $tahun, $startDate, $endDate)
                               ->orderBy('tanggal')->orderBy('id')->get();
        $summary         = $this->calculateSummary($allData);
        $laporanArusKas  = $this->buildLaporanArusKas($allData, $summary);
        $periodeLabel    = $this->generatePeriodLabel($jenisLaporan, $bulan, $tahun, $startDate, $endDate);

        $pdf = Pdf::loadView('admins.manajemen-laporan.pdf', compact(
            'laporanArusKas', 'summary', 'periodeLabel'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('laporan-arus-kas-' . now()->format('Ymd') . '.pdf');
    }

    // ──────────────────────────────────────────────
    // EXPORT EXCEL
    // ──────────────────────────────────────────────
    public function exportExcel(Request $request)
    {
        $jenisLaporan = $request->input('jenis_laporan', 'bulanan');
        $bulan        = $request->input('bulan');
        $tahun        = $request->input('tahun');
        $startDate    = $request->input('start_date');
        $endDate      = $request->input('end_date');

        $allData         = $this->buildQuery($jenisLaporan, $bulan, $tahun, $startDate, $endDate)
                               ->orderBy('tanggal')->orderBy('id')->get();
        $summary         = $this->calculateSummary($allData);
        $laporanArusKas  = $this->buildLaporanArusKas($allData, $summary);
        $periodeLabel    = $this->generatePeriodLabel($jenisLaporan, $bulan, $tahun, $startDate, $endDate);

        $filename = 'laporan-arus-kas-' . now()->format('Ymd') . '.xlsx';

        return Excel::download(
            new LaporanArusKasExport($laporanArusKas, $summary, $periodeLabel),
            $filename
        );
    }

    // ──────────────────────────────────────────────
    // PRIVATE HELPERS
    // ──────────────────────────────────────────────

    private function buildQuery($jenisLaporan, $bulan, $tahun, $startDate, $endDate)
    {
        $query = Transaksi::query();

        switch ($jenisLaporan) {
            case 'harian':
                if ($startDate && $endDate) {
                    $query->whereBetween('tanggal', [$startDate, $endDate]);
                } elseif ($tahun && $bulan) {
                    $query->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan);
                } else {
                    $query->whereDate('tanggal', Carbon::today());
                }
                break;
            case 'mingguan':
                if ($startDate && $endDate) {
                    $query->whereBetween('tanggal', [$startDate, $endDate]);
                } else {
                    $query->whereBetween('tanggal', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek(),
                    ]);
                }
                break;
            case 'tahunan':
                $query->whereYear('tanggal', $tahun ?? Carbon::now()->year);
                break;
            case 'custom':
                if ($startDate && $endDate) {
                    $query->whereBetween('tanggal', [$startDate, $endDate]);
                }
                break;
            default: // bulanan
                if ($bulan && $tahun) {
                    $query->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan);
                } else {
                    $query->whereYear('tanggal', Carbon::now()->year)
                          ->whereMonth('tanggal', Carbon::now()->month);
                }
                break;
        }

        return $query;
    }

    private function calculateSummary($transactions)
    {
        $totalPemasukan   = $transactions->where('jenis_transaksi', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = $transactions->where('jenis_transaksi', 'pengeluaran')->sum('jumlah');
        $selisih          = $totalPemasukan - $totalPengeluaran;

        $periodeAwal          = $transactions->min('tanggal');
        $saldoSebelumPeriode  = 0;

        if ($periodeAwal) {
            $prev = Transaksi::where('tanggal', '<', $periodeAwal)
                ->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->first();
            $saldoSebelumPeriode = $prev ? $prev->saldo_sesudah : 0;
        }

        return [
            'total_pemasukan'      => $totalPemasukan,
            'total_pengeluaran'    => $totalPengeluaran,
            'selisih'              => $selisih,
            'saldo_awal_periode'   => $saldoSebelumPeriode,
            'saldo_akhir_periode'  => $saldoSebelumPeriode + $selisih,
        ];
    }

    /**
     * Bangun struktur laporan arus kas per 4 kategori aliran.
     *
     * Return:
     * [
     *   'operasi'        => ['items' => [...], 'total' => 0],
     *   'investasi'      => ['items' => [...], 'total' => 0],
     *   'pendanaan'      => ['items' => [...], 'total' => 0],
     *   'pendanaan_lain' => ['items' => [...], 'total' => 0],
     * ]
     * Setiap item: ['uraian' => '...', 'jumlah' => signed_number]
     */
    private function buildLaporanArusKas($transactions, $summary)
    {
        $map = [
            'operasi'        => 'Aktivitas Operasi',
            'investasi'      => 'Aktivitas Investasi',
            'pendanaan'      => 'Aktivitas Pendanaan',
            'pendanaan_lain' => 'Aktivitas Pendanaan Lain',
        ];

        $result = [];

        foreach ($map as $key => $aliranLabel) {
            $filtered = $transactions->filter(fn($t) => $t->aliran === $aliranLabel);

            // Kelompokkan berdasarkan uraian lalu jumlahkan (pemasukan +, pengeluaran -)
            $grouped = $filtered->groupBy('uraian')->map(function ($rows) {
                return $rows->sum(function ($t) {
                    return $t->jenis_transaksi === 'pemasukan'
                        ? $t->jumlah
                        : -$t->jumlah;
                });
            });

            $items = $grouped->map(fn($jumlah, $uraian) => [
                'uraian' => $uraian,
                'jumlah' => $jumlah,
            ])->values()->toArray();

            $total = array_sum(array_column($items, 'jumlah'));

            $result[$key] = compact('items', 'total');
        }

        return $result;
    }

    private function generatePeriodLabel($jenisLaporan, $bulan, $tahun, $startDate, $endDate): string
    {
        $labels   = ['harian'=>'Harian','mingguan'=>'Mingguan','bulanan'=>'Bulanan','tahunan'=>'Tahunan','custom'=>'Custom'];
        $jenisText = $labels[$jenisLaporan] ?? 'Unknown';

        if ($jenisLaporan === 'custom' && $startDate && $endDate) {
            return $jenisText . ': ' . Carbon::parse($startDate)->translatedFormat('d F Y')
                . ' - ' . Carbon::parse($endDate)->translatedFormat('d F Y');
        }
        if ($bulan && $tahun) {
            return $jenisText . ': ' . Carbon::create(null, $bulan, 1)->translatedFormat('F') . ' ' . $tahun;
        }
        if ($tahun) {
            return $jenisText . ': ' . $tahun;
        }
        if ($jenisLaporan === 'harian') {
            return $jenisText . ': ' . Carbon::today()->translatedFormat('d F Y');
        }
        if ($jenisLaporan === 'mingguan') {
            return $jenisText . ': ' . Carbon::now()->startOfWeek()->translatedFormat('d F Y')
                . ' - ' . Carbon::now()->endOfWeek()->translatedFormat('d F Y');
        }
        return $jenisText;
    }
}