<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alternatif;

class AlternatifSeeder extends Seeder
{
    public function run(): void
    {
        $alternatifs = [
            ['nama_alternatif' => 'Aurellia Dian Hanifah', 'nim' => '241011402820'],
            ['nama_alternatif' => 'Bunga Alya Ramadhan', 'nim' => '251011402059'],
            ['nama_alternatif' => 'Dani Taufiqlirrohman', 'nim' => '251011400862'],
            ['nama_alternatif' => 'Danu Ngesti Prasetyo', 'nim' => '241011450147'],
            ['nama_alternatif' => 'Dimas Faizal Afzal', 'nim' => '241011402404'],
            ['nama_alternatif' => 'Dini Yusriah', 'nim' => '241011403194'],
            ['nama_alternatif' => 'Fairus Priyoga', 'nim' => '251011400516'],
            ['nama_alternatif' => 'Farel Adya Ridhafa', 'nim' => '251011400632'],
            ['nama_alternatif' => 'Haninda Coni Olivia', 'nim' => '251011401056'],
            ['nama_alternatif' => 'Haura Rana Hafazhah', 'nim' => '241011450159'],
            ['nama_alternatif' => 'Haya Haifa Himly', 'nim' => '251011402063'],
            ['nama_alternatif' => 'Hikbal maulana', 'nim' => '251011401605'],
            ['nama_alternatif' => 'Hikmah amalia', 'nim' => '241011402045'],
            ['nama_alternatif' => 'Ibnu salam yakin', 'nim' => '241011401334'],
            ['nama_alternatif' => 'Ibnu syuhada suffera', 'nim' => '241011401260'],
            ['nama_alternatif' => 'Juan alfredo ferryera situmorang', 'nim' => '241011403056'],
            ['nama_alternatif' => 'Khairunnisa syafitri', 'nim' => '241011400718'],
            ['nama_alternatif' => 'Laras ayu setyorini', 'nim' => '251011400283'],
            ['nama_alternatif' => 'Michelle aniela wijaya', 'nim' => '241011402771'],
            ['nama_alternatif' => 'Miftakhul fal\'ah', 'nim' => '241011400237'],
            ['nama_alternatif' => 'Muhammad albuhori', 'nim' => '251011401164'],
            ['nama_alternatif' => 'Muhammad hilmi hunandar', 'nim' => '251011400807'],
            ['nama_alternatif' => 'Muhammad Perdiansyah', 'nim' => '251011402109'],
            ['nama_alternatif' => 'Muhammad yusuf aji maulana', 'nim' => '251011401892'],
            ['nama_alternatif' => 'Oktanaka', 'nim' => '251011401709'],
            ['nama_alternatif' => 'Pilomena fransiska', 'nim' => '251011401688'],
            ['nama_alternatif' => 'Putra Wijaya', 'nim' => '251011400637'],
            ['nama_alternatif' => 'Rafal Rialdi', 'nim' => '241011401715'],
            ['nama_alternatif' => 'Rahmat Syafa Firdaus', 'nim' => '241011402804'],
            ['nama_alternatif' => 'Rifqi Atha Ramadhan', 'nim' => '241011450102'],
            ['nama_alternatif' => 'Rifqi ramdhan', 'nim' => '241011401857'],
            ['nama_alternatif' => 'Rizki revaldo', 'nim' => '241011450273'],
            ['nama_alternatif' => 'Rizqi komarullail', 'nim' => '241011400355'],
            ['nama_alternatif' => 'Salsabillah', 'nim' => '241011402651'],
            ['nama_alternatif' => 'Sela nabila', 'nim' => '251011401515'],
            ['nama_alternatif' => 'Syaufa rajani gifani', 'nim' => '241011403152'],
            ['nama_alternatif' => 'Wahyu ahmad', 'nim' => '251011400820'],
            ['nama_alternatif' => 'Zaid naufan', 'nim' => '251011401523'],
            ['nama_alternatif' => 'Zaqi ibnu gumanti', 'nim' => '251011401224'],
            ['nama_alternatif' => 'Zaydan afif fadhilah', 'nim' => '251011402367'],
        ];

        foreach ($alternatifs as $data) {
            Alternatif::create([
                'nama_alternatif' => $data['nama_alternatif'],
                'nim'             => $data['nim'],
                'angkatan_id'     => 1,
                'user_id'         => 1,
            ]);
        }
    }
}
