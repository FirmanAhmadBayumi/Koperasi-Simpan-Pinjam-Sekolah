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

class ExportTransaksiPinjaman implements FromCollection, WithHeadings, WithEvents, WithMapping, WithStyles
{
    public function collection()
    {
        $data = User::with(['pinjaman.tanggungan.transaksiPinjaman'])->get();
        $exportData = collect();

        foreach ($data as $user) {
            $isFirstRow = true;
            foreach ($user->pinjaman as $pinjaman) {
                foreach ($pinjaman->tanggungan as $tanggungan) {
                    foreach ($tanggungan->transaksiPinjaman as $transaksiPinjaman) {
                        $exportData->push([
                            'ID Anggota' => $isFirstRow ? $user->id_user : '',
                            'Nama' => $isFirstRow ? $user->nama : '',
                            'Iuran Per Bulan' => 'Rp. ' . number_format(ceil($tanggungan->iuran_perBulan), 0, ',', '.'),
                            'Jatuh Tempo' => $transaksiPinjaman->jatuh_tempo,
                            'Tanggal Pembayaran' => $transaksiPinjaman->tanggal_pembayaran ?? 'Belum Bayar',
                            'Keterangan' => $transaksiPinjaman->keterangan ?? 'Belum Lunas',
                        ]);
                        $isFirstRow = false;
                    }
                }
            }
        }

        return $exportData;
    }

    public function headings(): array
    {
        return [
            'ID Anggota',
            'Nama',
            'Iuran Per Bulan',
            'Jatuh Tempo',
            'Tanggal Pembayaran',
            'Keterangan',
        ];
    }

    public function map($row): array
    {
        return [
            $row['ID Anggota'],
            $row['Nama'],
            $row['Iuran Per Bulan'],
            $row['Jatuh Tempo'],
            $row['Tanggal Pembayaran'],
            $row['Keterangan'],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $data = $this->collection();
                $rowCount = $data->count() + 1;

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

                        $event->sheet->getDelegate()->mergeCells("A$startRow:A$endRow");
                        $event->sheet->getDelegate()->mergeCells("B$startRow:B$endRow");

                        $event->sheet->getDelegate()->getStyle("A$startRow:A$endRow")->getAlignment()->setVertical('center')->setHorizontal('center');
                        $event->sheet->getDelegate()->getStyle("B$startRow:B$endRow")->getAlignment()->setVertical('center')->setHorizontal('center');
                    }
                    $row++;
                }

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

    public function styles(Worksheet $sheet)
    {
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }
}
