<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransaksiExport implements FromCollection, WithHeadings, WithStyles
{
    protected $transaksis;

    public function __construct($transaksis)
    {
        $this->transaksis = $transaksis;
    }

    public function collection()
    {
        $data = [];
        $saldo = 0;

        foreach ($this->transaksis as $transaksi) {
            if ($transaksi->jenis_transaksi === 'pemasukan') {
                $saldo += $transaksi->jumlah;
            } else {
                $saldo -= $transaksi->jumlah;
            }

            $data[] = [
                'Tanggal' => \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y'),
                'Uraian' => $transaksi->uraian,
                'Jenis Transaksi' => $transaksi->jenis_transaksi,
                'Jumlah' => $transaksi->jumlah,
                'Saldo' => $saldo,
                'Aliran Kas' => $transaksi->aliran,
                'Keterangan' => $transaksi->keterangan,
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return ['Tanggal', 'Uraian', 'Jenis Transaksi', 'Jumlah', 'Saldo', 'Aliran Kas', 'Keterangan'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'E7E6E6']]],
        ];
    }
}