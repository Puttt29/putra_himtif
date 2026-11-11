<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Angkatan;
use App\Models\Kriteria;
use App\Models\NilaiAlternatif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AlternatifNilaiTableController extends Controller
{
    /* =======================
     * INDEX
     * ======================= */
    public function index(Request $request)
    {
        $angkatans = Angkatan::orderBy('nama_angkatan')->get();
        $angkatanId = $request->angkatan_id;

        $kriterias = Kriteria::with('kodeKriteria')
            ->orderBy('id')
            ->get();

        $alternatifs = collect();
        $nilaiMap = [];

        if ($angkatanId) {

            if (Auth::user()->role === 'pewawancara') {

                // 🔥 HANYA YANG DI-ASSIGN
                $alternatifs = Auth::user()
                    ->alternatifAssigned()
                    ->where('angkatan_id', $angkatanId)
                    ->orderBy('nama_alternatif')
                    ->get();
            } else {

                // ADMIN
                $alternatifs = Alternatif::where('angkatan_id', $angkatanId)
                    ->orderBy('nama_alternatif')
                    ->get();
            }

            $nilaiMap = NilaiAlternatif::whereIn(
                'alternatif_id',
                $alternatifs->pluck('id')
            )->get()->groupBy(['alternatif_id', 'kriteria_id']);
        }

        return view('alternatif.nilai-table', compact(
            'angkatans',
            'angkatanId',
            'kriterias',
            'alternatifs',
            'nilaiMap'
        ));
    }

    /* =======================
     * STORE NILAI
     * ======================= */
    public function store(Request $request)
    {
        $request->validate([
            'nilai' => 'required|array',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->nilai as $alternatifId => $kriteriaValues) {
                foreach ($kriteriaValues as $kriteriaId => $nilai) {
                    NilaiAlternatif::updateOrCreate(
                        [
                            'alternatif_id' => $alternatifId,
                            'kriteria_id'   => $kriteriaId,
                        ],
                        [
                            'nilai' => (int) $nilai,
                        ]
                    );
                }
            }
        });

        return back()->with('success', 'Nilai berhasil disimpan.');
    }

    /* =====================================================
     * SMART METHOD
     * ===================================================== */
    private function hitungSmart(Alternatif $alternatif)
    {
        $kriterias = Kriteria::with('kodeKriteria')->orderBy('id')->get();
        $nilaiAlternatif = NilaiAlternatif::where('alternatif_id', $alternatif->id)->get();

        $totalBobot = $kriterias->sum('bobot');
        $detail = [];
        $totalNilai = 0;

        $countBaik = 0;
        $countBuruk = 0;

        foreach ($kriterias as $kriteria) {
            $nilai = (int) (
                $nilaiAlternatif
                ->firstWhere('kriteria_id', $kriteria->id)
                ->nilai ?? 0
            );

            $nilai >= 4 ? $countBaik++ : $countBuruk++;

            $bobotNormal = $totalBobot > 0
                ? $kriteria->bobot / $totalBobot
                : 0;

            $utility = $nilai / 5;
            $nilaiAkhir = $bobotNormal * $utility;

            $detail[] = [
                'kode'        => $kriteria->kodeKriteria->kode ?? '-',
                'nama'        => $kriteria->nama_kriteria,
                'bobot'       => $kriteria->bobot,
                'nilai'       => $nilai,
                'nilai_akhir' => round($nilaiAkhir, 4),
            ];

            $totalNilai += $nilaiAkhir;
        }

        return [
            'detail'      => $detail,
            'total'       => round($totalNilai, 4),
            'count_baik'  => $countBaik,
            'count_buruk' => $countBuruk,
            'klasifikasi' => $this->klasifikasiSmart($countBaik, $countBuruk),
        ];
    }

    /* =======================
     * KLASIFIKASI
     * ======================= */
    private function klasifikasiSmart(int $countBaik, int $countBuruk)
    {
        if ($countBaik >= 3) return 'LAYAK';
        if ($countBuruk >= 3) return 'TIDAK LAYAK';
        return 'PERLU PEMBINAAN';
    }

    /* =======================
     * PREVIEW
     * ======================= */
    public function preview($alternatifId)
    {
        $alternatif = Alternatif::with(['angkatan', 'pewawancaras'])->findOrFail($alternatifId);

        // 🔒 Security (tetap)
        if (
            auth()->user()->role === 'pewawancara' &&
            !$alternatif->pewawancaras->contains(auth()->id())
        ) {
            abort(403);
        }

        // 🔥 AMBIL PEWAWANCARA
        $pewawancaraNama = auth()->user()->role === 'pewawancara'
            ? auth()->user()->name
            : optional($alternatif->pewawancaras->first())->name ?? '-';

        $smart = $this->hitungSmart($alternatif);
        $logoBase64 = $this->getLogoBase64();

        return view('smart.preview', compact(
            'alternatif',
            'smart',
            'logoBase64',
            'pewawancaraNama'
        ));
    }

    /* =======================
     * DOWNLOAD PDF
     * ======================= */
    public function download($alternatifId)
    {
        $alternatif = Alternatif::with(['angkatan', 'pewawancaras'])->findOrFail($alternatifId);

        if (
            auth()->user()->role === 'pewawancara' &&
            !$alternatif->pewawancaras->contains(auth()->id())
        ) {
            abort(403);
        }

        $pewawancaraNama = auth()->user()->role === 'pewawancara'
            ? auth()->user()->name
            : optional($alternatif->pewawancaras->first())->name ?? '-';

        $smart = $this->hitungSmart($alternatif);
        $logoBase64 = $this->getLogoBase64();

        $pdf = Pdf::loadView('smart.preview', compact(
            'alternatif',
            'smart',
            'logoBase64',
            'pewawancaraNama'
        ))->setPaper('A4', 'portrait');

        return $pdf->download(
            'Laporan-Interview-' . $alternatif->nama_alternatif . '.pdf'
        );
    }

    /* =======================
     * BASE64 LOGO
     * ======================= */
    private function getLogoBase64()
    {
        $path = public_path('assets/images/HIMTIF.png');

        if (!file_exists($path)) {
            return null;
        }

        return base64_encode(file_get_contents($path));
    }
}
