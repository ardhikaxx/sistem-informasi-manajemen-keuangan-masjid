# Rule: Fitur Laporan Arus Kas — Laravel

## Gambaran Umum

Fitur ini menghasilkan **Laporan Arus Kas** dari data tabel `transaksi` yang sudah ada, mengelompokkan transaksi berdasarkan kolom `aliran` ke dalam 4 kategori, dan menyediakan ekspor ke **PDF** dan **Excel** dengan format seperti standar akuntansi.

---

## 1. Struktur Database (Tabel `transaksi`)

```sql
-- Sudah ada, tidak perlu migrasi baru
-- Kolom yang relevan:
id            BIGINT UNSIGNED AUTO_INCREMENT PK
tanggal       DATE
uraian        VARCHAR(255)
jenis_transaksi ENUM('pemasukan','pengeluaran')
jumlah        DECIMAL(15,2)
saldo_sebelum DECIMAL(15,2) DEFAULT 0.00
saldo_sesudah DECIMAL(15,2) DEFAULT 0.00
aliran        ENUM('Aktivitas Operasi','Aktivitas Investasi','Aktivitas Pendanaan','Aktivitas Pendanaan Lain')
keterangan    TEXT NULL
created_at    TIMESTAMP NULL
updated_at    TIMESTAMP NULL
```

---

## 2. Routes — `routes/web.php`

```php
use App\Http\Controllers\ManajemenLaporanController;

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('manajemen-laporan', [ManajemenLaporanController::class, 'index'])
        ->name('manajemen-laporan.index');

    Route::get('manajemen-laporan/export-pdf', [ManajemenLaporanController::class, 'exportPDF'])
        ->name('manajemen-laporan.export-pdf');

    Route::get('manajemen-laporan/export-excel', [ManajemenLaporanController::class, 'exportExcel'])
        ->name('manajemen-laporan.export-excel');
});
```

---

## 3. Controller — `app/Http/Controllers/ManajemenLaporanController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanArusKasExport;

class ManajemenLaporanController extends Controller
{
    // ──────────────────────────────────────────────
    // INDEX — Halaman Manajemen Laporan
    // ──────────────────────────────────────────────
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
```

---

## 4. Export Excel — `app/Exports/LaporanArusKasExport.php`

> Install package: `composer require maatwebsite/excel`

```php
<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanArusKasExport implements FromArray, WithTitle, WithColumnWidths, WithEvents
{
    private array  $laporanArusKas;
    private array  $summary;
    private string $periodeLabel;

    public function __construct(array $laporanArusKas, array $summary, string $periodeLabel)
    {
        $this->laporanArusKas = $laporanArusKas;
        $this->summary        = $summary;
        $this->periodeLabel   = $periodeLabel;
    }

    public function title(): string { return 'Laporan Arus Kas'; }

    public function columnWidths(): array
    {
        return ['A' => 55, 'B' => 22];
    }

    public function array(): array
    {
        $rows = [];
        $rp   = fn($v) => 'Rp ' . number_format(abs($v), 0, ',', '.');
        $neg  = fn($v) => $v < 0;

        // Header
        $rows[] = ['Organisasi Nirlaba', ''];
        $rows[] = ['Laporan Arus Kas', ''];
        $rows[] = [$this->periodeLabel, ''];
        $rows[] = ['', ''];

        $sections = [
            'operasi'        => 'Aliran Kas dari Aktivitas Operasi:',
            'investasi'      => 'Aliran Kas dari Aktivitas Investasi:',
            'pendanaan'      => 'Aliran Kas dari Aktivitas Pendanaan:',
            'pendanaan_lain' => 'Aliran Kas dari Aktivitas Pendanaan Lain:',
        ];

        $subtotals = [
            'operasi'        => 'Kas bersih yang diterima (digunakan) untuk aktivitas operasi',
            'investasi'      => 'Kas bersih yang diterima (digunakan) untuk aktivitas investasi',
            'pendanaan'      => 'Kas bersih yang diterima (digunakan) untuk aktivitas pendanaan',
            'pendanaan_lain' => 'Kas bersih yang diterima (digunakan) untuk aktivitas pendanaan lain',
        ];

        foreach ($sections as $key => $heading) {
            $rows[] = [$heading, ''];
            foreach ($this->laporanArusKas[$key]['items'] as $item) {
                $val = $item['jumlah'];
                $rows[] = ['  ' . $item['uraian'], ($neg($val) ? '(' . $rp($val) . ')' : $rp($val))];
            }
            $total  = $this->laporanArusKas[$key]['total'];
            $rows[] = ['  ' . $subtotals[$key], ($neg($total) ? '(' . $rp($total) . ')' : $rp($total))];
            $rows[] = ['', ''];
        }

        // Kenaikan/penurunan bersih
        $selisih = $this->summary['selisih'];
        $rows[]  = [
            'Kenaikan (Penurunan) bersih dalam kas dan setara kas',
            ($selisih < 0 ? '(' . $rp($selisih) . ')' : $rp($selisih)),
        ];
        $rows[] = ['Kas dan setara kas pada awal periode', $rp($this->summary['saldo_awal_periode'])];
        $rows[] = ['Kas dan setara kas pada akhir periode', $rp($this->summary['saldo_akhir_periode'])];

        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $this->applyStyles($sheet);
            },
        ];
    }

    private function applyStyles(Worksheet $sheet): void
    {
        $highRow = $sheet->getHighestRow();

        // Header — merge & center
        foreach ([1, 2, 3] as $r) {
            $sheet->mergeCells("A{$r}:B{$r}");
            $sheet->getStyle("A{$r}")->applyFromArray([
                'font'      => ['bold' => true, 'size' => $r === 1 ? 14 : 12],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
        }

        // Scan rows: bold section headers & subtotals, color negative values
        for ($r = 4; $r <= $highRow; $r++) {
            $cell = $sheet->getCell("A{$r}")->getValue();
            $valB = $sheet->getCell("B{$r}")->getValue();

            // Section heading rows (Aliran Kas dari …)
            if (str_starts_with((string)$cell, 'Aliran Kas dari')) {
                $sheet->getStyle("A{$r}:B{$r}")->getFont()->setBold(true);
                $sheet->getStyle("A{$r}:B{$r}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('D9E1F2');
            }

            // Subtotal rows (Kas bersih …)
            if (str_contains((string)$cell, 'Kas bersih')) {
                $sheet->getStyle("A{$r}:B{$r}")->getFont()->setBold(true);
                $sheet->getStyle("B{$r}")->applyFromArray([
                    'borders' => [
                        'top'    => ['borderStyle' => Border::BORDER_THIN],
                        'bottom' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);
            }

            // Kenaikan/penurunan bersih
            if (str_contains((string)$cell, 'Kenaikan')) {
                $sheet->getStyle("A{$r}:B{$r}")->getFont()->setBold(true);
                $sheet->getStyle("B{$r}")->applyFromArray([
                    'borders' => [
                        'top'    => ['borderStyle' => Border::BORDER_MEDIUM],
                        'bottom' => ['borderStyle' => Border::BORDER_MEDIUM],
                    ],
                ]);
            }

            // Saldo akhir — double underline effect
            if (str_contains((string)$cell, 'akhir periode')) {
                $sheet->getStyle("A{$r}:B{$r}")->getFont()->setBold(true)->setSize(13);
                $sheet->getStyle("B{$r}")->applyFromArray([
                    'borders' => [
                        'top'    => ['borderStyle' => Border::BORDER_MEDIUM],
                        'bottom' => ['borderStyle' => Border::BORDER_DOUBLE],
                    ],
                ]);
            }

            // Nilai negatif → merah
            if ($valB && str_contains((string)$valB, '(')) {
                $sheet->getStyle("B{$r}")->getFont()->setColor(
                    (new \PhpOffice\PhpSpreadsheet\Style\Color())->setRGB('FF0000')
                );
            }

            // Kolom B rata kanan
            $sheet->getStyle("B{$r}")->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        }
    }
}
```

---

## 5. View PDF — `resources/views/admins/manajemen-laporan/pdf.blade.php`

```blade
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Arus Kas</title>
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 11pt; margin: 1.5cm 2cm; color: #000; }
        h1 { font-size: 14pt; margin: 0; text-align: center; }
        h2 { font-size: 12pt; margin: 4px 0; text-align: center; font-weight: normal; }
        .periode { text-align: center; font-size: 11pt; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 2px 4px; vertical-align: top; }
        .right { text-align: right; width: 28%; }
        .bold { font-weight: bold; }
        .indent { padding-left: 16px; }
        .subtotal td { border-top: 1px solid #000; border-bottom: 1px solid #000; font-weight: bold; }
        .grand-total td { border-top: 2px solid #000; border-bottom: 2px solid #000; font-weight: bold; font-size: 13pt; }
        .section-head td { font-weight: bold; padding-top: 10px; background: #eef2fb; }
        .neg { color: #c00; }
    </style>
</head>
<body>
    <h1>Organisasi Nirlaba</h1>
    <h2>Laporan Arus Kas</h2>
    <p class="periode">{{ $periodeLabel }}</p>

    <table>
    @php
        $sections = [
            'operasi'        => 'Aliran Kas dari Aktivitas Operasi:',
            'investasi'      => 'Aliran Kas dari Aktivitas Investasi:',
            'pendanaan'      => 'Aliran Kas dari Aktivitas Pendanaan:',
            'pendanaan_lain' => 'Aliran Kas dari Aktivitas Pendanaan Lain:',
        ];
        $subtotals = [
            'operasi'        => 'Kas bersih yang diterima (digunakan) untuk aktivitas operasi',
            'investasi'      => 'Kas bersih yang diterima (digunakan) untuk aktivitas investasi',
            'pendanaan'      => 'Kas bersih yang diterima (digunakan) untuk aktivitas pendanaan',
            'pendanaan_lain' => 'Kas bersih yang diterima (digunakan) untuk aktivitas pendanaan lain',
        ];
        $fmt = fn($v) => $v < 0
            ? '(Rp ' . number_format(abs($v), 0, ',', '.') . ')'
            : 'Rp ' . number_format($v, 0, ',', '.');
    @endphp

    @foreach($sections as $key => $heading)
        <tr class="section-head">
            <td colspan="2">{{ $heading }}</td>
        </tr>
        @foreach($laporanArusKas[$key]['items'] as $item)
        <tr>
            <td class="indent">{{ $item['uraian'] }}</td>
            <td class="right {{ $item['jumlah'] < 0 ? 'neg' : '' }}">{{ $fmt($item['jumlah']) }}</td>
        </tr>
        @endforeach
        <tr class="subtotal">
            <td class="indent" style="padding-left:30px; font-style:italic;">{{ $subtotals[$key] }}</td>
            <td class="right {{ $laporanArusKas[$key]['total'] < 0 ? 'neg' : '' }}">
                {{ $fmt($laporanArusKas[$key]['total']) }}
            </td>
        </tr>
        <tr><td colspan="2">&nbsp;</td></tr>
    @endforeach

        <tr class="subtotal">
            <td class="bold">Kenaikan (Penurunan) bersih dalam kas dan setara kas</td>
            <td class="right {{ $summary['selisih'] < 0 ? 'neg' : '' }}">{{ $fmt($summary['selisih']) }}</td>
        </tr>
        <tr>
            <td>Kas dan setara kas pada awal periode</td>
            <td class="right">{{ $fmt($summary['saldo_awal_periode']) }}</td>
        </tr>
        <tr class="grand-total">
            <td>Kas dan setara kas pada akhir periode</td>
            <td class="right">{{ $fmt($summary['saldo_akhir_periode']) }}</td>
        </tr>
    </table>
</body>
</html>
```

---

## 7. Package yang Dibutuhkan

```bash
# PDF export (DomPDF)
composer require barryvdh/laravel-dompdf

# Excel export (Maatwebsite Excel)
composer require maatwebsite/excel

# Publish config (opsional)
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config
```

Tambahkan di `config/app.php` → `providers` (jika Laravel < 11):
```php
Barryvdh\DomPDF\ServiceProvider::class,
Maatwebsite\Excel\ExcelServiceProvider::class,
```

---

## 8. Alur Data & Logika Bisnis

```
tabel transaksi
    │
    ├─ filter periode (harian/bulanan/tahunan/custom)
    │
    ├─ groupBy(aliran) ──► 4 kategori
    │      Aktivitas Operasi
    │      Aktivitas Investasi
    │      Aktivitas Pendanaan
    │      Aktivitas Pendanaan Lain
    │
    ├─ groupBy(uraian) per kategori
    │      pemasukan  → +jumlah
    │      pengeluaran → -jumlah
    │
    ├─ sum per uraian  → items[]
    ├─ sum items       → subtotal per kategori
    │
    ├─ saldo_awal      = saldo_sesudah transaksi terakhir sebelum periode
    ├─ selisih (net)   = total_pemasukan - total_pengeluaran
    └─ saldo_akhir     = saldo_awal + selisih
```