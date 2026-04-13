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
    <h1>Sistem Keuangan Masjid Jami' Al-Muttaqiin</h1>
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
