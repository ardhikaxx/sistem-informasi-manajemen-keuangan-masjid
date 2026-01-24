<?php
namespace App\Http\Controllers;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ManajemenKeuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Get filter parameters
            $search = $request->input('search');
            $bulan = $request->input('bulan');
            $tahun = $request->input('tahun');
            $jenis = $request->input('jenis');
            $aliran = $request->input('aliran'); // Tambahkan filter aliran jika diperlukan

            // Start query
            $query = Transaksi::query();

            // Apply filters
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('uraian', 'like', "%{$search}%")
                        ->orWhere('keterangan', 'like', "%{$search}%");
                });
            }
            if ($bulan && $tahun) {
                $query->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan);
            } elseif ($tahun) {
                $query->whereYear('tanggal', $tahun);
            }
            if ($jenis && in_array($jenis, ['pemasukan', 'pengeluaran'])) {
                $query->where('jenis_transaksi', $jenis);
            }
            // Tambahkan filter aliran jika diperlukan di sini
            if ($aliran) {
                $query->where('aliran', $aliran);
            }

            // Order by date and id
            $query->orderBy('tanggal', 'desc')
                ->orderBy('id', 'desc');

            // Paginate results
            $perPage = 10;
            $transaksis = $query->paginate($perPage);

            // Get summary statistics
            $summary = $this->getSummary($bulan, $tahun);

            // Get years for filter dropdown
            $tahunList = Transaksi::selectRaw('YEAR(tanggal) as tahun')
                ->groupBy('tahun')
                ->orderBy('tahun', 'desc')
                ->pluck('tahun');

            // Define aliran options for filter dropdown (optional)
            $aliranOptions = Transaksi::select('aliran')
                ->distinct()
                ->whereNotNull('aliran')
                ->pluck('aliran');

            // Calculate saldo akhir
            $saldoAkhir = $this->getSaldoAkhir();

            return view('admins.manajemen-keuangan.index', compact(
                'transaksis',
                'summary',
                'tahunList',
                'saldoAkhir',
                'search',
                'bulan',
                'tahun',
                'jenis',
                'aliranOptions' // Kirim ke view jika ingin digunakan untuk filter
            ));
        } catch (\Exception $e) {
            Log::error('Error in ManajemenKeuanganController@index: ' . $e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction(); // Mulai transaksi database
        try {
            // Validasi input - tambahkan aliran
            $validator = Validator::make($request->all(), [
                'tanggal' => 'required|date',
                'uraian' => 'required|string|max:255',
                'jenis_transaksi' => 'required|in:pemasukan,pengeluaran',
                'jumlah' => 'required|numeric|min:1',
                'keterangan' => 'nullable|string',
                'aliran' => 'required|in:Aktivitas Operasi,Aktivitas Investasi,Aktivitas Pendanaan,Aktivitas Pendanaan Lain' // Validasi enum
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Format jumlah sebagai decimal
            $jumlah = $request->jumlah;
            if (is_string($jumlah)) {
                $jumlah = (float) str_replace(['.', ','], ['', '.'], $jumlah);
            }

            // Hitung saldo sebelumnya
            $lastTransaction = Transaksi::orderBy('tanggal', 'desc')
                ->orderBy('id', 'desc')
                ->first();
            $saldoSebelum = $lastTransaction ? $lastTransaction->saldo_sesudah : 0;

            // Hitung saldo sesudah
            if ($request->jenis_transaksi == 'pemasukan') {
                $saldoSesudah = $saldoSebelum + $jumlah;
            } else {
                $saldoSesudah = $saldoSebelum - $jumlah;
            }

            // Buat transaksi baru
            $transaksi = new Transaksi();
            $transaksi->tanggal = $request->tanggal;
            $transaksi->uraian = $request->uraian;
            $transaksi->jenis_transaksi = $request->jenis_transaksi;
            $transaksi->jumlah = $jumlah;
            $transaksi->keterangan = $request->keterangan;
            $transaksi->aliran = $request->aliran; // Simpan aliran
            $transaksi->saldo_sebelum = $saldoSebelum;
            $transaksi->saldo_sesudah = $saldoSesudah;

            // Simpan transaksi
            $transaksi->save();

            DB::commit(); // Commit transaksi database

            // Log keberhasilan jika diperlukan
            Log::info("Transaksi berhasil disimpan: ID {$transaksi->id}");

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan!',
                'data' => [
                    'id' => $transaksi->id,
                    'uraian' => $transaksi->uraian,
                    'jumlah' => $transaksi->jumlah,
                    'aliran' => $transaksi->aliran, // Tambahkan ke response jika perlu
                    'saldo_sesudah' => $transaksi->saldo_sesudah
                ]
            ], 200);

        } catch (\Throwable $e) { // Tangkap Throwable untuk menangani Exception & Error fatal
            DB::rollback(); // Rollback transaksi database jika terjadi error
            // Log error ke file log Laravel secara rinci
            Log::error('Error menyimpan transaksi di ManajemenKeuanganController@store: ' . $e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());

            // Kirim response error umum ke client tanpa informasi sensitif
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan transaksi. Silakan coba lagi nanti.'
            ], 500); // Gunakan HTTP status 500 untuk server error
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $transaksi = Transaksi::find($id);
            if (!$transaksi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $transaksi
            ]);
        } catch (\Exception $e) {
            Log::error('Error mengambil data transaksi di ManajemenKeuanganController@edit: ' . $e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data transaksi.'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $transaksi = Transaksi::findOrFail($id);

            // Validasi input - tambahkan aliran
            $validator = Validator::make($request->all(), [
                'tanggal' => 'required|date',
                'uraian' => 'required|string|max:255',
                'jenis_transaksi' => 'required|in:pemasukan,pengeluaran',
                'jumlah' => 'required|numeric|min:1',
                'keterangan' => 'nullable|string',
                'aliran' => 'required|in:Aktivitas Operasi,Aktivitas Investasi,Aktivitas Pendanaan,Aktivitas Pendanaan Lain'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Format jumlah sebagai decimal
            $jumlah = $request->jumlah;
            if (is_string($jumlah)) {
                $jumlah = (float) str_replace(['.', ','], ['', '.'], $jumlah);
            }

            // Simpan data lama untuk perbandingan
            $oldData = $transaksi->toArray();

            // Update data transaksi
            $transaksi->tanggal = $request->tanggal;
            $transaksi->uraian = $request->uraian;
            $transaksi->jenis_transaksi = $request->jenis_transaksi;
            $transaksi->jumlah = $jumlah;
            $transaksi->keterangan = $request->keterangan;
            $transaksi->aliran = $request->aliran; // Update aliran

            // Hitung ulang saldo
            $this->recalculateSaldoFromTransaction($transaksi);

            // Simpan perubahan
            $transaksi->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diperbarui!',
                'data' => $transaksi
            ]);
        } catch (\Throwable $e) {
            DB::rollback();
            Log::error('Error memperbarui transaksi di ManajemenKeuanganController@update: ' . $e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui transaksi. Silakan coba lagi nanti.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $transaksi = Transaksi::findOrFail($id);
            $transaksiId = $transaksi->id;
            $transaksiTanggal = $transaksi->tanggal;

            // Hapus transaksi
            $transaksi->delete();

            // Hitung ulang saldo untuk transaksi setelah yang dihapus
            $this->recalculateSaldoAfterDelete($transaksiTanggal, $transaksiId);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dihapus!'
            ]);
        } catch (\Throwable $e) {
            DB::rollback();
            Log::error('Error menghapus transaksi di ManajemenKeuanganController@destroy: ' . $e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus transaksi. Silakan coba lagi nanti.'
            ], 500);
        }
    }

    /**
     * Get summary statistics.
     */
    private function getSummary($bulan = null, $tahun = null)
    {
        $query = Transaksi::query();
        if ($bulan && $tahun) {
            $query->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan);
        } elseif ($tahun) {
            $query->whereYear('tanggal', $tahun);
        }

        $summary = $query->selectRaw('
            SUM(CASE WHEN jenis_transaksi = "pemasukan" THEN jumlah ELSE 0 END) as total_pemasukan,
            SUM(CASE WHEN jenis_transaksi = "pengeluaran" THEN jumlah ELSE 0 END) as total_pengeluaran
        ')->first();

        return [
            'total_pemasukan' => $summary->total_pemasukan ?? 0,
            'total_pengeluaran' => $summary->total_pengeluaran ?? 0,
            'selisih' => ($summary->total_pemasukan ?? 0) - ($summary->total_pengeluaran ?? 0)
        ];
    }

    /**
     * Get saldo akhir.
     */
    private function getSaldoAkhir()
    {
        $lastTransaction = Transaksi::orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->first();
        return $lastTransaction ? $lastTransaction->saldo_sesudah : 0;
    }

    /**
     * Recalculate saldo from a specific transaction.
     */
    private function recalculateSaldoFromTransaction(Transaksi $transaction)
    {
        // Cari transaksi sebelumnya
        $previousTransaction = Transaksi::where('tanggal', '<', $transaction->tanggal)
            ->orWhere(function ($query) use ($transaction) {
                $query->where('tanggal', '=', $transaction->tanggal)
                    ->where('id', '<', $transaction->id);
            })
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        $saldoSebelum = $previousTransaction ? $previousTransaction->saldo_sesudah : 0;

        // Update saldo untuk transaksi ini
        $transaction->saldo_sebelum = $saldoSebelum;
        $transaction->saldo_sesudah = $transaction->jenis_transaksi == 'pemasukan'
            ? $saldoSebelum + $transaction->jumlah
            : $saldoSebelum - $transaction->jumlah;

        // Update transaksi berikutnya
        $this->updateFollowingTransactions($transaction);
    }

    /**
     * Update following transactions after a transaction is updated.
     */
    private function updateFollowingTransactions(Transaksi $transaction)
    {
        $followingTransactions = Transaksi::where('tanggal', '>', $transaction->tanggal)
            ->orWhere(function ($query) use ($transaction) {
                $query->where('tanggal', '=', $transaction->tanggal)
                    ->where('id', '>', $transaction->id);
            })
            ->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $currentSaldo = $transaction->saldo_sesudah;

        foreach ($followingTransactions as $followingTransaction) {
            $followingTransaction->saldo_sebelum = $currentSaldo;
            $followingTransaction->saldo_sesudah = $followingTransaction->jenis_transaksi == 'pemasukan'
                ? $currentSaldo + $followingTransaction->jumlah
                : $currentSaldo - $followingTransaction->jumlah;
            $followingTransaction->save();
            $currentSaldo = $followingTransaction->saldo_sesudah;
        }
    }

    /**
     * Recalculate saldo after a transaction is deleted.
     */
    private function recalculateSaldoAfterDelete($tanggal, $id)
    {
        // Cari transaksi sebelum yang dihapus
        $previousTransaction = Transaksi::where('tanggal', '<', $tanggal)
            ->orWhere(function ($query) use ($tanggal, $id) {
                $query->where('tanggal', '=', $tanggal)
                    ->where('id', '<', $id);
            })
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        $currentSaldo = $previousTransaction ? $previousTransaction->saldo_sesudah : 0;

        // Update semua transaksi setelah yang dihapus
        $followingTransactions = Transaksi::where('tanggal', '>', $tanggal)
            ->orWhere(function ($query) use ($tanggal, $id) {
                $query->where('tanggal', '=', $tanggal)
                    ->where('id', '>', $id);
            })
            ->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        foreach ($followingTransactions as $transaction) {
            $transaction->saldo_sebelum = $currentSaldo;
            $transaction->saldo_sesudah = $transaction->jenis_transaksi == 'pemasukan'
                ? $currentSaldo + $transaction->jumlah
                : $currentSaldo - $transaction->jumlah;
            $transaction->save();
            $currentSaldo = $transaction->saldo_sesudah;
        }
    }

    /**
     * Get transaction statistics for dashboard.
     */
    public function getStatistics(Request $request)
    {
        try {
            $bulan = $request->input('bulan', date('m'));
            $tahun = $request->input('tahun', date('Y'));

            // Total pemasukan bulan ini
            $totalPemasukan = Transaksi::where('jenis_transaksi', 'pemasukan')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->sum('jumlah');

            // Total pengeluaran bulan ini
            $totalPengeluaran = Transaksi::where('jenis_transaksi', 'pengeluaran')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->sum('jumlah');

            // Transaksi terbaru
            $transaksiTerbaru = Transaksi::orderBy('tanggal', 'desc')
                ->orderBy('id', 'desc')
                ->take(5)
                ->get();

            // Saldo akhir
            $saldoAkhir = $this->getSaldoAkhir();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_pemasukan' => $totalPemasukan,
                    'total_pengeluaran' => $totalPengeluaran,
                    'saldo_akhir' => $saldoAkhir,
                    'transaksi_terbaru' => $transaksiTerbaru
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error mengambil statistik di ManajemenKeuanganController@getStatistics: ' . $e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik. Silakan coba lagi nanti.'
            ], 500);
        }
    }
}