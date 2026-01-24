<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('uraian', 255);
            $table->enum('jenis_transaksi', ['pemasukan', 'pengeluaran']);
            $table->decimal('jumlah', 15, 2);
            $table->decimal('saldo_sebelum', 15, 2)->default(0);
            $table->decimal('saldo_sesudah', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            $table->index('tanggal');
            $table->index('jenis_transaksi');
            $table->index(['tanggal', 'jenis_transaksi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};