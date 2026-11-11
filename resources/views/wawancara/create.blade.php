<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>HIMTIF — Form Interview Seleksi</title>

    @include('wawancara.styles')
</head>

<body>
    <div class="wrap">
        <div class="card">

            <form method="POST" action="{{ route('wawancara.store') }}">
                @csrf

                {{-- ================= HEADER ================= --}}
                <header>
                    @include('wawancara.title')

                    <div class="row">
                        <a class="btn slim ghost" href="{{ route('wawancara.index') }}">Kembali ke Panel</a>
                        <button type="button" class="btn slim ghost" id="btnNew">Entri Baru</button>
                        <button type="button" class="btn slim ghost" onclick="printPreview()">🖨️ Cetak</button>
                        <button type="submit" class="btn slim primary">💾 Simpan</button>
                    </div>
                </header>

                <div class="grid g2" style="padding:16px">

                    {{-- =====================================================
   LEFT — FORM
===================================================== --}}
                    <div class="form-area">

                        {{-- IDENTITAS --}}
                        <div class="box grid g2">

                            <div>
                                <label>Nama Calon</label>
                                <input id="nama" name="nama_alternatif" required>
                            </div>

                            <div>
                                <label>NIM</label>
                                <input id="nim" name="nim" required>
                            </div>

                            <div>
                                <label>Tanggal Interview</label>
                                <input id="tanggal" type="date" name="tanggal_wawancara"
                                    value="{{ now()->format('Y-m-d') }}">
                            </div>

                            <div>
                                <label>Angkatan</label>
                                <select name="angkatan_id" id="angkatan" required>
                                    <option value="">-- pilih angkatan --</option>
                                    @foreach ($angkatans as $angkatan)
                                        <option value="{{ $angkatan->id }}">
                                            {{ $angkatan->nama_angkatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div style="grid-column: span 2;">
                                <label>Pewawancara</label>
                                <input value="{{ auth()->user()->name }}" readonly>
                            </div>

                        </div>

                        {{-- ================= SKOR ================= --}}
                        <div class="box">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                <div class="section-title" style="margin: 0;">Skor Aspek (1–5)</div>
                                <button type="button" class="btn slim ghost" id="btnPanduan" onclick="document.getElementById('modalPanduan').showModal()" style="font-size: 11px; padding: 6px 10px; cursor: pointer;">
                                    💬 Panduan (?)
                                </button>
                            </div>

                            <div class="grid g3">
                                @forelse($kriterias as $kriteria)
                                    <div>
                                        <label>
                                            {{ $kriteria->kodeKriteria->kode ?? 'K' }}
                                            — {{ $kriteria->nama_kriteria }}
                                        </label>

                                        <select name="nilai[{{ $kriteria->id }}]" class="score-input">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <option value="{{ $i }}" {{ $i == 3 ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                @empty
                                    <div class="muted">Data kriteria belum ada</div>
                                @endforelse
                            </div>
                        </div>

                        {{-- ================= CATATAN ================= --}}
                        <div class="box">
                            <div class="section-title">Catatan Pewawancara</div>
                            <textarea id="catatan" name="catatan_pewawancara"></textarea>
                        </div>

                        {{-- ================= KPI ================= --}}
                        <div class="box">
                            <div class="section-title">Rekap & Klasifikasi</div>

                            <div class="kpi">
                                <span class="badge success" id="avgBadge">Rerata: 0.00</span>
                                <span class="badge success" id="classBadge">Klasifikasi: -</span>
                            </div>
                        </div>

                    </div>

                    {{-- =====================================================
   RIGHT — PREVIEW + LIST
===================================================== --}}
                    <div>

                        {{-- ================= PREVIEW ================= --}}
                        <div class="box">
                            <div class="section-title">Pratinjau Dokumen (Hasil)</div>

                            <div id="printArea">
                                <table class="table" style="width:100%">
                                    <tr>
                                        <th>Nama</th>
                                        <td id="p_nama">-</td>
                                        <th>NIM</th>
                                        <td id="p_nim">-</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal</th>
                                        <td id="p_tanggal">-</td>
                                        <th>Pewawancara</th>
                                        <td id="p_pewawancara">{{ auth()->user()->name }}</td>
                                    </tr>
                                </table>

                                <hr>

                                <div style="font-weight:700">
                                    Klasifikasi: <span id="p_klasifikasi">-</span>
                                </div>

                                <hr>

                                <div style="margin-top:10px">
                                    <b>Detail Skor Aspek</b>

                                    <table class="table" style="width:100%;margin-top:6px;font-size:12px">
                                        <thead>
                                            <tr>
                                                <th style="width:40%">Aspek</th>

                                                <th style="width:20%">Skor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($kriterias as $kriteria)
                                                <tr>
                                                    <td>{{ $kriteria->nama_kriteria }}</td>
                                                    <td>
                                                        <span class="p_skor" data-kid="{{ $kriteria->id }}">3</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="muted" style="font-size:12px;margin-top:4px">
                                    Rerata skor: <span id="p_avg">0.00</span>
                                </div>

                                <div style="margin-top:10px">
                                    <b>Catatan Pewawancara</b>
                                    <div id="p_catatan"></div>
                                </div>
                            </div>
                        </div>


                        {{-- ================= LISTING ================= --}}
                        <div class="box">
                            <div class="section-title">Data Wawancara Sebelumnya</div>

                            {{-- 🔍 FILTER REALTIME --}}
                            <div class="grid g2" style="margin-bottom:8px">

                                <select id="filterAngkatan">
                                    <option value="">Semua Angkatan</option>

                                    @foreach ($angkatans as $angkatan)
                                        <option value="{{ $angkatan->id }}">
                                            {{ $angkatan->nama_angkatan }}
                                        </option>
                                    @endforeach
                                </select>

                                <input id="search" type="text" placeholder="Cari nama/NIM...">

                            </div>

                            {{-- 📊 INFO PAGINATION --}}
                            <div class="muted" style="font-size:12px;margin-bottom:6px">
                                Showing {{ $alternatifTerbaru->firstItem() ?? 0 }}
                                to {{ $alternatifTerbaru->lastItem() ?? 0 }}
                                of {{ $alternatifTerbaru->total() }} results
                            </div>

                            {{-- 📋 TABLE --}}
                            <div style="overflow:auto">
                                <table class="table" id="tblData">
                                    <tbody>
                                        @forelse ($alternatifTerbaru as $row)
                                            @php
                                                $avg = $row->nilaiAlternatif->avg('nilai') ?? 0;
                                                if ($avg >= 4) {
                                                    $cls = 'LAYAK';
                                                } elseif ($avg >= 3) {
                                                    $cls = 'PERLU PEMBINAAN';
                                                } else {
                                                    $cls = 'TIDAK LAYAK';
                                                }
                                            @endphp

                                            <tr class="row-data" data-nama="{{ strtolower($row->nama_alternatif) }}"
                                                data-nim="{{ strtolower($row->nim) }}"
                                                data-angkatan="{{ $row->angkatan_id }}"
                                                data-nilai='@json($row->nilaiAlternatif->pluck('nilai', 'kriteria_id'))'
                                                data-catatan="{{ e($row->catatan_pewawancara) }}"
                                                data-tanggal="{{ now()->format('Y-m-d') }}"
                                                data-avg="{{ number_format($avg, 2, '.', '') }}"
                                                data-klas="{{ $cls }}">

                                                <td>
                                                    <a href="#" class="load-data">
                                                        {{ $row->nama_alternatif }}
                                                    </a>
                                                    <div class="muted" style="font-size:11px">
                                                        avg: {{ number_format($avg, 2) }} • {{ $cls }}
                                                    </div>
                                                </td>

                                                <td>{{ $row->nim }}</td>
                                                <td>{{ $row->angkatan->nama_angkatan ?? '-' }}</td>
                                            </tr>

                                        @empty
                                            <tr>
                                                <td colspan="3" class="muted" style="text-align:center">
                                                    Belum ada data wawancara
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- 🔢 PAGINATION LINKS --}}
                            <div class="pagination-wrap">
                                {{ $alternatifTerbaru->onEachSide(1)->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ================= JS FINAL ================= --}}
    <script>
        const nama = document.getElementById('nama');
        const nim = document.getElementById('nim');
        const tanggal = document.getElementById('tanggal');
        const angkatan = document.getElementById('angkatan');
        const catatan = document.getElementById('catatan');

        function hitungRata() {
            let total = 0;
            let n = 0;

            document.querySelectorAll('.score-input').forEach(el => {
                const val = parseFloat(el.value);
                if (!isNaN(val)) {
                    total += val;
                    n++;
                }

                // 🔥 sync ke preview tabel
                const match = el.name.match(/\[(\d+)\]/);
                if (match) {
                    const kid = match[1];
                    const span = document.querySelector('.p_skor[data-kid="' + kid + '"]');
                    if (span) span.innerText = el.value;
                }
            });

            const avg = n ? total / n : 0;

            let klas = 'TIDAK LAYAK';
            if (avg >= 4) klas = 'LAYAK';
            else if (avg >= 3) klas = 'PERLU PEMBINAAN';

            document.getElementById('avgBadge').innerText = 'Rerata: ' + avg.toFixed(2);
            document.getElementById('classBadge').innerText = 'Klasifikasi: ' + klas;
            document.getElementById('p_klasifikasi').innerText = klas;
            document.getElementById('p_avg').innerText = avg.toFixed(2);
        }

        // realtime
        document.querySelectorAll('.score-input').forEach(el => {
            el.addEventListener('change', hitungRata);
        });

        // preview text binding
        ['nama', 'nim', 'tanggal', 'catatan'].forEach(id => {
            document.getElementById(id)?.addEventListener('input', e => {
                const map = {
                    nama: 'p_nama',
                    nim: 'p_nim',
                    tanggal: 'p_tanggal',
                    catatan: 'p_catatan'
                };
                const target = document.getElementById(map[id]);
                if (target) target.innerText = e.target.value;
            });
        });

        // LOAD DATA (EDIT)
        document.querySelectorAll('.load-data').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const tr = this.closest('tr');

                nama.value = tr.dataset.nama;
                nim.value = tr.dataset.nim;
                tanggal.value = tr.dataset.tanggal;
                angkatan.value = tr.dataset.angkatan;
                catatan.value = tr.dataset.catatan || '';

                let nilai = JSON.parse(tr.dataset.nilai || '{}');

                document.querySelectorAll('.score-input').forEach(select => {
                    const match = select.name.match(/\[(\d+)\]/);
                    if (!match) return;

                    const kid = match[1];

                    if (nilai[kid] !== undefined) {
                        select.value = parseInt(nilai[kid]);
                    } else {
                        select.value = 3;
                    }
                });

                // set badge dari DB
                if (tr.dataset.avg) {
                    document.getElementById('avgBadge').innerText =
                        'Rerata: ' + parseFloat(tr.dataset.avg).toFixed(2);
                    document.getElementById('p_avg').innerText =
                        parseFloat(tr.dataset.avg).toFixed(2);
                }

                if (tr.dataset.klas) {
                    document.getElementById('classBadge').innerText =
                        'Klasifikasi: ' + tr.dataset.klas;
                    document.getElementById('p_klasifikasi').innerText =
                        tr.dataset.klas;
                }

                hitungRata();
                nama.dispatchEvent(new Event('input'));
                nim.dispatchEvent(new Event('input'));
                tanggal.dispatchEvent(new Event('input'));
                catatan.dispatchEvent(new Event('input'));
            });
        });

        // reset form
        document.getElementById('btnNew')?.addEventListener('click', () => {
            document.querySelector('form').reset();
            document.querySelectorAll('.score-input').forEach(s => s.value = 3);
            hitungRata();
        });

        hitungRata();
    </script>

    <script>
        function printPreview() {
            const printContents = document.getElementById('printArea').innerHTML;
            const originalContents = document.body.innerHTML;

            document.body.innerHTML = `
        <html>
            <head>
                <title>Cetak Hasil Wawancara</title>
                <style>
                    body { font-family: Arial, sans-serif; padding:20px; }
                    table { border-collapse: collapse; width:100%; }
                    table, th, td { border:1px solid #000; }
                    th, td { padding:6px; font-size:12px; }
                    hr { margin:10px 0; }
                </style>
            </head>
            <body>
                ${printContents}
            </body>
        </html>
    `;

            window.print();
            window.location.reload(); // kembali normal
        }
    </script>

    @if (!empty($selectedAlternatif))
        @php
            $nilai = optional($selectedAlternatif->nilaiAlternatif)->pluck('nilai', 'kriteria_id');

            $lastSaved = [
                'nama' => $selectedAlternatif->nama_alternatif,
                'nim' => $selectedAlternatif->nim,
                'angkatan' => $selectedAlternatif->angkatan_id,
                'catatan' => $selectedAlternatif->catatan_pewawancara,
                'nilai' => $nilai,
            ];
        @endphp

        <script>
            window.__LAST_SAVED__ = @json($lastSaved);
        </script>
    @endif

    <script>
document.addEventListener('DOMContentLoaded', function() {

    const searchInput = document.getElementById('search');
    const filterAngkatan = document.getElementById('filterAngkatan');

    if (!searchInput || !filterAngkatan) return;

    function applyFilter() {

        const keyword = searchInput.value.toLowerCase().trim();
        const angkatan = filterAngkatan.value;

        document.querySelectorAll('#tblData tbody tr.row-data')
            .forEach(row => {

                const nama =
                    (row.dataset.nama || '').toLowerCase();

                const nim =
                    (row.dataset.nim || '').toLowerCase();

                const rowAngkatan =
                    row.dataset.angkatan || '';

                const matchSearch =
                    keyword === '' ||
                    nama.includes(keyword) ||
                    nim.includes(keyword);

                const matchAngkatan =
                    angkatan === '' ||
                    rowAngkatan === angkatan;

                row.style.display =
                    (matchSearch && matchAngkatan)
                    ? ''
                    : 'none';
            });
    }

    searchInput.addEventListener('keyup', applyFilter);
    searchInput.addEventListener('input', applyFilter);
    filterAngkatan.addEventListener('change', applyFilter);

});
</script>
    @include('wawancara.footer')
    @include('wawancara.script')

</body>

</html>
