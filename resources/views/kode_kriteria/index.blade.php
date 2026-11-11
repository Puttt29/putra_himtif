@extends('layouts.app')

@section('content')
<section class="bg-surface rounded-2xl p-6 shadow-sm border border-black/5 space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold">Manajemen Kode Kriteria</h2>
            <p class="text-sm text-textmuted mt-1">
                Kelola daftar kode kriteria yang digunakan dalam sistem
            </p>
        </div>

        <a href="{{ route('kode_kriteria.create') }}"
            class="px-4 py-2 rounded-lg bg-accent text-white hover:bg-accentHover transition text-sm font-medium">
            + Tambah Kode Kriteria
        </a>
    </div>

    {{-- FLASH MESSAGE --}}
    @if (session('success'))
        <div class="px-4 py-3 rounded-lg bg-green-50 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- SEARCH --}}
    <form method="GET" class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
        <div class="flex gap-2 w-full sm:w-1/2">
            <input type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari kode kriteria..."
                class="w-full px-3 py-2 rounded-lg border border-black/10 text-sm focus:ring-accent focus:border-accent">

            <button type="submit"
                class="px-4 py-2 rounded-lg bg-accent text-white text-sm hover:bg-accentHover">
                Cari
            </button>
        </div>

        {{-- Preserve Sorting --}}
        <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
        <input type="hidden" name="sort_dir" value="{{ request('sort_dir') }}">
    </form>

    {{-- SORT ICON HELPER --}}
    @php
        function sortIcon($column, $sortBy, $sortDir) {
            if ($sortBy !== $column) return '↕';
            return $sortDir === 'asc' ? '↑' : '↓';
        }
    @endphp

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-base text-textmuted">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">#</th>

                    <th class="px-4 py-3 text-left font-medium">
                        <a href="{{ request()->fullUrlWithQuery([
                            'sort_by' => 'kode_kriteria',
                            'sort_dir' => ($sortBy === 'kode_kriteria' && $sortDir === 'asc') ? 'desc' : 'asc'
                        ]) }}"
                        class="flex items-center gap-1 hover:text-accent transition">
                            Kode Kriteria
                            <span>{{ sortIcon('kode_kriteria', $sortBy, $sortDir) }}</span>
                        </a>
                    </th>

                    <th class="px-4 py-3 text-left font-medium">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($kodeKriterias as $index => $kode)
                    <tr class="border-t border-black/5 hover:bg-base/60 transition">
                        <td class="px-4 py-3">
                            {{ $kodeKriterias->firstItem() + $index }}
                        </td>

                        <td class="px-4 py-3 font-medium">
                            {{ $kode->kode_kriteria }}
                        </td>

                        <td class="px-4 py-3 space-x-3">
                            <a href="{{ route('kode_kriteria.edit', $kode->id) }}"
                                class="text-accent hover:underline text-sm">
                                Edit
                            </a>

                            <form action="{{ route('kode_kriteria.destroy', $kode->id) }}"
                                method="POST"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Yakin ingin menghapus kode kriteria ini?')"
                                    class="text-red-600 hover:underline text-sm">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-textmuted">
                            Data tidak ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $kodeKriterias->links() }}
    </div>

</section>
@endsection
