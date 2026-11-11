@extends('layouts.app')

@section('content')
<section class="bg-surface rounded-2xl p-6 shadow-sm border border-black/5 space-y-6">

    {{-- ======================
         HEADER
    ======================= --}}
    <div>
        <h2 class="text-xl font-semibold">Dashboard SMART</h2>
        <p class="text-sm text-textmuted mt-1">
            Ringkasan data sistem metode SMART
        </p>
    </div>

    {{-- ======================
         STAT CARD
    ======================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

        <div class="bg-base rounded-xl p-4 border border-black/5">
            <p class="text-sm text-textmuted">Total Alternatif</p>
            <p class="text-2xl font-semibold mt-1">{{ $totalAlternatif }}</p>
        </div>

        <div class="bg-base rounded-xl p-4 border border-black/5">
            <p class="text-sm text-textmuted">Total Kriteria</p>
            <p class="text-2xl font-semibold mt-1">{{ $totalKriteria }}</p>
        </div>

        <div class="bg-base rounded-xl p-4 border border-black/5">
            <p class="text-sm text-textmuted">Total Nilai</p>
            <p class="text-2xl font-semibold mt-1">{{ $totalNilai }}</p>
        </div>

        <div class="bg-base rounded-xl p-4 border border-black/5">
            <p class="text-sm text-textmuted">Total Angkatan</p>
            <p class="text-2xl font-semibold mt-1">{{ $totalAngkatan }}</p>
        </div>

        <div class="bg-base rounded-xl p-4 border border-black/5">
            <p class="text-sm text-textmuted">Session Perhitungan</p>
            <p class="text-2xl font-semibold mt-1">{{ $totalPerhitunganSession }}</p>
        </div>

    </div>

    {{-- ======================
         DATA TERBARU
    ======================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Alternatif Terbaru --}}
        <div class="bg-base rounded-xl border border-black/5 overflow-hidden">
            <div class="px-4 py-3 border-b border-black/5 font-medium">
                Alternatif Terbaru
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-base text-textmuted">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium">Nama</th>
                            <th class="px-4 py-3 text-left font-medium">NIM</th>
                            <th class="px-4 py-3 text-left font-medium">Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestAlternatif as $item)
                            <tr class="border-t border-black/5 hover:bg-base/60 transition">
                                <td class="px-4 py-3 font-medium">
                                    {{ $item->nama_alternatif }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $item->nim ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $item->created_at->format('d M Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-textmuted">
                                    Belum ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Session Terbaru --}}
        <div class="bg-base rounded-xl border border-black/5 overflow-hidden">
            <div class="px-4 py-3 border-b border-black/5 font-medium">
                Riwayat Perhitungan Terbaru
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-base text-textmuted">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium">Nama</th>
                            <th class="px-4 py-3 text-left font-medium">Angkatan</th>
                            <th class="px-4 py-3 text-left font-medium">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestSessions as $item)
                            <tr class="border-t border-black/5 hover:bg-base/60 transition">
                                <td class="px-4 py-3 font-medium">
                                    {{ $item->nama_perhitungan }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $item->angkatan->nama_angkatan ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ \Carbon\Carbon::parse($item->tanggal_hitung)->format('d M Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-textmuted">
                                    Belum ada Riwayat
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</section>
@endsection