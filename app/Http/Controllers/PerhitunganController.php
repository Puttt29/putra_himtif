<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Angkatan;
use App\Models\Kriteria;
use App\Models\Perhitungan;
use App\Models\PerhitunganSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerhitunganController extends Controller
{
    public function index(Request $request)
    {
        $angkatanId   = $request->get('angkatan_id');
        $angkatanList = Angkatan::orderBy('nama_angkatan')->get();
        $kriterias    = Kriteria::orderBy('id')->get();

        $alternatifs = collect();
        $ranking     = collect();
        $session     = null;

        if ($angkatanId) {

            $alternatifs = Alternatif::with('nilaiAlternatif')
                ->where('angkatan_id', $angkatanId)
                ->get();

            if ($request->boolean('hitung')) {

                $session = DB::transaction(function () use (
                    $angkatanId,
                    $kriterias,
                    $alternatifs
                ) {

                    $session = PerhitunganSession::create([
                        'nama_perhitungan' => 'Perhitungan ' . now()->format('d-m-Y H:i'),
                        'angkatan_id'      => $angkatanId,
                        'tanggal_hitung'   => now(),
                    ]);

                    $totalBobot = $kriterias->sum('bobot');

                    $bobotNormal = $kriterias->mapWithKeys(fn($k) => [
                        $k->id => $totalBobot > 0 ? round($k->bobot / $totalBobot, 4) : 0
                    ]);

                    $nilaiMax = [];
                    $nilaiMin = [];

                    foreach ($kriterias as $kriteria) {
                        $nilai = $alternatifs->map(
                            fn($alt) =>
                                $alt->nilaiAlternatif
                                    ->firstWhere('kriteria_id', $kriteria->id)
                                    ?->nilai ?? 0
                        );

                        $nilaiMax[$kriteria->id] = $nilai->max();
                        $nilaiMin[$kriteria->id] = $nilai->min();
                    }

                    foreach ($alternatifs as $alt) {
                        foreach ($kriterias as $kriteria) {

                            $nilai = $alt->nilaiAlternatif
                                ->firstWhere('kriteria_id', $kriteria->id)
                                ?->nilai ?? 0;

                            $range = $nilaiMax[$kriteria->id] - $nilaiMin[$kriteria->id];

                            $utilitas = $range == 0
                                ? 1
                                : (
                                    $kriteria->atribut === 'benefit'
                                    ? ($nilai - $nilaiMin[$kriteria->id]) / $range
                                    : ($nilaiMax[$kriteria->id] - $nilai) / $range
                                );

                            $utilitas   = round($utilitas, 4);
                            $nilaiAkhir = round(
                                $utilitas * $bobotNormal[$kriteria->id],
                                4
                            );

                            Perhitungan::create([
                                'perhitungan_session_id' => $session->id,
                                'alternatif_id'          => $alt->id,
                                'kriteria_id'            => $kriteria->id,
                                'nilai_utilitas'         => $utilitas,
                                'nilai_akhir'            => $nilaiAkhir,
                            ]);
                        }
                    }

                    return $session;
                });

                $alternatifs->load([
                    'perhitungan' => fn($q) =>
                        $q->where('perhitungan_session_id', $session->id)
                ]);
            }

            if ($session) {
                $ranking = $alternatifs
                    ->map(fn($alt) => [
                        'alternatif_id'   => $alt->id,
                        'nama_alternatif' => $alt->nama_alternatif,
                        'total_nilai'     => round($alt->perhitungan->sum('nilai_akhir'), 4),
                    ])
                    ->sortByDesc('total_nilai')
                    ->values()
                    ->map(function ($row, $i) {

                        $total = $row['total_nilai'];

                        if ($total >= 0.75) {
                            $klasifikasi = 'Layak';
                        } elseif ($total >= 0.50) {
                            $klasifikasi = 'Perlu Pembinaan';
                        } else {
                            $klasifikasi = 'Tidak Layak';
                        }

                        return $row + [
                            'ranking'     => $i + 1,
                            'klasifikasi' => $klasifikasi,
                        ];
                    });
            }
        }

        return view('perhitungan.index', compact(
            'angkatanList',
            'angkatanId',
            'alternatifs',
            'kriterias',
            'ranking',
            'session'
        ));
    }
}
