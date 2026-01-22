<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admins;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admins::create([
            'nama_lengkap' => 'Administrator',
            'nomor_telfon' => '081234567890',
            'pin' => '2222',
        ]);

        Admins::create([
            'nama_lengkap' => 'Bendahara Masjid',
            'nomor_telfon' => '082345678901',
            'pin' => '3333',
        ]);
    }
}