<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithCustomStartCell,
    WithStyles,
    WithColumnWidths,
    WithEvents
};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;

class PemesananExport implements
    FromCollection,
    WithHeadings,
    WithCustomStartCell,
    WithStyles,
    WithColumnWidths,
    WithEvents
{
    protected $pemesanan;

    public function __construct($pemesanan)
    {
        $this->pemesanan = $pemesanan;
    }

    /**
     * ⬅️ TABEL DIMULAI BARIS 4
     */
    public function startCell(): string
    {
        return 'A4';
    }

    /**
     * DATA TABEL
     */
    public function collection()
    {
        return $this->pemesanan->map(function ($p) {

            // Gabungkan jadwal jadi multi-line
            $jadwals = $p->detailJadwal->map(function ($dj) {
                return
                    Carbon::parse($dj->tanggal)->format('d/m/Y') . ' ' .
                    substr($dj->jam_mulai, 0, 5) . ' - ' .
                    substr($dj->jam_selesai, 0, 5);
            })->implode("\n");

            return [
                $p->kode_pemesanan,
                $p->pengguna->nama ?? '-',
                $p->lapangan->nama_lapangan ?? '-',
                $jadwals,
                'Rp ' . number_format($p->total_bayar ?? 0, 0, ',', '.'),
                ucfirst($p->status_pemesanan ?? '-'),
            ];
        });
    }

    /**
     * HEADER TABEL (SAMA DENGAN INDEX)
     */
    public function headings(): array
    {
        return [
            'Kode Pemesanan',
            'Pemesan',
            'Lapangan',
            'Jadwal',
            'Total Bayar',
            'Status',
        ];
    }

    /**
     * STYLE HEADER BARIS TABEL
     */
    public function styles(Worksheet $sheet)
    {
        return [
            4 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    /**
     * LEBAR KOLOM
     */
    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 25,
            'C' => 20,
            'D' => 40, // Jadwal (wrap)
            'E' => 18,
            'F' => 18,
        ];
    }

    /**
     * HEADER LAPORAN + BORDER + WRAP
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet   = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // ===== HEADER LAPORAN =====
                $sheet->mergeCells('A1:F1');
                $sheet->setCellValue('A1', 'LAPORAN PEMESANAN LAPANGAN');

                $sheet->mergeCells('A2:F2');
                $sheet->setCellValue('A2', 'FUTSAL ACR');

                $sheet->mergeCells('A3:F3');
                $sheet->setCellValue(
                    'A3',
                    'Dicetak pada: ' . Carbon::now()->format('d/m/Y H:i')
                );

                $sheet->getStyle('A1:A3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // ===== HEADER TABEL =====
                $sheet->getStyle('A4:F4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '2563EB'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // ===== BORDER TABEL =====
                $sheet->getStyle("A4:F{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // ===== WRAP JADWAL =====
                $sheet->getStyle("D5:D{$lastRow}")
                    ->getAlignment()
                    ->setWrapText(true)
                    ->setVertical(Alignment::VERTICAL_TOP);

                // ===== TOTAL RATA KANAN =====
                $sheet->getStyle("E5:E{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            }
        ];
    }
}
