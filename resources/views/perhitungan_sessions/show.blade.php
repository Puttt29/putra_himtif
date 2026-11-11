@extends('layouts.app')

@section('content')
<section class="bg-surface rounded-2xl p-6 shadow-sm border border-black/5 space-y-6">

    {{-- ========================= --}}
    {{-- HEADER --}}
    {{-- ========================= --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold">Detail Riwayat Perhitungan</h2>
            <p class="text-sm text-textmuted mt-1">
                Informasi perhitungan dan hasil ranking
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('perhitungan-sessions.index') }}"
                class="px-4 py-2 rounded-lg border border-black/10 text-sm hover:bg-base transition">
                Kembali
            </a>

            @if (auth()->check() && auth()->user()->role === 'admin')
                <a href="{{ route('perhitungan-sessions.excel', $session->id) }}"
                    class="px-4 py-2 rounded-lg bg-green-600 text-white text-sm hover:bg-green-700 transition">
                    Download
                </a>
            @endif
        </div>
    </div>

    {{-- ========================= --}}
    {{-- INFO PERHITUNGAN --}}
    {{-- ========================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1">Nama Perhitungan</label>
            <input type="text"
                class="w-full px-3 py-2 rounded-lg border border-black/10 bg-base"
                value="{{ $session->nama_perhitungan }}"
                readonly>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Angkatan</label>
            <input type="text"
                class="w-full px-3 py-2 rounded-lg border border-black/10 bg-base"
                value="{{ $session->angkatan->nama_angkatan ?? '-' }}"
                readonly>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Tanggal Hitung</label>
            <input type="text"
                class="w-full px-3 py-2 rounded-lg border border-black/10 bg-base"
                value="{{ $session->tanggal_hitung->format('d-m-Y H:i') }}"
                readonly>
        </div>
    </div>

    {{-- ========================= --}}
    {{-- FILTER RANKING --}}
    {{-- ========================= --}}
    <div class="flex flex-col sm:flex-row gap-3">
        <input type="text" id="searchRanking"
            placeholder="Cari nama alternatif"
            class="px-3 py-2 rounded-lg border border-black/10 text-sm w-full sm:w-64">

        <select id="sortRanking"
            class="px-3 py-2 rounded-lg border border-black/10 text-sm">
            <option value="rank">Urutkan: Ranking</option>
            <option value="name">Urutkan: Nama</option>
            <option value="nilai">Urutkan: Total Akhir</option>
        </select>
    </div>

    {{-- ========================= --}}
    {{-- TABEL HASIL RANKING --}}
    {{-- ========================= --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="rankingTable">
            <thead class="bg-base text-textmuted">
                <tr>
                    <th class="px-4 py-3 text-center w-28">Peringkat</th>
                    <th class="px-4 py-3 text-left">Nama Alternatif</th>
                    <th class="px-4 py-3 text-right w-40">Total Akhir</th>
                    <th class="px-4 py-3 text-center w-48">Klasifikasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ranking as $row)
                    <tr
                        class="border-t border-black/5 hover:bg-base/60 transition"
                        data-rank="{{ $row['ranking'] }}"
                        data-name="{{ strtolower($row['alternatif']) }}"
                        data-nilai="{{ $row['total_nilai'] }}"
                    >
                        <td class="px-4 py-3 text-center font-semibold">
                            {{ $row['ranking'] }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $row['alternatif'] }}
                        </td>

                        <td class="px-4 py-3 text-right font-medium">
                            {{ number_format($row['total_nilai'], 3) }}
                        </td>

                        <td class="px-4 py-3 text-center">
                            @if ($row['klasifikasi'] === 'Layak')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    bg-green-100 text-green-700">
                                    Layak
                                </span>
                            @elseif ($row['klasifikasi'] === 'Perlu Pembinaan')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    bg-yellow-100 text-yellow-700">
                                    Perlu Pembinaan
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    bg-red-100 text-red-700">
                                    Tidak Layak
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</section>

{{-- ========================= --}}
{{-- SCRIPT SEARCH & SORT --}}
{{-- ========================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('searchRanking');
    const sortSelect  = document.getElementById('sortRanking');
    const tbody       = document.querySelector('#rankingTable tbody');

    function filterRows() {
        const keyword = searchInput.value.toLowerCase();
        tbody.querySelectorAll('tr').forEach(row => {
            row.style.display = row.dataset.name.includes(keyword) ? '' : 'none';
        });
    }

    function sortRows() {
        const rows = Array.from(tbody.querySelectorAll('tr'));

        rows.sort((a, b) => {
            if (sortSelect.value === 'name') {
                return a.dataset.name.localeCompare(b.dataset.name);
            }

            if (sortSelect.value === 'nilai') {
                return parseFloat(b.dataset.nilai) - parseFloat(a.dataset.nilai);
            }

            return parseInt(a.dataset.rank) - parseInt(b.dataset.rank);
        });

        rows.forEach(row => tbody.appendChild(row));
    }

    searchInput.addEventListener('keyup', filterRows);
    sortSelect.addEventListener('change', sortRows);
});
</script>
@endsection
