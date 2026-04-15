<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiHistory extends Model
{
    use HasFactory;

    protected $table = 'transaksi_histories';

    protected $fillable = [
        'transaksi_id',
        'admin_id',
        'action',
        'data_lama',
        'data_baru',
        'keterangan',
    ];

    protected $casts = [
        'data_lama' => 'array',
        'data_baru' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admins::class, 'admin_id');
    }

    public function getActionLabelAttribute()
    {
        $labels = [
            'create' => 'Dibuat',
            'update' => 'Diperbarui',
            'delete' => 'Dihapus'
        ];
        return $labels[$this->action] ?? $this->action;
    }

    public function getActionBadgeAttribute()
    {
        $badges = [
            'create' => 'success',
            'update' => 'warning',
            'delete' => 'danger'
        ];
        return $badges[$this->action] ?? 'secondary';
    }
}