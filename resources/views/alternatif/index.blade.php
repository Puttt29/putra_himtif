@extends('layouts.app')

@section('content')
    <section class="bg-surface rounded-2xl p-6 shadow-sm border border-black/5 space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold">Manajemen Alternatif</h2>
                <p class="text-sm text-textmuted mt-1">
                    Kelola data alternatif yang akan dinilai
                </p>
            </div>

            <a href="{{ route('alternatif.create') }}"
                class="px-4 py-2 rounded-lg bg-accent text-white hover:bg-accentHover transition text-sm font-medium">
                + Tambah Alternatif
            </a>
        </div>

        {{-- FLASH --}}
        @if (session('success'))
            <div class="px-4 py-3 rounded-lg bg-green-50 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET" class="grid grid-cols-1 sm:grid-cols-5 gap-3 items-end">

            {{-- SEARCH --}}
            <div class="sm:col-span-2">
                <label class="text-sm text-textmuted mb-1 block">Pencarian</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama, NIM, atau angkatan..."
                    class="w-full px-3 py-2 rounded-lg border border-black/10 text-sm">
            </div>

            {{-- FILTER ANGKATAN --}}
            <div>
                <label class="text-sm text-textmuted mb-1 block">Angkatan</label>
                <select name="angkatan_id" class="w-full px-3 py-2 rounded-lg border border-black/10 text-sm">
                    <option value="">Semua Angkatan</option>
                    @foreach ($angkatans as $angkatan)
                        <option value="{{ $angkatan->id }}" {{ request('angkatan_id') == $angkatan->id ? 'selected' : '' }}>
                            {{ $angkatan->nama_angkatan }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- SORT BY --}}
            <div>
                <label class="text-sm text-textmuted mb-1 block">Urutkan Berdasarkan</label>
                <select name="sort_by" class="w-full px-3 py-2 rounded-lg border border-black/10 text-sm">
                    <option value="nama" {{ request('sort_by') == 'nama' ? 'selected' : '' }}>
                        Nama Alternatif
                    </option>
                    <option value="nim" {{ request('sort_by') == 'nim' ? 'selected' : '' }}>
                        NIM
                    </option>
                    <option value="angkatan" {{ request('sort_by') == 'angkatan' ? 'selected' : '' }}>
                        Angkatan
                    </option>
                    <option value="created" {{ request('sort_by') == 'created' ? 'selected' : '' }}>
                        Tanggal Dibuat
                    </option>
                </select>
            </div>

            {{-- SORT DIR --}}
            <div>
                <label class="text-sm text-textmuted mb-1 block">Arah Urutan</label>
                <select name="sort_dir" class="w-full px-3 py-2 rounded-lg border border-black/10 text-sm">
                    <option value="asc" {{ request('sort_dir') == 'asc' ? 'selected' : '' }}>
                        A → Z / Kecil → Besar
                    </option>
                    <option value="desc" {{ request('sort_dir') == 'desc' ? 'selected' : '' }}>
                        Z → A / Besar → Kecil
                    </option>
                </select>
            </div>

            {{-- ACTION --}}
            <div class="sm:col-span-5 flex gap-2">
                <button type="submit" class="px-4 py-2 rounded-lg bg-accent text-white text-sm">
                    Terapkan
                </button>

                <a href="{{ route('alternatif.index') }}" class="px-4 py-2 rounded-lg bg-gray-100 text-sm">
                    Reset
                </a>
            </div>
        </form>

        {{-- TABLE --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-base text-textmuted">
                    <tr>
                        <th class="px-4 py-3 w-16">#</th>
                        <th class="px-4 py-3">Nama Alternatif</th>
                        <th class="px-4 py-3">NIM</th>
                        <th class="px-4 py-3">Angkatan</th>
                        <th class="px-4 py-3 w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($alternatifs as $i => $a)
                        <tr class="border-t border-black/5 hover:bg-base/60 transition">
                            <td class="px-4 py-3">
                                {{ $alternatifs->firstItem() + $i }}
                            </td>
                            <td class="px-4 py-3 font-medium">
                                {{ $a->nama_alternatif }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $a->nim ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $a->angkatan->nama_angkatan ?? '-' }}
                            </td>
                            <td class="px-4 py-3 space-x-3">
                                <a href="{{ route('alternatif.edit', $a->id) }}"
                                    class="text-sm text-accent hover:underline">
                                    Edit
                                </a>

                                <form action="{{ route('alternatif.destroy', $a->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin ingin menghapus alternatif ini?')"
                                        class="text-sm text-red-600 hover:underline">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-textmuted">
                                Data tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-4">
            {{ $alternatifs->links() }}
        </div>

    </section>
@endsection
