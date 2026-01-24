<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Ambil tahun saat ini
        $currentYear = now()->year; // Ini akan menghasilkan 2026

        // Definisikan contoh data Uraian dan Keterangan dalam Bahasa Indonesia
        $uraianPemasukan = [
            'Donasi Rutin Jamaah',
            'Infaq Kotak Amal',
            'Sumbangan Khitanan Anak',
            'Penjualan Buku Agama',
            'Hasil Panitia Tablig Akbar',
            'Donasi dari Keluarga Almarhum',
            'Iuran Warga Lingkungan',
            'Sumbangan Lebaran Idul Fitri',
            'Donasi Kegiatan Ramadhan',
            'Pendapatan Jual Beli Takjil',
            'Sumbangan Dana Renovasi',
            'Tabungan Anak-anak',
            'Donasi Pernikahan Jamaah',
            'Pendapatan Sewa Gedung',
            'Kotak Infaq Hari Raya',
            'Donasi Uang Saku Jamaah',
            'Pendapatan Bazar Amal',
            'Sumbaran Dana Kemanusiaan',
            'Donasi Pembayaran Listrik',
            'Hasil Galang Dana Bencana'
        ];

        $uraianPengeluaran = [
            'Biaya Listrik Bulanan',
            'Biaya Air Bulanan',
            'Pembayaran Gaji Imam',
            'Pembayaran Gaji Marbot',
            'Pembelian Perlengkapan Sholat',
            'Pembelian Makanan Untuk Jamaah',
            'Biaya Pemeliharaan AC',
            'Biaya Perawatan Mobil/Motor',
            'Biaya Pembersihan Masjid',
            'Biaya Penerangan Jalan',
            'Pembelian Perlengkapan Kebersihan',
            'Biaya Listrik Kantor',
            'Biaya Pemeliharaan Sound System',
            'Pembayaran Telepon',
            'Biaya Makan Jamaah',
            'Biaya Operasional Ibadah Kurban',
            'Pembelian Tikar dan Karpet',
            'Biaya Perbaikan Atap',
            'Biaya Obat-obatan P3K',
            'Biaya Bazar Amal'
        ];

        // Generate data transaksi tanpa saldo
        $transaksiData = [];
        for ($i = 0; $i < 50; $i++) {
            $randomMonth = rand(1, 12);
            $randomDay = rand(1, cal_days_in_month(CAL_GREGORIAN, $randomMonth, $currentYear));
            $tanggal = sprintf("%04d-%02d-%02d", $currentYear, $randomMonth, $randomDay);

            $jenis_transaksi = $faker->randomElement(['pemasukan', 'pengeluaran']);
            $uraian_pool = $jenis_transaksi === 'pemasukan' ? $uraianPemasukan : $uraianPengeluaran;
            $uraian = $faker->randomElement($uraian_pool);
            $jumlah = $faker->numberBetween(50000, 5000000);

            $aliran = $faker->randomElement([
                'Aktivitas Operasi',
                'Aktivitas Investasi',
                'Aktivitas Pendanaan',
                'Aktivitas Pendanaan Lain'
            ]);

            $transaksiData[] = [
                'tanggal' => $tanggal,
                'uraian' => $uraian,
                'jenis_transaksi' => $jenis_transaksi,
                'jumlah' => $jumlah,
                'saldo_sebelum' => 0, // Akan diisi nanti
                'saldo_sesudah' => 0, // Akan diisi nanti
                'aliran' => $aliran,
                'keterangan' => '-',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Urutkan data berdasarkan tanggal ASCENDING (dari paling lama ke paling baru)
        usort($transaksiData, function ($a, $b) {
            return strcmp($a['tanggal'], $b['tanggal']);
        });

        // Inisialisasi saldo awal
        $currentBalance = 0;

        // Iterasi data transaksi yang sudah diurutkan untuk menghitung saldo
        foreach ($transaksiData as $index => &$trx) {
            // Saldo sebelum adalah saldo saat ini
            $trx['saldo_sebelum'] = $currentBalance;

            // Jika transaksi pertama adalah pengeluaran dan saldo 0, ubah ke pemasukan kecil
            if ($index === 0 && $currentBalance === 0 && $trx['jenis_transaksi'] === 'pengeluaran') {
                $trx['jenis_transaksi'] = 'pemasukan';
                $trx['jumlah'] = max(50000, $trx['jumlah']);
            }

            // Jika jenisnya pengeluaran dan saldo tidak mencukupi, ubah ke pemasukan kecil
            if ($trx['jenis_transaksi'] === 'pengeluaran' && $currentBalance < $trx['jumlah']) {
                $trx['jenis_transaksi'] = 'pemasukan';
                $trx['jumlah'] = max(50000, $trx['jumlah']);
            }

            // Hitung saldo sesudah berdasarkan jenis transaksi
            if ($trx['jenis_transaksi'] === 'pemasukan') {
                $currentBalance += $trx['jumlah'];
            } else { // pengeluaran
                $currentBalance -= $trx['jumlah'];
            }

            $trx['saldo_sesudah'] = $currentBalance;
        }

        // Hapus referensi ke item terakhir agar tidak merubah array lagi
        unset($trx);

        // Masukkan semua data ke tabel
        DB::table('transaksis')->insert($transaksiData);
    }
}