<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Angkatan;
use App\Models\Kriteria;
use App\Models\NilaiAlternatif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WawancaraController extends Controller
{
    /* ======================================
     * INDEX — daftar hasil wawancara
     * ====================================== */
  public function index(Request $request)
{
    $angkatanId = $request->angkatan_id;

    $angkatans = Angkatan::orderBy('nama_angkatan')->get();

    // paginator kosong agar links() tetap bisa dipanggil
    $alternatifs = Alternatif::whereRaw('1 = 0')
        ->paginate(15)
        ->withQueryString();

    if ($angkatanId) {

        if (Auth::user()->role === 'pewawancara') {

            $alternatifs = Auth::user()
                ->alternatifAssigned()
                ->where('angkatan_id', $angkatanId)
                ->with(['nilaiAlternatif', 'angkatan'])
                ->orderBy('nama_alternatif')
                ->paginate(15)
                ->withQueryString();

        } else {

            $alternatifs = Alternatif::where('angkatan_id', $angkatanId)
                ->with(['nilaiAlternatif', 'angkatan'])
                ->orderBy('nama_alternatif')
                ->paginate(15)
                ->withQueryString();
        }
    }

    return view('wawancara.index', compact(
        'alternatifs',
        'angkatans',
        'angkatanId'
    ));
}
    /* ======================================
     * CREATE — FORM + LISTING PAGINATION
     * ====================================== */
 /* ======================================
 * CREATE — FORM + LISTING PAGINATION
 * ====================================== */
public function create(Request $request)
{
    $search      = $request->search;
    $angkatanId  = $request->filter_angkatan;
    $loadAlternatifId = $request->load_alternatif;

    $angkatans = Angkatan::orderBy('nama_angkatan')->get();

    $kriterias = Kriteria::with('kodeKriteria')
        ->orderBy('id')
        ->get();

    $nilaiMap = collect();

    // ================= QUERY =================
    if (Auth::user()->role === 'pewawancara') {
        $query = Auth::user()
            ->alternatifAssigned()
            ->with(['angkatan', 'nilaiAlternatif']);
    } else {
        $query = Alternatif::with(['angkatan', 'nilaiAlternatif']);
    }

    // 🔍 search
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('nama_alternatif', 'like', "%{$search}%")
              ->orWhere('nim', 'like', "%{$search}%");
        });
    }

    // 🎓 filter angkatan
    if ($angkatanId) {
        $query->where('angkatan_id', $angkatanId);
    }

    $alternatifTerbaru = $query
        ->orderByDesc('created_at')
        ->paginate(15)
        ->withQueryString();

    /* ======================================
     * ⭐ LOAD DATA SETELAH SIMPAN
     * ====================================== */
    $selectedAlternatif = null;

    if ($loadAlternatifId) {
        $selectedAlternatif = Alternatif::with('nilaiAlternatif')
            ->find($loadAlternatifId);
    }

    return view('wawancara.create', compact(
        'kriterias',
        'nilaiMap',
        'angkatans',
        'alternatifTerbaru',
        'search',
        'angkatanId',
        'selectedAlternatif' // ⭐ WAJIB
    ));
}

    /* ======================================
     * STORE — AUTO CREATE + UPDATE NILAI
     * ====================================== */
/* ======================================
 * STORE — AUTO CREATE + UPDATE NILAI
 * ====================================== */
public function store(Request $request)
{
    $request->validate([
        'nama_alternatif'     => 'required|string|max:255',
        'nim'                 => 'required|string|max:50',
        'angkatan_id'         => 'required|exists:angkatan,id',
        'catatan_pewawancara' => 'nullable|string',
        'nilai'               => 'required|array',
    ]);

    $alternatif = DB::transaction(function () use ($request) {

        $existingAlternatif = Alternatif::where('nim', $request->nim)->first();
        $isNew = !$existingAlternatif;

        // create atau ambil
        $alternatif = Alternatif::firstOrCreate(
            ['nim' => $request->nim],
            [
                'nama_alternatif'     => $request->nama_alternatif,
                'angkatan_id'         => $request->angkatan_id,
                'catatan_pewawancara' => $request->catatan_pewawancara,
            ]
        );

        // update jika sudah ada
        if (!$isNew) {
            $alternatif->update([
                'nama_alternatif'     => $request->nama_alternatif,
                'angkatan_id'         => $request->angkatan_id,
                'catatan_pewawancara' => $request->catatan_pewawancara,
            ]);
        }

        /* ===== AUTO ASSIGN ===== */
        if (
            Auth::user()->role === 'pewawancara' &&
            $isNew &&
            method_exists($alternatif, 'pewawancaras')
        ) {
            $alternatif->pewawancaras()
                ->syncWithoutDetaching(Auth::id());
        }

        /* ===== SECURITY ===== */
        if (
            Auth::user()->role === 'pewawancara' &&
            method_exists($alternatif, 'pewawancaras') &&
            !$alternatif->pewawancaras()
                ->where('users.id', Auth::id())
                ->exists()
        ) {
            abort(403, 'Anda tidak berhak menilai peserta ini.');
        }

        /* ===== SIMPAN NILAI ===== */
        foreach ($request->nilai as $kriteriaId => $nilai) {
            NilaiAlternatif::updateOrCreate(
                [
                    'alternatif_id' => $alternatif->id,
                    'kriteria_id'   => $kriteriaId,
                ],
                [
                    'nilai' => (int) $nilai,
                ]
            );
        }

        return $alternatif; // ⭐ PENTING
    });

    // ⭐ REDIRECT DENGAN CONTEXT
    return redirect()
        ->route('wawancara.create', [
            'load_alternatif' => $alternatif->id,
        ])
        ->with('success', 'Hasil wawancara berhasil disimpan.');
}
}