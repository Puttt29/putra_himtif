@extends('layouts.app')

@section('content')
<section class="bg-surface rounded-2xl p-6 shadow-sm border border-black/5 space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold">Data Wawancara</h2>
            <p class="text-sm text-textmuted mt-1">
                Kelola proses wawancara calon anggota
            </p>
        </div>
    </div>

    {{-- FILTER ANGKATAN --}}
    <form method="GET" action="{{ route('wawancara.index') }}">
        <div class="max-w-sm">
            <label class="block text-sm font-medium mb-1">
                Pilih Angkatan
            </label>

            <select
                name="angkatan_id"
                onchange="this.form.submit()"
                class="w-full rounded-lg border border-black/10 px-3 py-2 text-sm
                       focus:outline-none focus:ring-2 focus:ring-accent">

                <option value="">-- Pilih Angkatan --</option>

                @foreach ($angkatans as $angkatan)
                    <option
                        value="{{ $angkatan->id }}"
                        {{ $angkatanId == $angkatan->id ? 'selected' : '' }}>
                        {{ $angkatan->nama_angkatan }}
                    </option>
                @endforeach

            </select>
        </div>
    </form>

    {{-- FLASH MESSAGE --}}
    @if (session('success'))
        <div class="px-4 py-3 rounded-lg bg-green-50 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-base text-textmuted">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">#</th>
                    <th class="px-4 py-3 text-left font-medium">Nama</th>
                    <th class="px-4 py-3 text-left font-medium">NIM</th>
                    <th class="px-4 py-3 text-left font-medium">Status</th>
                    <th class="px-4 py-3 text-left font-medium">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($alternatifs as $index => $alt)

                    @php
                        $sudahDinilai = $alt->nilaiAlternatif->count() > 0;
                    @endphp

                    <tr class="border-t border-black/5 hover:bg-base/60 transition">

                        <td class="px-4 py-3">
                            {{ $alternatifs->firstItem() + $index }}
                        </td>

                        <td class="px-4 py-3 font-medium">
                            {{ $alt->nama_alternatif }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $alt->nim ?? '-' }}
                        </td>

                        {{-- STATUS --}}
                        <td class="px-4 py-3">
                            <span
                                class="px-2 py-1 rounded-full text-xs font-medium
                                {{ $sudahDinilai
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $sudahDinilai ? 'Sudah Dinilai' : 'Belum Dinilai' }}
                            </span>
                        </td>

                        {{-- AKSI --}}
                        <td class="px-4 py-3">
                            <a
                                href="{{ route('wawancara.create', $alt->id) }}"
                                class="text-sm text-accent hover:underline">

                                {{ $sudahDinilai ? 'Edit' : 'Interview' }}

                            </a>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-textmuted">

                            @if (!$angkatanId)
                                Silakan pilih angkatan terlebih dahulu.
                            @else
                                Tidak ada data alternatif.
                            @endif

                        </td>
                    </tr>

                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    @if ($alternatifs->total() > 0)
        <div class="mt-4">
            {{ $alternatifs->links() }}
        </div>
    @endif

</section>
@endsection