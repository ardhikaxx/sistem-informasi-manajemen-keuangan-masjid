<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
        'saldo_sebelum' => 'decimal:2',
        'saldo_sesudah' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Scopes
    public function scopePemasukan($query)
    {
        return $query->where('jenis_transaksi', 'pemasukan');
    }

    public function scopePengeluaran($query)
    {
        return $query->where('jenis_transaksi', 'pengeluaran');
    }

    public function scopeBulanIni($query)
    {
        return $query->whereYear('tanggal', Carbon::now()->year)
            ->whereMonth('tanggal', Carbon::now()->month);
    }

    public function scopeTahunIni($query)
    {
        return $query->whereYear('tanggal', Carbon::now()->year);
    }

    public function scopeByPeriod($query, $bulan = null, $tahun = null)
    {
        if ($tahun) {
            $query->whereYear('tanggal', $tahun);
        }
        if ($bulan) {
            $query->whereMonth('tanggal', $bulan);
        }
        return $query;
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('uraian', 'like', "%{$keyword}%")
                ->orWhere('keterangan', 'like', "%{$keyword}%");
        });
    }

    // Accessors
    public function getFormattedJumlahAttribute()
    {
        return 'Rp ' . number_format((float) $this->jumlah, 0, ',', '.');
    }

    public function getFormattedSaldoSebelumAttribute()
    {
        return 'Rp ' . number_format((float) $this->saldo_sebelum, 0, ',', '.');
    }

    public function getFormattedSaldoSesudahAttribute()
    {
        return 'Rp ' . number_format((float) $this->saldo_sesudah, 0, ',', '.');
    }

    public function getFormattedTanggalAttribute()
    {
        return Carbon::parse($this->tanggal)->format('d/m/Y');
    }

    // Methods
    public function hitungSaldo()
    {
        // Ambil transaksi sebelum tanggal ini
        $query = self::where('tanggal', '<=', $this->tanggal)
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc');

        // Jika transaksi sudah ada (update), exclude transaksi ini
        if ($this->id) {
            $query->where('id', '<', $this->id);
        }

        $transaksiTerakhir = $query->first();

        $this->saldo_sebelum = $transaksiTerakhir ? $transaksiTerakhir->saldo_sesudah : 0;

        if ($this->jenis_transaksi === 'pemasukan') {
            $this->saldo_sesudah = $this->saldo_sebelum + $this->jumlah;
        } else {
            $this->saldo_sesudah = $this->saldo_sebelum - $this->jumlah;
        }
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaksi) {
            $transaksi->hitungSaldo();
        });

        static::updating(function ($transaksi) {
            $transaksi->hitungSaldo();
        });

        static::created(function ($transaksi) {
            $transaksi->updateSaldoSetelahnya();
        });

        static::updated(function ($transaksi) {
            $transaksi->updateSaldoSetelahnya();
        });

        static::deleted(function ($transaksi) {
            $transaksi->updateSaldoSetelahnya();
        });
    }

    public function updateSaldoSetelahnya()
    {
        // Ambil semua transaksi setelah tanggal ini
        $transaksiSetelahnya = self::where('tanggal', '>=', $this->tanggal)
            ->where('id', '>', $this->id)
            ->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // Update saldo untuk setiap transaksi setelahnya
        foreach ($transaksiSetelahnya as $transaksi) {
            $transaksi->hitungSaldo();
            $transaksi->saveQuietly();
        }
    }
}