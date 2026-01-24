<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ManajemenLaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Get filter parameters
            $jenisLaporan = $request->input('jenis_laporan', 'bulanan');
            $bulan = $request->input('bulan');
            $tahun = $request->input('tahun');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            // Start query
            $query = Transaksi::query();

            // Apply filters based on jenis_laporan
            switch ($jenisLaporan) {
                case 'harian':
                    if ($startDate && $endDate) {
                        $query->whereBetween('tanggal', [$startDate, $endDate]);
                    } elseif ($tahun && $bulan) {
                        $query->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan);
                    } else {
                        // Default to today if no specific filter
                        $query->whereDate('tanggal', Carbon::today());
                    }
                    break;
                case 'mingguan':
                    if ($startDate && $endDate) {
                        $query->whereBetween('tanggal', [$startDate, $endDate]);
                    } elseif ($tahun && $bulan) {
                        // Example: filter by week within a month if needed
                        $query->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan);
                    } else {
                        // Default to this week
                        $query->whereBetween('tanggal', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    }
                    break;
                case 'bulanan':
                    if ($bulan && $tahun) {
                        $query->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan);
                    } else {
                        // Default to current month/year
                        $query->whereYear('tanggal', Carbon::now()->year)->whereMonth('tanggal', Carbon::now()->month);
                    }
                    break;
                case 'tahunan':
                    if ($tahun) {
                        $query->whereYear('tanggal', $tahun);
                    } else {
                        // Default to current year
                        $query->whereYear('tanggal', Carbon::now()->year);
                    }
                    break;
                case 'custom':
                    if ($startDate && $endDate) {
                        $query->whereBetween('tanggal', [$startDate, $endDate]);
                    }
                    // If no start/end date for custom, might want to apply default
                    break;
                default: // fallback to 'bulanan'
                    if ($bulan && $tahun) {
                        $query->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan);
                    } else {
                        $query->whereYear('tanggal', Carbon::now()->year)->whereMonth('tanggal', Carbon::now()->month);
                    }
                    break;
            }

            // Order by date and id
            $query->orderBy('tanggal', 'asc') // Ascending for reports typically
                ->orderBy('id', 'asc');

            // Get paginated results
            $perPage = 15; // Adjust as needed
            $transaksis = $query->paginate($perPage);

            // Calculate summary statistics for the filtered period
            $summary = $this->calculateSummary($transaksis);

            // Get years for filter dropdown
            $tahunList = Transaksi::selectRaw('YEAR(tanggal) as tahun')
                ->groupBy('tahun')
                ->orderBy('tahun', 'desc')
                ->pluck('tahun');

            // Generate label for the selected period
            $periodeLabel = $this->generatePeriodLabel($jenisLaporan, $bulan, $tahun, $startDate, $endDate);

            return view('admins.manajemen-laporan.index', compact(
                'transaksis',
                'summary',
                'tahunList',
                'jenisLaporan',
                'bulan',
                'tahun',
                'startDate',
                'endDate',
                'periodeLabel'
            ));

        } catch (\Exception $e) {
            Log::error('Error in ManajemenLaporanController@index: ' . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat laporan.');
        }
    }

    /**
     * Calculate summary statistics for the given transactions collection.
     */
    private function calculateSummary($transactions)
    {
        $totalPemasukan = $transactions->where('jenis_transaksi', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = $transactions->where('jenis_transaksi', 'pengeluaran')->sum('jumlah');
        $selisih = $totalPemasukan - $totalPengeluaran;

        $periodeAwal = $transactions->min('tanggal');
        $periodeAkhir = $transactions->max('tanggal');

        $saldoSebelumPeriode = 0;
        if ($periodeAwal) {
            $transaksiSebelumnya = Transaksi::where('tanggal', '<', $periodeAwal)
                ->orderBy('tanggal', 'desc')
                ->orderBy('id', 'desc')
                ->first();
            $saldoSebelumPeriode = $transaksiSebelumnya ? $transaksiSebelumnya->saldo_sesudah : 0;
        }

        $saldoAkhir = $saldoSebelumPeriode + $selisih;

        return [
            'total_pemasukan' => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'selisih' => $selisih, // Net difference
            'saldo_akhir_periode' => $saldoAkhir
        ];
    }

    /**
     * Generate a label for the selected period.
     */
    private function generatePeriodLabel($jenisLaporan, $bulan, $tahun, $startDate, $endDate)
    {
        $labels = [
            'harian' => 'Harian',
            'mingguan' => 'Mingguan',
            'bulanan' => 'Bulanan',
            'tahunan' => 'Tahunan',
            'custom' => 'Custom'
        ];

        $jenisText = $labels[$jenisLaporan] ?? 'Unknown';

        if ($jenisLaporan === 'custom' && $startDate && $endDate) {
            $startDateFormatted = Carbon::parse($startDate)->translatedFormat('d F Y');
            $endDateFormatted = Carbon::parse($endDate)->translatedFormat('d F Y');
            return "$jenisText: $startDateFormatted - $endDateFormatted";
        }

        if ($bulan && $tahun) {
            $bulanName = Carbon::create(null, $bulan, 1)->translatedFormat('F');
            return "$jenisText: $bulanName $tahun";
        }

        if ($tahun) {
            return "$jenisText: $tahun";
        }

        if ($jenisLaporan === 'harian' && !($bulan && $tahun)) {
            return "$jenisText: " . Carbon::today()->translatedFormat('d F Y');
        }

        if ($jenisLaporan === 'mingguan' && !($bulan && $tahun)) {
            $startWeek = Carbon::now()->startOfWeek()->translatedFormat('d F Y');
            $endWeek = Carbon::now()->endOfWeek()->translatedFormat('d F Y');
            return "$jenisText: $startWeek - $endWeek";
        }

        // Fallback
        return $jenisText;
    }

    // --- Export PDF function will be added later ---
    // public function exportPDF(Request $request) { ... }
}