<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::share('breadcrumbMap', [
            'dashboard'             => 'Dashboard',
            'alternatif'            => 'Alternatif',
            'alternatif-nilai'      => 'Nilai Alternatif',
            'kode-kriteria'         => 'Kode Kriteria',
            'kriterias'             => 'Kriteria',
            'perhitungan'           => 'Perhitungan',
            'perhitungan-sessions'  => 'Riwayat Perhitungan',
            'users'                 => 'Manajemen User',
            'angkatan'              => 'Angkatan',
            'create'                => 'Tambah',
            'edit'                  => 'Edit',
        ]);
    }
}
