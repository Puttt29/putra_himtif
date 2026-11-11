@extends('layouts.app')

@section('content')
<section class="bg-surface rounded-2xl p-6 shadow-sm border border-black/5 space-y-6">

    <div>
        <h2 class="text-xl font-semibold">Riwayat Perhitungan SMART</h2>
        <p class="text-sm text-textmuted mt-1">
            Daftar riwayat perhitungan yang telah dilakukan
        </p>
    </div>

    {{-- FILTER --}}
    <form method="GET" class="flex flex-col sm:flex-row gap-3">
        <input type="text" name="q" value="{{ request('q') }}"
            placeholder="Cari nama perhitungan / angkatan"
            class="px-3 py-2 rounded-lg border border-black/10 text-sm w-full sm:w-72">

        <select name="sort"
            class="px-3 py-2 rounded-lg border border-black/10 text-sm">
            <option value="newest" {{ request('sort') !== 'oldest' ? 'selected' : '' }}>
                Terbaru
            </option>
            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>
                Terlama
            </option>
        </select>

        <button class="px-4 py-2 rounded-lg bg-accent text-white text-sm">
            Terapkan
        </button>
    </form>

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-base text-textmuted">
                <tr>
                    <th class="px-4 py-3 w-16">#</th>
                    <th class="px-4 py-3">Nama Perhitungan</th>
                    <th class="px-4 py-3">Angkatan</th>
                    <th class="px-4 py-3">Tanggal Hitung</th>
                    <th class="px-4 py-3 text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sessions as $i => $s)
                    <tr class="border-t border-black/5">
                        <td class="px-4 py-3">
                            {{ $sessions->firstItem() + $i }}
                        </td>
                        <td class="px-4 py-3 font-medium">
                            {{ $s->nama_perhitungan }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $s->angkatan->nama_angkatan ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $s->tanggal_hitung->format('d-m-Y H:i') }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('perhitungan-sessions.show', $s->id) }}"
                                class="text-accent hover:underline text-sm">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-textmuted">
                            Belum ada riwayat perhitungan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $sessions->links() }}

</section>
@endsection
