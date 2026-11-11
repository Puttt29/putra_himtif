@extends('layouts.app')

@section('content')
<section class="bg-surface rounded-2xl p-6 shadow-sm border border-black/5 space-y-6">

    {{-- ========================= --}}
    {{-- HEADER --}}
    {{-- ========================= --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold">
                Perhitungan Nilai Akhir Alternatif (SMART)
            </h2>
            <p class="text-sm text-textmuted mt-1">
                Perhitungan utilitas, nilai akhir, dan ranking alternatif
            </p>
        </div>

        {{-- FORM HITUNG --}}
        <form method="GET" class="flex gap-2 items-center">
            <select name="angkatan_id"
                class="px-3 py-2 rounded-lg border border-black/10 text-sm"
                required>
                <option value="">-- Pilih Angkatan --</option>
                @foreach ($angkatanList as $a)
                    <option value="{{ $a->id }}" {{ $angkatanId == $a->id ? 'selected' : '' }}>
                        {{ $a->nama_angkatan }}
                    </option>
                @endforeach
            </select>

            <button type="submit" name="hitung" value="1"
                class="px-4 py-2 rounded-lg bg-accent text-white hover:bg-accentHover transition text-sm font-medium">
                Hitung
            </button>
        </form>
    </div>

    {{-- INFO --}}
    @if (!$angkatanId)
        <div class="px-4 py-3 rounded-lg bg-blue-50 text-blue-700 text-sm">
            Silakan pilih <strong>angkatan</strong> terlebih dahulu.
        </div>
    @endif

    {{-- ========================= --}}
    {{-- TABEL UTILITAS --}}
    {{-- ========================= --}}
    @if ($angkatanId && $alternatifs->count() && $session)
        <div class="space-y-3">
            <h3 class="text-lg font-semibold">Tabel Utilitas & Nilai Akhir</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-base text-textmuted">
                        <tr>
                            <th rowspan="2" class="px-4 py-3 text-left">#</th>
                            <th rowspan="2" class="px-4 py-3 text-left">Nama Alternatif</th>
                            @foreach ($kriterias as $kriteria)
                                <th colspan="2" class="px-4 py-3 text-center">
                                    {{ $kriteria->nama_kriteria }}
                                </th>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach ($kriterias as $kriteria)
                                <th class="px-4 py-2 text-center">Utilitas</th>
                                <th class="px-4 py-2 text-center">Nilai Akhir</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($alternatifs as $i => $alt)
                            <tr class="border-t border-black/5 hover:bg-base/60 transition">
                                <td class="px-4 py-3">{{ $i + 1 }}</td>
                                <td class="px-4 py-3 font-medium">
                                    {{ $alt->nama_alternatif }}
                                </td>

                                @foreach ($kriterias as $kriteria)
                                    @php
                                        $p = $alt->perhitungan->firstWhere('kriteria_id', $kriteria->id);
                                    @endphp
                                    <td class="px-4 py-3 text-center">
                                        {{ number_format($p?->nilai_utilitas ?? 0, 3) }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        {{ number_format($p?->nilai_akhir ?? 0, 3) }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- ========================= --}}
    {{-- HASIL RANKING --}}
    {{-- ========================= --}}
    @if ($ranking->count())
        <div class="space-y-4">
            <h3 class="text-lg font-semibold">Hasil Ranking</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-base text-textmuted">
                        <tr>
                            <th class="px-4 py-3 text-center w-32">Peringkat</th>
                            <th class="px-4 py-3 text-left">Nama Alternatif</th>
                            <th class="px-4 py-3 text-right w-40">Total Akhir</th>
                            <th class="px-4 py-3 text-center w-48">Klasifikasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ranking as $row)
                            <tr class="border-t border-black/5 hover:bg-base/60 transition">
                                <td class="px-4 py-3 text-center font-semibold">
                                    {{ $row['ranking'] }}
                                </td>

                                <td class="px-4 py-3 font-medium">
                                    {{ $row['nama_alternatif'] }}
                                </td>

                                <td class="px-4 py-3 text-right font-semibold">
                                    {{ number_format($row['total_nilai'], 3) }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    @if ($row['klasifikasi'] === 'Layak')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                            Layak
                                        </span>
                                    @elseif ($row['klasifikasi'] === 'Perlu Pembinaan')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                            Perlu Pembinaan
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                            Tidak Layak
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</section>
@endsection
