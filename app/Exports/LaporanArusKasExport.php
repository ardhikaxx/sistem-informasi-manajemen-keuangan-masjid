<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
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
        $rows[] = ['Sistem Keuangan Masjid Jami\' Al-Muttaqiin', ''];
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
