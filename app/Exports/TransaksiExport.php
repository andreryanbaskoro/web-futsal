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

class TransaksiExport implements
    FromCollection,
    WithHeadings,
    WithCustomStartCell,
    WithStyles,
    WithColumnWidths,
    WithEvents
{
    protected $transaksis;
    protected $periode;

    public function __construct($transaksis, $periode = 'harian')
    {
        $this->transaksis = $transaksis;
        $this->periode = $periode;
    }

    public function startCell(): string
    {
        return 'A8';
    }

    public function collection()
    {
        return $this->transaksis->map(function ($trx) {

            return [
                Carbon::parse($trx->waktu_bayar)->format('d/m/Y H:i'),
                $trx->kode_pemesanan,
                $trx->pengguna->nama ?? '-',
                $trx->lapangan->nama_lapangan ?? '-',
                'Rp ' . number_format($trx->total_bayar, 0, ',', '.'),
                ucfirst($trx->status_pemesanan),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tanggal Bayar',
            'Kode Pemesanan',
            'Nama Pelanggan',
            'Lapangan',
            'Total Bayar',
            'Status',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            8 => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 22,
            'B' => 25,
            'C' => 30,
            'D' => 25,
            'E' => 20,
            'F' => 15,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $lastRow = $sheet->getHighestRow();

                $totalKeuangan = $this->transaksis->sum('total_bayar');
                $totalBooking = $this->transaksis->count();

                $rataRata = $totalBooking > 0
                    ? ($totalKeuangan / $totalBooking)
                    : 0;

                // HEADER
                $sheet->mergeCells('A1:F1');
                $sheet->setCellValue('A1', 'LAPORAN TRANSAKSI');

                $sheet->mergeCells('A2:F2');
                $sheet->setCellValue('A2', 'FUTSAL ACR');

                $sheet->mergeCells('A3:F3');
                $sheet->setCellValue(
                    'A3',
                    'Periode : ' . strtoupper(str_replace('_', ' ', $this->periode))
                );

                $sheet->mergeCells('A4:F4');
                $sheet->setCellValue(
                    'A4',
                    'Dicetak : ' . Carbon::now()->format('d/m/Y H:i')
                );

                // SUMMARY
                $sheet->setCellValue('A5', 'Total Keuangan');
                $sheet->setCellValue(
                    'B5',
                    'Rp ' . number_format($totalKeuangan, 0, ',', '.')
                );

                $sheet->setCellValue('C5', 'Total Booking');
                $sheet->setCellValue('D5', $totalBooking);

                $sheet->setCellValue('E5', 'Rata-rata');
                $sheet->setCellValue(
                    'F5',
                    'Rp ' . number_format($rataRata, 0, ',', '.')
                );

                // STYLE HEADER
                $sheet->getStyle('A1:F4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // STYLE SUMMARY
                $sheet->getStyle('A5:F5')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                ]);

                // HEADER TABLE
                $sheet->getStyle('A8:F8')->applyFromArray([
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

                // BORDER
                $sheet->getStyle("A8:F{$lastRow}")
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);
            },
        ];
    }
}
