<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KodeKriteria;
use App\Models\Kriteria;

class KriteriaSeeder extends Seeder
{
    public function run(): void
    {
        // 🔹 Data Kode Kriteria (C1–C5)
        $kodeKriterias = [
            ['kode_kriteria' => 'C1'],
            ['kode_kriteria' => 'C2'],
            ['kode_kriteria' => 'C3'],
            ['kode_kriteria' => 'C4'],
            ['kode_kriteria' => 'C5'],
        ];

        foreach ($kodeKriterias as $kode) {
            KodeKriteria::create($kode);
        }

        // 🔹 Data Kriteria
        $kriterias = [
            [
                'kode_kriteria_id' => 1,
                'nama_kriteria'    => 'Motivasi',
                'atribut'          => 'benefit',
                'bobot'            => 25,
                'normalisasi'      => 0.25,
            ],
            [
                'kode_kriteria_id' => 2,
                'nama_kriteria'    => 'Komitmen',
                'atribut'          => 'benefit',
                'bobot'            => 20,
                'normalisasi'      => 0.20,
            ],
            [
                'kode_kriteria_id' => 3,
                'nama_kriteria'    => 'Ketekunan',
                'atribut'          => 'benefit',
                'bobot'            => 20,
                'normalisasi'      => 0.20,
            ],
            [
                'kode_kriteria_id' => 4,
                'nama_kriteria'    => 'Kesiapan Diri',
                'atribut'          => 'benefit',
                'bobot'            => 15,
                'normalisasi'      => 0.15,
            ],
            [
                'kode_kriteria_id' => 5,
                'nama_kriteria'    => 'Sikap',
                'atribut'          => 'benefit',
                'bobot'            => 20,
                'normalisasi'      => 0.20,
            ],
        ];

        foreach ($kriterias as $data) {
            Kriteria::create($data);
        }
    }
}
