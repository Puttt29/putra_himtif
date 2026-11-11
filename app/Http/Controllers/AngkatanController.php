<?php

namespace App\Http\Controllers;

use App\Models\Angkatan;
use Illuminate\Http\Request;

class AngkatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $angkatan = Angkatan::orderBy('nama_angkatan')->paginate(10);

        return view('angkatan.index', compact('angkatan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('angkatan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_angkatan' => 'required|string|max:255|unique:angkatan,nama_angkatan',
        ]);

        Angkatan::create($validated);

        return redirect()
            ->route('angkatan.index')
            ->with('success', 'Angkatan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Angkatan $angkatan)
    {
        return view('angkatan.edit', compact('angkatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Angkatan $angkatan)
    {
        $validated = $request->validate([
            'nama_angkatan' => 'required|string|max:255|unique:angkatan,nama_angkatan,' . $angkatan->id,
        ]);

        $angkatan->update($validated);

        return redirect()
            ->route('angkatan.index')
            ->with('success', 'Angkatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Angkatan $angkatan)
    {
        // Proteksi opsional: jangan hapus jika sudah dipakai
        if ($angkatan->alternatif()->exists() || $angkatan->perhitunganSessions()->exists()) {
            return redirect()
                ->route('angkatan.index')
                ->with('error', 'Angkatan tidak dapat dihapus karena masih digunakan.');
        }

        $angkatan->delete();

        return redirect()
            ->route('angkatan.index')
            ->with('success', 'Angkatan berhasil dihapus.');
    }
}
