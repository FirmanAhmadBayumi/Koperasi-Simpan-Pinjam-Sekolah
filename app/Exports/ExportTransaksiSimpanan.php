<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportTransaksiSimpanan implements FromCollection, WithHeadings, WithEvents, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Mengambil data dari tabel user dengan relasi simpanan_pokoks dan transaksi_pokoks
        $data = User::with(['simpananPokok.transaksiPokok'])->get();

        // Mengonversi data menjadi bentuk koleksi yang diperlukan untuk diekspor
        $exportData = collect();

        foreach ($data as $user) {
            $userRowSpan = $user->simpananPokok->reduce(function ($carry, $simpananPokok) {
                return $carry + $simpananPokok->transaksiPokok->count();
            }, 0);

            $isFirstRow = true;

            foreach ($user->simpananPokok as $simpananPokok) {
                foreach ($simpananPokok->transaksiPokok as $transaksiPokok) {
                    $exportData->push([
                        'ID Anggota' => $isFirstRow ? $user->id_user : '',
                        'Nama' => $isFirstRow ? $user->nama : '',
                        'Jatuh Tempo Pembayaran' => $transaksiPokok->jatuh_tempo,
                        'Tanggal Pembayaran' => $transaksiPokok->tanggal_pembayaran,
                        'Keterangan Pembayaran' => $transaksiPokok->keterangan,
                        'Total Simpanan Pokok' => $isFirstRow ? 'Rp. ' . number_format($simpananPokok->total_simpanan, 0, ',', '.') : '',
                    ]);
                    $isFirstRow = false;
                }
            }
        }

        return $exportData;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID Anggota',
            'Nama',
            'Jatuh Tempo Pembayaran',
            'Tanggal Pembayaran',
            'Keterangan Pembayaran',
            'Total Simpanan Pokok'
        ];
    }

    /**
     * @param $user
     * @return array
     */
    public function map($user): array
    {
        return [
            $user['ID Anggota'],
            $user['Nama'],
            $user['Jatuh Tempo Pembayaran'],
            $user['Tanggal Pembayaran'],
            $user['Keterangan Pembayaran'],
            $user['Total Simpanan Pokok'],
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Mengambil data untuk mengetahui jumlah baris
                $data = $this->collection();
                $rowCount = $data->count() + 1;

                // Style untuk heading
                $event->sheet->getDelegate()->getStyle('A1:F1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FFFFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF4F81BD'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Mengatur lebar kolom
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(20);

                $row = 2;
                foreach ($data as $index => $datum) {
                    if ($datum['ID Anggota'] !== '') {
                        $startRow = $row;
                        $rowSpan = $data->where('ID Anggota', $datum['ID Anggota'])->count();
                        $endRow = $row + $rowSpan - 1;

                        // Menggabungkan sel dan mengatur posisi teks
                        $event->sheet->getDelegate()->mergeCells("A$startRow:A$endRow");
                        $event->sheet->getDelegate()->mergeCells("B$startRow:B$endRow");
                        $event->sheet->getDelegate()->mergeCells("F$startRow:F$endRow");

                        $event->sheet->getDelegate()->getStyle("A$startRow:A$endRow")->getAlignment()->setVertical('center')->setHorizontal('center');
                        $event->sheet->getDelegate()->getStyle("B$startRow:B$endRow")->getAlignment()->setVertical('center')->setHorizontal('center');
                        $event->sheet->getDelegate()->getStyle("F$startRow:F$endRow")->getAlignment()->setVertical('center')->setHorizontal('center');
                    }
                    $row++;
                }

                // Apply borders to all cells
                $event->sheet->getDelegate()->getStyle("A1:F{$rowCount}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);
            },
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return void
     */
    public function styles(Worksheet $sheet)
    {
        // Set the width of columns to auto size
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }
}
