<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Interview Seleksi</title>

    <style>
        @page {
            margin: 30px 40px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header img {
            height: 65px;
            margin-bottom: 6px;
        }

        .header h2 {
            margin: 4px 0 2px 0;
            font-size: 15px;
            font-weight: bold;
        }

        .header p {
            margin: 0;
            font-size: 11px;
        }

        .badge {
            position: fixed;
            top: 30px;
            right: 40px;
            background: #0f766e;
            color: #fff;
            padding: 5px 12px;
            border-radius: 14px;
            font-size: 9px;
            font-weight: bold;
        }

        hr {
            border: none;
            border-top: 1.5px solid #ccc;
            margin: 12px 0;
        }

        .info-table {
            width: 100%;
            margin-bottom: 8px;
        }

        .info-table td {
            padding: 3px 4px;
            vertical-align: top;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        table.data th,
        table.data td {
            border: 1px solid #bbb;
            padding: 6px 5px;
            font-size: 10.5px;
        }

        table.data th {
            background: #f3f4f6;
            text-align: center;
            font-weight: bold;
        }

        .summary {
            margin-top: 12px;
            font-size: 11px;
        }

        .summary h3 {
            margin-top: 6px;
            font-size: 13px;
        }

        .footer {
            margin-top: 35px;
            font-size: 10px;
            color: #444;
        }
    </style>
</head>

<body>

    <div class="badge">CONFIDENTIAL</div>

    <div class="header">
        @if ($logoBase64)
            <img src="data:image/png;base64,{{ $logoBase64 }}" height="65">
        @endif
        <h2>HIMTIF — Laporan Interview Seleksi</h2>
        <p>Himpunan Mahasiswa Teknik Informatika</p>
    </div>

    <hr>

    <table class="info-table">
        <tr>
            <td width="90">Nama</td>
            <td width="200">: {{ $alternatif->nama_alternatif }}</td>
            <td width="80">NIM</td>
            <td>: {{ $alternatif->nim ?? '-' }}</td>
        </tr>
        <tr>
            <td>Angkatan</td>
            <td>: {{ $alternatif->angkatan->nama_angkatan ?? '-' }}</td>
            <td>Tanggal</td>
            <td>: {{ now()->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td>Pewawancara</td>
            <td colspan="3">: {{ $pewawancaraNama }}</td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th width="60">No</th>
                <th>Kriteria</th>
                <th width="80">Bobot</th>
                <th width="60">Nilai</th>
                <th width="90">Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($smart['detail'] as $index => $row)
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td>{{ $row['nama'] }}</td>
                    <td align="center">{{ number_format($row['bobot'], 2) }}</td>
                    <td align="center">{{ $row['nilai'] }}</td>
                    <td align="center">{{ number_format($row['nilai_akhir'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <p><strong>Skor SMART:</strong> {{ number_format($smart['total'], 2) }}</p>
        <p><strong>Kriteria ≥ 4:</strong> {{ $smart['count_baik'] }}</p>
        <p><strong>Kriteria &lt; 4:</strong> {{ $smart['count_buruk'] }}</p>
        <h3>Klasifikasi: {{ strtoupper($smart['klasifikasi']) }}</h3>
    </div>

    <div class="footer">
        <p>Dokumen ini dihasilkan otomatis oleh Sistem Seleksi HIMTIF.</p>
        <p>{{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

</body>

</html>
