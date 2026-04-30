<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admins extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'admins';

    protected $fillable = [
        'nama_lengkap',
        'nomor_telfon',
        'pin',
    ];

    protected $hidden = [
        'pin',
        'remember_token',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the password for the user.
     * In this case, we use 'pin' instead of 'password'.
     * However, the AuthController uses custom logic for login, 
     * so this might not be strictly necessary unless using standard Auth features.
     */
    public function getAuthPassword()
    {
        return $this->pin;
    }
}
