<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Hasil Perhitungan SMART</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #f0f0f0;
        }

        .center {
            text-align: center;
        }
    </style>
</head>

<body>

    <h3>Hasil Perhitungan Metode SMART</h3>

    <table>
        <tr>
            <td width="30%">Nama Perhitungan</td>
            <td>{{ $session->nama_perhitungan }}</td>
        </tr>
        <tr>
            <td>Angkatan</td>
            <td>{{ $session->angkatan->nama_angkatan ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>{{ $session->tanggal_hitung->format('d-m-Y H:i') }}</td>
        </tr>
    </table>

    <h4>Hasil Ranking &amp; Nilai Per Kriteria</h4>

    @php
        $kriterias = \App\Models\Kriteria::orderBy('id')->get();
    @endphp

    <table>
        <thead>
            <tr>
                <th class="center" width="40">#</th>
                <th>Nama Calon</th>
                @foreach ($kriterias as $kriteria)
                    <th class="center">{{ $kriteria->nama_kriteria }}</th>
                @endforeach
                <th class="center" width="90">Total Akhir</th>
                <th class="center" width="100">Klasifikasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ranking as $r)
                <tr>
                    <td class="center">{{ $r['ranking'] }}</td>
                    <td>{{ $r['alternatif'] }}</td>
                    @foreach ($kriterias as $kriteria)
                        <td class="center">
                            {{ number_format($r['nilai_per_kriteria'][$kriteria->id]['nilai_akhir'] ?? 0, 4) }}
                        </td>
                    @endforeach
                    <td class="center">{{ number_format($r['total_nilai'], 3) }}</td>
                    <td class="center">{{ $r['klasifikasi'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
