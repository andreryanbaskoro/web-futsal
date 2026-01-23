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

class JadwalExport implements
    FromCollection,
    WithHeadings,
    WithCustomStartCell,
    WithStyles,
    WithColumnWidths,
    WithEvents
{
    protected $jadwals;

    public function __construct($jadwals)
    {
        $this->jadwals = $jadwals;
    }

    /**
     * ⬅️ TABEL DIMULAI BARIS 4
     */
    public function startCell(): string
    {
        return 'A4';
    }

    /**
     * DATA
     */
    public function collection()
    {
        return $this->jadwals->map(function ($j) {
            return [
                Carbon::parse($j->tanggal)->format('d/m/Y'),
                $j->pemesananJadwal->pemesanan->kode_pemesanan ?? '-',
                $j->lapangan->nama_lapangan ?? '-',
                substr($j->jam_mulai, 0, 5) . ' - ' . substr($j->jam_selesai, 0, 5),
                $j->pemesananJadwal->pemesanan->pengguna->nama ?? '-',
            ];
        });
    }

    /**
     * HEADER TABEL (SAMA DENGAN INDEX)
     */
    public function headings(): array
    {
        return [
            'Tanggal',
            'Kode Pemesanan',
            'Lapangan',
            'Jam',
            'Dibooking Oleh',
        ];
    }

    /**
     * STYLE HEADER TABEL
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
            'A' => 15,
            'B' => 25,
            'C' => 20,
            'D' => 20,
            'E' => 30,
        ];
    }

    /**
     * HEADER LAPORAN + BORDER
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // ===== HEADER LAPORAN =====
                $sheet->mergeCells('A1:E1');
                $sheet->setCellValue('A1', 'LAPORAN JADWAL LAPANGAN');

                $sheet->mergeCells('A2:E2');
                $sheet->setCellValue('A2', 'FUTSAL ACR');

                $sheet->mergeCells('A3:E3');
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
                $sheet->getStyle('A4:E4')->applyFromArray([
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
                $sheet->getStyle("A4:E{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
            }
        ];
    }
}
