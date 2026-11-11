<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\NilaiAlternatif;
use App\Models\Angkatan;
use App\Models\PerhitunganSession;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // =============================
        // STATISTIK UTAMA
        // =============================
        $totalAlternatif = Alternatif::count();
        $totalKriteria   = Kriteria::count();
        $totalNilai      = NilaiAlternatif::count();
        $totalAngkatan   = Angkatan::count();
        $totalPerhitunganSession = PerhitunganSession::count();

        // =============================
        // DATA TERBARU
        // =============================
        $latestAlternatif = Alternatif::latest()->take(5)->get();
        $latestSessions   = PerhitunganSession::with('angkatan')
                                ->latest()
                                ->take(5)
                                ->get();

        return view('dashboard.index', compact(
            'user',
            'totalAlternatif',
            'totalKriteria',
            'totalNilai',
            'totalAngkatan',
            'totalPerhitunganSession',
            'latestAlternatif',
            'latestSessions'
        ));
    }
}

