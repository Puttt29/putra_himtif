@extends('layouts.app')

@section('content')
<section class="bg-surface rounded-2xl p-6 shadow-sm border border-black/5 space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold">Manajemen Kriteria</h2>
            <p class="text-sm text-textmuted mt-1">
                Kelola kriteria, atribut, bobot, dan normalisasi
            </p>
        </div>

        <a href="{{ route('kriteria.create') }}"
            class="px-4 py-2 rounded-lg bg-accent text-white hover:bg-accentHover transition text-sm font-medium">
            + Tambah Kriteria
        </a>
    </div>

    {{-- FLASH --}}
    @if (session('success'))
        <div class="px-4 py-3 rounded-lg bg-green-50 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- FILTER : SEARCH + SORT --}}
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">

        {{-- SEARCH --}}
        <div class="sm:col-span-2">
            <label class="text-sm text-textmuted mb-1 block">Pencarian</label>
            <input type="text" name="search"
                value="{{ request('search') }}"
                placeholder="Cari kode / nama kriteria..."
                class="w-full px-3 py-2 rounded-lg border border-black/10 text-sm">
        </div>

        {{-- SORT BY --}}
        <div>
            <label class="text-sm text-textmuted mb-1 block">Urutkan Berdasarkan</label>
            <select name="sort_by"
                class="w-full px-3 py-2 rounded-lg border border-black/10 text-sm">
                <option value="kode" {{ request('sort_by')=='kode'?'selected':'' }}>Kode</option>
                <option value="nama" {{ request('sort_by')=='nama'?'selected':'' }}>Nama Kriteria</option>
                <option value="atribut" {{ request('sort_by')=='atribut'?'selected':'' }}>Atribut</option>
                <option value="bobot" {{ request('sort_by')=='bobot'?'selected':'' }}>Bobot</option>
                <option value="normalisasi" {{ request('sort_by')=='normalisasi'?'selected':'' }}>Normalisasi</option>
            </select>
        </div>

        {{-- SORT DIR --}}
        <div>
            <label class="text-sm text-textmuted mb-1 block">Arah Urutan</label>
            <select name="sort_dir"
                class="w-full px-3 py-2 rounded-lg border border-black/10 text-sm">
                <option value="asc" {{ request('sort_dir')=='asc'?'selected':'' }}>
                    Kecil → Besar / A → Z
                </option>
                <option value="desc" {{ request('sort_dir','desc')=='desc'?'selected':'' }}>
                    Besar → Kecil / Z → A
                </option>
            </select>
        </div>

        {{-- ACTION --}}
        <div class="sm:col-span-4 flex gap-2">
            <button type="submit"
                class="px-4 py-2 rounded-lg bg-accent text-white text-sm">
                Terapkan
            </button>

            <a href="{{ route('kriteria.index') }}"
                class="px-4 py-2 rounded-lg bg-gray-100 text-sm">
                Reset
            </a>
        </div>
    </form>

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-base text-textmuted">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Atribut</th>
                    <th class="px-4 py-3">Bobot</th>
                    <th class="px-4 py-3">Normalisasi</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kriterias as $i => $k)
                    <tr class="border-t border-black/5 hover:bg-base/60">
                        <td class="px-4 py-3">
                            {{ $kriterias->firstItem() + $i }}
                        </td>
                        <td class="px-4 py-3 font-medium">
                            {{ $k->kodeKriteria->kode_kriteria ?? '-' }}
                        </td>
                        <td class="px-4 py-3">{{ $k->nama_kriteria }}</td>
                        <td class="px-4 py-3 capitalize">{{ $k->atribut }}</td>
                        <td class="px-4 py-3">{{ $k->bobot }}</td>
                        <td class="px-4 py-3">
                            {{ number_format($k->normalisasi, 3) }}
                        </td>
                        <td class="px-4 py-3 space-x-2">
                            <a href="{{ route('kriteria.edit',$k->id) }}"
                                class="text-accent text-sm hover:underline">Edit</a>
                            <form action="{{ route('kriteria.destroy',$k->id) }}"
                                method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Hapus data?')"
                                    class="text-red-600 text-sm hover:underline">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-textmuted">
                            Data tidak ditemukan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $kriterias->links() }}
    </div>

</section>
@endsection
