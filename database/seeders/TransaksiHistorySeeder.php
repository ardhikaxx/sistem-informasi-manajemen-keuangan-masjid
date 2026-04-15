<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi;

class TransaksiHistorySeeder extends Seeder
{
    public function run(): void
    {
        // Ambil beberapa transaksi untuk dibuatkan history
        $transaksis = Transaksi::limit(10)->get();

        if ($transaksis->isEmpty()) {
            $this->command->info('Tidak ada transaksi untuk dibuatkan riwayat. Jalankan TransaksiSeeder terlebih dahulu.');
            return;
        }

        $histories = [];
        
        foreach ($transaksis as $transaksi) {
            // History untuk create
            $histories[] = [
                'transaksi_id' => $transaksi->id,
                'admin_id' => 1,
                'action' => 'create',
                'data_lama' => null,
                'data_baru' => json_encode([
                    'tanggal' => $transaksi->tanggal,
                    'uraian' => $transaksi->uraian,
                    'jenis_transaksi' => $transaksi->jenis_transaksi,
                    'jumlah' => $transaksi->jumlah,
                    'aliran' => $transaksi->aliran,
                    'keterangan' => $transaksi->keterangan
                ]),
                'keterangan' => 'Transaksi baru ditambahkan',
                'created_at' => $transaksi->created_at,
                'updated_at' => $transaksi->created_at,
            ];

            // Tambahkan history update untuk beberapa transaksi (acak)
            if (rand(0, 1)) {
                $histories[] = [
                    'transaksi_id' => $transaksi->id,
                    'admin_id' => 1,
                    'action' => 'update',
                    'data_lama' => json_encode([
                        'jumlah' => $transaksi->jumlah,
                        'keterangan' => '-'
                    ]),
                    'data_baru' => json_encode([
                        'jumlah' => $transaksi->jumlah,
                        'keterangan' => 'Diperbarui via sistem'
                    ]),
                    'keterangan' => 'Transaksi diperbarui',
                    'created_at' => $transaksi->created_at->addHours(rand(1, 24)),
                    'updated_at' => $transaksi->created_at->addHours(rand(1, 24)),
                ];
            }
        }

        DB::table('transaksi_histories')->insert($histories);
        
        $this->command->info('Berhasil membuat ' . count($histories) . ' riwayat transaksi!');
    }
}