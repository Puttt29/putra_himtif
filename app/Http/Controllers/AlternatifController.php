<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Angkatan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class AlternatifController extends Controller
{
    public function index(Request $request)
    {
        $query = Alternatif::with('angkatan');

        // FILTER BY ANGKATAN
        if ($request->filled('angkatan_id')) {
            $query->where('angkatan_id', $request->angkatan_id);
        }

        // SEARCH
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_alternatif', 'like', '%' . $request->search . '%')
                    ->orWhere('nim', 'like', '%' . $request->search . '%')
                    ->orWhereHas('angkatan', function ($sub) use ($request) {
                        $sub->where('nama_angkatan', 'like', '%' . $request->search . '%');
                    });
            });
        }

        // SORTING
        $allowedSort = [
            'nama'     => 'nama_alternatif',
            'nim'      => 'nim',
            'angkatan' => 'angkatan_id',
            'created'  => 'created_at',
        ];

        $sortBy  = $allowedSort[$request->sort_by] ?? 'nama_alternatif';
        $sortDir = $request->sort_dir === 'desc' ? 'desc' : 'asc';

        $alternatifs = $query
            ->orderBy($sortBy, $sortDir)
            ->paginate(15)
            ->withQueryString();

        $angkatans = Angkatan::orderBy('nama_angkatan')->get();

        return view('alternatif.index', compact(
            'alternatifs',
            'angkatans',
            'sortBy',
            'sortDir'
        ));
    }

    public function create()
    {
        $angkatans = Angkatan::orderBy('nama_angkatan')->get();
        return view('alternatif.create', compact('angkatans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_alternatif' => [
                'required',
                'string',
                'max:100',
                'unique:alternatif,nama_alternatif',
            ],
            'nim' => [
                'nullable',
                'string',
                'max:50',
                'unique:alternatif,nim',
            ],
            'angkatan_id' => [
                'required',
                'exists:angkatan,id',
            ],
        ]);

        Alternatif::create([
            'nama_alternatif' => $validated['nama_alternatif'],
            'nim'             => $validated['nim'] ?? null,
            'angkatan_id'     => $validated['angkatan_id'],
            'user_id'         => Auth::id(),
        ]);

        /**
         * ==========================================
         * REDIRECT CERDAS BERDASARKAN URL SEBELUMNYA
         * ==========================================
         */
        if (
            url()->previous() === route('alternatif-nilai.index', [], false)
            || str_contains(url()->previous(), 'alternatif-nilai')
        ) {

            return redirect()
                ->route('alternatif-nilai.index', [
                    'angkatan_id' => $validated['angkatan_id']
                ])
                ->with('success', 'Alternatif berhasil ditambahkan.');
        }

        return redirect()
            ->route('alternatif.index')
            ->with('success', 'Alternatif berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $alternatif = Alternatif::findOrFail($id);
        $angkatans  = Angkatan::orderBy('nama_angkatan')->get();

        return view('alternatif.edit', compact('alternatif', 'angkatans'));
    }

    public function update(Request $request, $id)
    {
        $alternatif = Alternatif::findOrFail($id);

        $validated = $request->validate([
            'nama_alternatif' => [
                'required',
                'string',
                'max:100',
                Rule::unique('alternatif', 'nama_alternatif')->ignore($alternatif->id),
            ],
            'nim' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('alternatif', 'nim')->ignore($alternatif->id),
            ],
            'angkatan_id' => [
                'required',
                'exists:angkatan,id',
            ],
        ]);

        $alternatif->update([
            'nama_alternatif' => $validated['nama_alternatif'],
            'nim'             => $validated['nim'] ?? null,
            'angkatan_id'     => $validated['angkatan_id'],
        ]);

        return redirect()
            ->route('alternatif.index')
            ->with('success', 'Alternatif berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $alternatif = Alternatif::findOrFail($id);
        $alternatif->delete();

        return redirect()
            ->route('alternatif.index')
            ->with('success', 'Alternatif berhasil dihapus.');
    }
}
