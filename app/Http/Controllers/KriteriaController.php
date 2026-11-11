<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\KodeKriteria;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KriteriaController extends Controller
{
    // ===============================
    // INDEX
    // ===============================
    public function index(Request $request)
    {
        $query = Kriteria::with('kodeKriteria');

        // ===============================
        // SEARCH (Nama Kriteria & Kode)
        // ===============================
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_kriteria', 'like', '%' . $request->search . '%')
                    ->orWhereHas('kodeKriteria', function ($sub) use ($request) {
                        $sub->where('kode_kriteria', 'like', '%' . $request->search . '%');
                    });
            });
        }

        // ===============================
        // SORTING
        // ===============================
        $allowedSort = [
            'kode'         => 'kode_kriteria_id',
            'nama'         => 'nama_kriteria',
            'atribut'      => 'atribut',
            'bobot'        => 'bobot',
            'normalisasi'  => 'normalisasi',
            'created_at'   => 'created_at',
        ];

        $sortBy  = $allowedSort[$request->sort_by] ?? 'kode_kriteria_id';
        $sortDir = $request->sort_dir === 'asc' ? 'asc' : 'desc';

        $kriterias = $query
            ->orderBy($sortBy, $sortDir)
            ->paginate(10)
            ->withQueryString();

        return view('kriteria.index', compact(
            'kriterias',
            'sortBy',
            'sortDir'
        ));
    }

    // ===============================
    // CREATE
    // ===============================
    public function create()
    {
        $kodeKriterias = KodeKriteria::orderBy('kode_kriteria')->get();
        return view('kriteria.create', compact('kodeKriterias'));
    }

    // ===============================
    // STORE
    // ===============================
    public function store(Request $request)
    {
        $request->validate([
            'kode_kriteria_id' => 'required|exists:kode_kriteria,id',
            'nama_kriteria'    => 'required|string|max:100',
            'atribut'          => 'required|in:benefit,cost',
            'bobot'            => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {

            Kriteria::create([
                'kode_kriteria_id' => $request->kode_kriteria_id,
                'nama_kriteria'    => $request->nama_kriteria,
                'atribut'          => $request->atribut,
                'bobot'            => $request->bobot,
                'normalisasi'      => 0,
            ]);

            $this->hitungNormalisasiGlobal();
        });

        return redirect()
            ->route('kriteria.index')
            ->with('success', 'Kriteria berhasil ditambahkan dan dinormalisasi.');
    }

    // ===============================
    // EDIT
    // ===============================
    public function edit($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        $kodeKriterias = KodeKriteria::orderBy('kode_kriteria')->get();

        return view('kriteria.edit', compact('kriteria', 'kodeKriterias'));
    }

    // ===============================
    // UPDATE
    // ===============================
    public function update(Request $request, $id)
    {
        $kriteria = Kriteria::findOrFail($id);

        $request->validate([
            'kode_kriteria_id' => 'required|exists:kode_kriteria,id',
            'nama_kriteria'    => 'required|string|max:100',
            'atribut'          => 'required|in:benefit,cost',
            'bobot'            => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $kriteria) {

            $kriteria->update([
                'kode_kriteria_id' => $request->kode_kriteria_id,
                'nama_kriteria'    => $request->nama_kriteria,
                'atribut'          => $request->atribut,
                'bobot'            => $request->bobot,
            ]);

            $this->hitungNormalisasiGlobal();
        });

        return redirect()
            ->route('kriteria.index')
            ->with('success', 'Kriteria berhasil diperbarui dan dinormalisasi.');
    }

    // ===============================
    // DESTROY
    // ===============================
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            Kriteria::findOrFail($id)->delete();
            $this->hitungNormalisasiGlobal();
        });

        return redirect()
            ->route('kriteria.index')
            ->with('success', 'Kriteria berhasil dihapus dan dinormalisasi ulang.');
    }

    // ===============================
    // NORMALISASI GLOBAL (CORE)
    // ===============================
    private function hitungNormalisasiGlobal()
    {
        $totalBobot = Kriteria::sum('bobot');

        if ($totalBobot <= 0) {
            Kriteria::query()->update(['normalisasi' => 0]);
            return;
        }

        Kriteria::all()->each(function ($kriteria) use ($totalBobot) {
            $kriteria->update([
                'normalisasi' => $kriteria->bobot / $totalBobot
            ]);
        });
    }
}
