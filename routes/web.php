<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\AlternatifNilaiController;
use App\Http\Controllers\AlternatifNilaiTableController;
use App\Http\Controllers\KodeKriteriaController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\PerhitunganController;
use App\Http\Controllers\PerhitunganSessionController;
use App\Http\Controllers\AngkatanController;
use App\Http\Controllers\AlternatifAssignmentController;
use App\Http\Controllers\WawancaraController;

/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
*/

// =====================
// ROOT
// =====================
Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

// =====================
// AUTH
// =====================
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.process');
    Route::post('/logout', 'logout')->name('logout');
});

// =====================
// AUTHENTICATED AREA (SEMUA ROLE)
// =====================
Route::middleware('auth.middleware')->group(function () {

    // =====================
    // WAWANCARA
    // =====================
    Route::prefix('wawancara')->name('wawancara.')->group(function () {
        Route::get('/', [WawancaraController::class, 'index'])->name('index');
        Route::get('/create', [WawancaraController::class, 'create'])->name('create');
        Route::post('/store', [WawancaraController::class, 'store'])->name('store');
    });

    // =====================
    // DASHBOARD
    // =====================
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // =====================
    // ALTERNATIF (SEMUA ROLE)
    // =====================
    Route::resource('alternatif', AlternatifController::class)
        ->except(['show']);

    Route::prefix('alternatif-nilai')
        ->name('alternatif-nilai.')
        ->group(function () {

            Route::get('/', [AlternatifNilaiTableController::class, 'index'])
                ->name('index');

            Route::post('/', [AlternatifNilaiTableController::class, 'store'])
                ->name('store');

            // PREVIEW 1 ALTERNATIF
            Route::get(
                '/{alternatif}/preview',
                [AlternatifNilaiTableController::class, 'preview']
            )->name('preview');

            // CETAK / DOWNLOAD PDF
            Route::get(
                '/{alternatif}/cetak',
                [AlternatifNilaiTableController::class, 'download']
            )->name('cetak');
        });

    // =====================
    // PERHITUNGAN (SEMUA ROLE)
    // =====================
    Route::get('/perhitungan', [PerhitunganController::class, 'index'])
        ->name('perhitungan.index');

    Route::prefix('perhitungan-sessions')
        ->name('perhitungan-sessions.')
        ->group(function () {

            Route::get('/', [PerhitunganSessionController::class, 'index'])
                ->name('index');

            Route::get('/{id}', [PerhitunganSessionController::class, 'show'])
                ->name('show');

            Route::put('/{id}', [PerhitunganSessionController::class, 'update'])
                ->name('update');

            Route::delete('/{id}', [PerhitunganSessionController::class, 'destroy'])
                ->name('destroy');

            // Download Excel hanya Administrator
            Route::middleware('auth.middleware:admin')->group(function () {
                Route::get('/{id}/excel', [PerhitunganSessionController::class, 'downloadExcel'])
                    ->name('excel');
            });
        });

    // =====================
    // KRITERIA
    // ADMIN & DOSEN SAJA
    // =====================
    Route::middleware('auth.middleware:admin,dosen')->group(function () {

        Route::resource('kode-kriteria', KodeKriteriaController::class)
            ->except(['show'])
            ->names('kode_kriteria');

        Route::resource('kriterias', KriteriaController::class)
            ->except(['show'])
            ->names('kriteria');

        Route::get(
            'alternatif/{alternatif}/assign-pewawancara',
            [AlternatifAssignmentController::class, 'edit']
        )->name('alternatif.assign');

        Route::put(
            'alternatif/{alternatif}/assign-pewawancara',
            [AlternatifAssignmentController::class, 'update']
        )->name('alternatif.assign.update');


        Route::get(
            'alternatif-assignment',
            [AlternatifAssignmentController::class, 'index']
        )->name('alternatif-assignment.index');

        Route::post(
            'alternatif-assignment',
            [AlternatifAssignmentController::class, 'store']
        )->name('alternatif-assignment.store');

        Route::delete(
            'alternatif-assignment/{alternatif}/{user}',
            [AlternatifAssignmentController::class, 'destroy']
        )->name('alternatif-assignment.destroy');
    });

    // =====================
    // ADMIN ONLY
    // =====================
    Route::middleware('auth.middleware:admin')->group(function () {

        Route::resource('users', UserManagementController::class)
            ->except(['show']);

        Route::resource('angkatan', AngkatanController::class)
            ->except(['show']);
    });
});
