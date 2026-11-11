<?php

namespace App\Http\Controllers;

use App\Models\PerhitunganSession;
use App\Models\Kriteria;
use App\Models\Angkatan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PerhitunganSessionController extends Controller
{
    public function index(Request $request)
    {
        $query = PerhitunganSession::with('angkatan');

        // SEARCH
        if ($request->filled('q')) {
            $query->where('nama_perhitungan', 'like', '%' . $request->q . '%')
                ->orWhereHas(
                    'angkatan',
                    fn($q) =>
                    $q->where('nama_angkatan', 'like', '%' . $request->q . '%')
                );
        }

        // SORTING TERBARU / TERLAMA
        if ($request->sort === 'oldest') {
            $query->orderBy('tanggal_hitung', 'asc');
        } else {
            $query->orderByDesc('tanggal_hitung');
        }

        $sessions = $query->paginate(10)->withQueryString();

        return view('perhitungan_sessions.index', compact('sessions'));
    }

    public function show($id)
    {
        $session = PerhitunganSession::with([
            'angkatan',
            'perhitungan.alternatif.pewawancaras',
            'perhitungan.kriteria'
        ])->findOrFail($id);

        $ranking = $this->buildRanking($session);

        return view('perhitungan_sessions.show', compact('session', 'ranking'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_perhitungan' => 'required|string|max:255',
        ]);

        PerhitunganSession::findOrFail($id)->update([
            'nama_perhitungan' => $request->nama_perhitungan,
        ]);

        return back()->with('success', 'Nama perhitungan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        PerhitunganSession::findOrFail($id)->delete();

        return redirect()
            ->route('perhitungan-sessions.index')
            ->with('success', 'Riwayat perhitungan berhasil dihapus.');
    }

    public function downloadPdf($id)
    {
        $session = PerhitunganSession::with([
            'angkatan',
            'perhitungan.alternatif.pewawancaras',
            'perhitungan.kriteria'
        ])->findOrFail($id);

        $ranking = $this->buildRanking($session);

        $pdf = Pdf::loadView(
            'perhitungan_sessions.pdf',
            compact('session', 'ranking')
        )->setPaper('A4', 'landscape');

        return $pdf->download(
            'Hasil_Perhitungan_' . str_replace(' ', '_', $session->nama_perhitungan) . '.pdf'
        );
    }

    private function buildRanking(PerhitunganSession $session)
    {
        return $session->perhitungan
            ->groupBy('alternatif_id')
            ->map(function ($rows) {

                $total = round($rows->sum('nilai_akhir'), 4);

                if ($total >= 0.75) {
                    $klasifikasi = 'Layak';
                } elseif ($total >= 0.50) {
                    $klasifikasi = 'Perlu Pembinaan';
                } else {
                    $klasifikasi = 'Tidak Layak';
                }

                // Detail nilai per kriteria
                $nilaiPerKriteria = $rows->mapWithKeys(fn($r) => [
                    $r->kriteria_id => [
                        'nilai_utilitas' => $r->nilai_utilitas,
                        'nilai_akhir'    => $r->nilai_akhir,
                    ]
                ]);

                $alternatif = $rows->first()->alternatif;
                $pewawancaras = $alternatif->pewawancaras->pluck('name')->implode(', ');

                return [
                    'alternatif'        => $alternatif->nama_alternatif,
                    'pewawancara'       => $pewawancaras ?: '-',
                    'total_nilai'       => $total,
                    'klasifikasi'       => $klasifikasi,
                    'nilai_per_kriteria' => $nilaiPerKriteria,
                ];
            })
            ->sortByDesc('total_nilai')
            ->values()
            ->map(fn($row, $i) => $row + ['ranking' => $i + 1]);
    }

    /* ==========================
     * DOWNLOAD EXCEL LENGKAP
     * ========================== */
    public function downloadExcel($id)
    {
        $session = PerhitunganSession::with([
            'angkatan',
            'perhitungan.alternatif.pewawancaras',
            'perhitungan.kriteria'
        ])->findOrFail($id);

        $ranking   = $this->buildRanking($session);
        $kriterias = Kriteria::orderBy('id')->get();

        // =====================
        // BUAT SPREADSHEET
        // =====================
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Hasil Nilai Akhir');

        // -- HEADER INFO --
        $sheet->setCellValue('A1', 'HASIL PERHITUNGAN METODE SMART');
        $sheet->mergeCells('A1:' . $this->colLetter(4 + $kriterias->count()) . '1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', 'Nama Perhitungan');
        $sheet->setCellValue('B3', $session->nama_perhitungan);
        $sheet->setCellValue('A4', 'Angkatan');
        $sheet->setCellValue('B4', $session->angkatan->nama_angkatan ?? '-');
        $sheet->setCellValue('A5', 'Tanggal Hitung');
        $sheet->setCellValue('B5', $session->tanggal_hitung->format('d-m-Y H:i'));
        $sheet->getStyle('A3:A5')->getFont()->setBold(true);

        // -- HEADER TABEL --
        $headerRow = 7;
        $col = 0;
        $headers = ['No', 'Nama Calon', 'Pewawancara'];

        foreach ($kriterias as $kriteria) {
            $headers[] = $kriteria->nama_kriteria;
        }
        $headers[] = 'Total Nilai Akhir';
        $headers[] = 'Klasifikasi';

        foreach ($headers as $i => $header) {
            $colLetter = $this->colLetter($i);
            $sheet->setCellValue($colLetter . $headerRow, $header);
        }

        $lastCol = $this->colLetter(count($headers) - 1);
        $headerRange = 'A' . $headerRow . ':' . $lastCol . $headerRow;

        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // -- DATA ROWS --
        $dataRow = $headerRow + 1;
        foreach ($ranking as $row) {
            $col = 0;
            $sheet->setCellValue($this->colLetter($col++) . $dataRow, $row['ranking']);
            $sheet->setCellValue($this->colLetter($col++) . $dataRow, $row['alternatif']);
            $sheet->setCellValue($this->colLetter($col++) . $dataRow, $row['pewawancara']);

            foreach ($kriterias as $kriteria) {
                $nilaiAkhir = $row['nilai_per_kriteria'][$kriteria->id]['nilai_akhir'] ?? 0;
                $sheet->setCellValue($this->colLetter($col++) . $dataRow, round($nilaiAkhir, 4));
            }

            $sheet->setCellValue($this->colLetter($col++) . $dataRow, round($row['total_nilai'], 4));
            $sheet->setCellValue($this->colLetter($col++) . $dataRow, $row['klasifikasi']);

            $dataRow++;
        }

        // -- BORDER TABEL --
        $lastDataRow = $dataRow - 1;
        $tableRange = 'A' . $headerRow . ':' . $lastCol . $lastDataRow;
        $sheet->getStyle($tableRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Center kolom No, nilai, dan klasifikasi
        $sheet->getStyle('A' . ($headerRow + 1) . ':A' . $lastDataRow)
              ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Auto-width kolom
        foreach (range(0, count($headers) - 1) as $colIdx) {
            $sheet->getColumnDimension($this->colLetter($colIdx))
                  ->setAutoSize(true);
        }

        // -- WARNA KLASIFIKASI --
        $klasifikasiCol = $this->colLetter(count($headers) - 1);
        for ($r = $headerRow + 1; $r <= $lastDataRow; $r++) {
            $val = $sheet->getCell($klasifikasiCol . $r)->getValue();
            $color = match ($val) {
                'Layak'            => 'C6EFCE',
                'Perlu Pembinaan'  => 'FFEB9C',
                'Tidak Layak'     => 'FFC7CE',
                default            => 'FFFFFF',
            };
            $sheet->getStyle($klasifikasiCol . $r)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $color],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
        }

        // =====================
        // DOWNLOAD
        // =====================
        $filename = 'Hasil_Nilai_Akhir_' . str_replace(' ', '_', $session->nama_perhitungan) . '.xlsx';

        $writer = new Xlsx($spreadsheet);

        $temp = tempnam(sys_get_temp_dir(), 'excel');
        $writer->save($temp);

        return response()->download($temp, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Helper: index angka → huruf kolom Excel (0=A, 1=B, ..., 26=AA, ...)
     */
    private function colLetter(int $index): string
    {
        $letter = '';
        while ($index >= 0) {
            $letter = chr(65 + ($index % 26)) . $letter;
            $index  = intdiv($index, 26) - 1;
        }
        return $letter;
    }
}
