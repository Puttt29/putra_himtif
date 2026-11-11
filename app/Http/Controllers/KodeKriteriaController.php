<?php

namespace App\Http\Controllers;

use App\Models\KodeKriteria;
use Illuminate\Http\Request;

class KodeKriteriaController extends Controller
{
    // 🔹 Menampilkan daftar kode kriteria
    public function index(Request $request)
    {
        $query = KodeKriteria::query();

        // 🔍 Search
        if ($request->filled('search')) {
            $query->where('kode_kriteria', 'like', '%' . $request->search . '%');
        }

        // ↕ Sorting
        $sortable = ['kode_kriteria', 'created_at'];
        $sortBy   = in_array($request->sort_by, $sortable) ? $request->sort_by : 'created_at';
        $sortDir  = $request->sort_dir === 'asc' ? 'asc' : 'desc';

        $kodeKriterias = $query
            ->orderBy($sortBy, $sortDir)
            ->paginate(10)
            ->withQueryString();

        return view('kode_kriteria.index', compact('kodeKriterias', 'sortBy', 'sortDir'));
    }

    // 🔹 Menampilkan form tambah kode kriteria
    public function create()
    {
        return view('kode_kriteria.create');
    }

    // 🔹 Simpan data kode kriteria baru
    public function store(Request $request)
    {
        $request->validate([
            'kode_kriteria' => 'required|string|max:50|unique:kode_kriteria,kode_kriteria',
        ]);

        KodeKriteria::create([
            'kode_kriteria' => $request->kode_kriteria,
        ]);

        return redirect()->route('kode_kriteria.index')->with('success', 'Kode kriteria berhasil ditambahkan.');
    }

    // 🔹 Menampilkan form edit kode kriteria
    public function edit($id)
    {
        $kodeKriteria = KodeKriteria::findOrFail($id);
        return view('kode_kriteria.edit', compact('kodeKriteria'));
    }

    // 🔹 Update data kode kriteria
    public function update(Request $request, $id)
    {
        $kodeKriteria = KodeKriteria::findOrFail($id);

        $request->validate([
            'kode_kriteria' => 'required|string|max:50|unique:kode_kriteria,kode_kriteria,' . $id,
        ]);

        $kodeKriteria->update([
            'kode_kriteria' => $request->kode_kriteria,
        ]);

        return redirect()->route('kode_kriteria.index')->with('success', 'Kode kriteria berhasil diperbarui.');
    }

    // 🔹 Hapus kode kriteria
    public function destroy($id)
    {
        $kodeKriteria = KodeKriteria::findOrFail($id);
        $kodeKriteria->delete();

        return redirect()->route('kode_kriteria.index')->with('success', 'Kode kriteria berhasil dihapus.');
    }
}
