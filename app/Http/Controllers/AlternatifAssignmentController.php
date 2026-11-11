<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\User;
use Illuminate\Http\Request;

class AlternatifAssignmentController extends Controller
{
    /* ==========================
     * FORM ASSIGN PEWAWANCARA
     * ========================== */
    public function edit($alternatifId)
    {
        $alternatif = Alternatif::with(['pewawancaras', 'angkatan'])
            ->findOrFail($alternatifId);

        $pewawancaras = User::where('role', 'pewawancara')
            ->orderBy('name')
            ->get();

        return view('alternatif.assign', compact(
            'alternatif',
            'pewawancaras'
        ));
    }

    /* ==========================
     * SIMPAN ASSIGNMENT
     * ========================== */
    public function update(Request $request, $alternatifId)
    {
        $request->validate([
            'pewawancara_ids'   => 'nullable|array',
            'pewawancara_ids.*' => 'exists:users,id',
        ]);

        $alternatif = Alternatif::with('angkatan')
            ->findOrFail($alternatifId);

        // 🔹 Simpan assignment (overwrite lama)
        $alternatif->pewawancaras()->sync(
            $request->pewawancara_ids ?? []
        );

        // 🔥 REDIRECT KE HALAMAN NILAI SESUAI ANGKATAN
        return redirect()->route(
            'alternatif-nilai.index',
            ['angkatan_id' => $alternatif->angkatan_id]
        )->with('success', 'Pewawancara berhasil di-assign.');
    }

    /* ==========================
     * INDEX
     * ========================== */
    public function index(Request $request)
    {
        $alternatifs = Alternatif::with(['angkatan', 'pewawancaras'])
            ->orderBy('nama_alternatif')
            ->paginate(10);

        $pewawancaras = User::where('role', 'pewawancara')
            ->orderBy('name')
            ->get();

        return view('alternatif_assignment.index', compact(
            'alternatifs',
            'pewawancaras'
        ));
    }

    /* ==========================
     * STORE / UPDATE (SATU PINTU)
     * ========================== */
    public function store(Request $request)
    {
        $request->validate([
            'alternatif_id'     => 'required|exists:alternatif,id',
            'pewawancara_ids'   => 'nullable|array',
            'pewawancara_ids.*' => 'exists:users,id',
        ]);

        $alternatif = Alternatif::findOrFail($request->alternatif_id);

        // sync → create + update sekaligus
        $alternatif->pewawancaras()->sync(
            $request->pewawancara_ids ?? []
        );

        return back()->with('success', 'Assignment berhasil disimpan.');
    }

    /* ==========================
     * DELETE (PER PEWAWANCARA)
     * ========================== */
    public function destroy($alternatifId, $userId)
    {
        $alternatif = Alternatif::findOrFail($alternatifId);
        $alternatif->pewawancaras()->detach($userId);

        return back()->with('success', 'Assignment berhasil dihapus.');
    }
}
