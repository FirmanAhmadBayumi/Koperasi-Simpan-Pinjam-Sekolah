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

class ExportTanggungan implements FromCollection, WithHeadings, WithEvents, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Mengambil data dari tabel user dengan relasi pinjamans dan tanggungans
        $data = User::with(['pinjaman.tanggungan'])->get();

        // Mengonversi data menjadi bentuk koleksi yang diperlukan untuk diekspor
        $exportData = collect();

        foreach ($data as $user) {
            foreach ($user->pinjaman as $pinjaman) {
                foreach ($pinjaman->tanggungan as $tanggungan) {
                    $exportData->push([
                        'Nama' => $user->nama,
                        'Tanggal Pengajuan' => $pinjaman->tgl_pengajuan,
                        'Besar Pinjaman' => 'Rp. ' . number_format(ceil($pinjaman->besar_pinjaman), 0, ',', '.'),
                        'Tenor Pinjaman' => $pinjaman->tenor_pinjaman,
                        'Keterangan' => $pinjaman->keterangan,
                        'Bunga Pinjaman' => $tanggungan->bunga_pinjaman,
                        'Total Pinjaman' => 'Rp. ' . number_format(ceil($tanggungan->total_pinjaman), 0, ',', '.'),
                        'Iuran Per Bulan' => 'Rp. ' . number_format(ceil($tanggungan->iuran_perBulan), 0, ',', '.'),
                        'Sisa Pinjaman' => $tanggungan->sisa_pinjaman,
                        'Sisa Tenor' => $tanggungan->sisa_tenor,
                    ]);
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
            'Nama',
            'Tanggal Pengajuan',
            'Besar Pinjaman',
            'Tenor Pinjaman',
            'Keterangan',
            'Bunga Pinjaman',
            'Total Pinjaman',
            'Iuran Per Bulan',
            'Sisa Pinjaman',
            'Sisa Tenor'
        ];
    }

    /**
     * @param $tanggungan
     * @return array
     */
    public function map($tanggungan): array
    {
        return [
            $tanggungan['Nama'],
            $tanggungan['Tanggal Pengajuan'],
            $tanggungan['Besar Pinjaman'],
            $tanggungan['Tenor Pinjaman'],
            $tanggungan['Keterangan'],
            $tanggungan['Bunga Pinjaman'],
            $tanggungan['Total Pinjaman'],
            $tanggungan['Iuran Per Bulan'],
            $tanggungan['Sisa Pinjaman'],
            $tanggungan['Sisa Tenor'],
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
                $event->sheet->getDelegate()->getStyle('A1:J1')->applyFromArray([
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
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(20);

                // Apply borders to all cells
                $event->sheet->getDelegate()->getStyle("A1:J{$rowCount}")->applyFromArray([
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
        foreach (range('A', 'J') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }
}
