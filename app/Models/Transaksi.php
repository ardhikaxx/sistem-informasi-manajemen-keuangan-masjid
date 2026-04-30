<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';

    protected $fillable = [
        'tanggal',
        'uraian',
        'jenis_transaksi',
        'jumlah',
        'saldo_sebelum',
        'saldo_sesudah',
        'keterangan',
        'aliran',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
        'saldo_sebelum' => 'decimal:2',
        'saldo_sesudah' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function histories()
    {
        return $this->hasMany(TransaksiHistory::class, 'transaksi_id');
    }

    public function scopePemasukan($query)
    {
        return $query->where('aliran', 'pemasukan');
    }

    public function scopePengeluaran($query)
    {
        return $query->where('aliran', 'pengeluaran');
    }
}
