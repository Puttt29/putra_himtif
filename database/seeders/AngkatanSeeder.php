<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Angkatan;

class AngkatanSeeder extends Seeder
{
    public function run(): void
    {
        // 🔹 Data Angkatan Tahun 2025 - 2027
        $angkatans = [
            ['nama_angkatan' => 'Angkatan 2025'],
            ['nama_angkatan' => 'Angkatan 2026'],
            ['nama_angkatan' => 'Angkatan 2027'],
        ];

        foreach ($angkatans as $data) {
            Angkatan::create($data);
        }
    }
}
