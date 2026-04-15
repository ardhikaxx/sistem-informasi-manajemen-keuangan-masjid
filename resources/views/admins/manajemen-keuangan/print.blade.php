<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi - {{ $transaksi->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            width: 280px;
            padding: 15px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #1D8A4E;
        }
        .header h2 { color: #1D8A4E; font-size: 16px; margin-bottom: 3px; }
        .header p { font-size: 10px; color: #666; }
        .trans-info { margin-bottom: 15px; }
        .trans-info table { width: 100%; }
        .trans-info td { padding: 3px 0; font-size: 11px; }
        .trans-info td:first-child { font-weight: 600; width: 90px; color: #555; }
        .divider { border-top: 1px dashed #ccc; margin: 15px 0; }
        .amount { text-align: center; padding: 15px 0; }
        .amount .label { font-size: 11px; color: #666; margin-bottom: 5px; }
        .amount .value { font-size: 22px; font-weight: bold; }
        .amount .type { font-size: 12px; font-weight: 600; margin-top: 5px; padding: 3px 10px; border-radius: 3px; display: inline-block; }
        .income { color: #27AE60; }
        .income-bg { background: #E8F5E9; color: #27AE60; }
        .expense { color: #E74C3C; }
        .expense-bg { background: #FDEDEC; color: #E74C3C; }
        .footer { text-align: center; margin-top: 20px; padding-top: 10px; border-top: 1px dashed #ccc; font-size: 10px; color: #888; }
        .footer p { margin-bottom: 3px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>KEUANGAN MASJID</h2>
        <p>Bukti Transaksi Keuangan</p>
    </div>

    <div class="trans-info">
        <table>
            <tr><td>No. Transaksi</td><td>: {{ $transaksi->id }}</td></tr>
            <tr><td>Tanggal</td><td>: {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}</td></tr>
            <tr><td>Uraian</td><td>: {{ $transaksi->uraian }}</td></tr>
            <tr><td>Aliran Kas</td><td>: {{ $transaksi->aliran }}</td></tr>
            @if($transaksi->keterangan)
            <tr><td>Keterangan</td><td>: {{ $transaksi->keterangan }}</td></tr>
            @endif
        </table>
    </div>

    <div class="divider"></div>

    <div class="amount">
        <div class="label">JUMLAH</div>
        <div class="value {{ $transaksi->jenis_transaksi == 'pemasukan' ? 'income' : 'expense' }}">Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</div>
        <div class="type {{ $transaksi->jenis_transaksi == 'pemasukan' ? 'income-bg' : 'expense-bg' }}">{{ strtoupper($transaksi->jenis_transaksi) }}</div>
    </div>

    <div class="divider"></div>

    <div class="trans-info">
        <table>
            <tr><td>Saldo Sebelum</td><td>: Rp {{ number_format($transaksi->saldo_sebelum, 0, ',', '.') }}</td></tr>
            <tr><td>Saldo Sesudah</td><td>: Rp {{ number_format($transaksi->saldo_sesudah, 0, ',', '.') }}</td></tr>
        </table>
    </div>

    <div class="footer">
        <p>Terima kasih atas kepercayaan Anda</p>
        <p>{{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>