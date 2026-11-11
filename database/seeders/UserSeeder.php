<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'nostradamus@gmail.com',
            'password' => Hash::make('nostradamus@gmail.com'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Dosen',
            'email' => 'dosen@mail.com',
            'password' => Hash::make('dosen@mail.com'),
            'role' => 'dosen',
        ]);

        User::create([
            'name' => 'Pewawancara',
            'email' => 'pewawancara@mail.com',
            'password' => Hash::make('pewawancara@mail.com'),
            'role' => 'pewawancara',
        ]);
    }
}
