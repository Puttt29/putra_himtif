<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(KriteriaSeeder::class);
        $this->call(AngkatanSeeder::class);
        $this->call(AlternatifSeeder::class);
    }
}
